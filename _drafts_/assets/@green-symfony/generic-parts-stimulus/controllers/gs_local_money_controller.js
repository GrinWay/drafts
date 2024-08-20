import { Controller } from '@hotwired/stimulus';
import { useHotkeys, useDebounce } from 'stimulus-use';
import parseMoney from 'parse-money';

/*
	Usage:
		symfony form:
			NumberType::class
			'grouping'	=> true,

		<input
			type="number" {# NUMBER TYPE! #}
			data-controller='<controller-name>'
			data-<controller-name>-locale-value=app.request.locale
			
			data-<controller-name>-change-speed-value=<>
		>
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {

	static values = {
		'locale':					String,
		'changeSpeed':				{ type: Number, default: 700 },
	};

	static debounces = [
		'normalize',
	];

	$el					= null;
	currentAmount		= null;
	prevLocal			= null;
	
	normalizeCallback	= null;
	onSubmitCallback	= null;
	
	localValueChanged() {
		this.assignNormalizedLocalValue();
	}
	
	connect() {
		useDebounce(this, {
			wait: this.changeSpeedValue,
		});
		this.normalizeCallback	= this.normalize.bind(this);
		this.onSubmitCallback	= this.onSubmit.bind(this);
		this.element.addEventListener('input',	this.normalizeCallback);
		this.element.closest('form').addEventListener('submit', this.onSubmitCallback, true);
		this.$el				= this.element;
		this.assignNormalizedLocalValue();
		
		this.doNormalize();
	}
	
	disconnect() {
		this.element.removeEventListener('input',	this.normalizeCallback);
		this.element.closest('form').removeEventListener('submit',	this.onSubmitCallback, true);
	}

	normalize(event) {
		this.$el = event.target; // target 
		this.assignNormalizedLocalValue();
		this.doNormalize();
	}
	
	onSubmit(event) {
		this.$el.value = this.currentAmount;
	}
	
	// ###> HELPER ###
	
	doNormalize() {
		const value = this.$el.value.trim();
		
		if (!value.match(/[0-9]/)) return;
		
		const money = parseMoney(value + ' ' + this.localeValue);
		this.currentAmount = money.amount;
		this.$el.value = this.currentAmount.toLocaleString(this.localeValue);
	}
	
	assignNormalizedLocalValue() {
		if (!this.localeValue || this.localValue === this.prevLocal) return;
		
		const matches						= this.localeValue.match(/^[a-z]{2}/i);
		
		if (!matches)						return;
		
		this.prevLocal						= matches[0];
		this.localeValue					= this.prevLocal;
	}
}
