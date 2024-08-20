import { Controller } from '@hotwired/stimulus';
import Swal from 'sweetalert2/src/sweetalert2.js';
import { gsUseFadeTransition } from '@grinway/generic-parts-stimulus/public/functions';
import { ApplicationController, useDebounce } from 'stimulus-use';

/*
Usage:
	
*/

/* stimulusFetch: 'lazy' */
export default class extends Controller {

	static debounces = [
		'initHideTimer',
	];

	static values = {
		'delay':					{ type: Number,			default: 3000 },
		'persist':					{ type: Boolean,		default: false },
	};
	
	persistTargetConnected(event) {
		this.initHideTimer();
	}
	
	delayTargetConnected(event) {
		this.initHideTimer();
	}

	connect() {
		useDebounce(this, { wait: 100 });
		gsUseFadeTransition(this, this.element);
		
		/*
		Swal.fire({
			toast: true,
			position: 'top-end',
			showConfirmButton: false,
			timer: 3000,
			timerProgressBar: true,
			didOpen: (toast) => {
				toast.addEventListener('mouseenter', Swal.stopTimer)
				toast.addEventListener('mouseleave', Swal.resumeTimer)
			}
		});
		*/
	}
	
	// ###> HELPER ###
	
	initHideTimer() {
		if (!this.persistValue) {
			setTimeout(() => this.leave().then(() => { this.element.remove(); }), this.delayValue);
		}
	}
}
