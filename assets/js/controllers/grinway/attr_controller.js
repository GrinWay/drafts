import { Controller } from '@hotwired/stimulus';
import { useHover } from 'stimulus-use'

/* This class is intended to work with attributes:
	When stimulus use actions happen, all targets "attr" execute their data-notations
*/

/* Usage: data-[controller_method_name]-[what_to_do_method_name]='key=val;key2;key3="val3"'
<div
	{{ stimulus_controller('grinway--attr', controllerValues={
		useHover: true,
	}) }}
>
	<div
		{{ stimulus_target('grinway--attr', 'attr') }}
			
		data-controller-actions='mouseEnter,mouseLeave'
		
		data-mouse-enter-set-attribute='begin=0s;oneMoreWithValue=value'
		data-mouse-leave-set-attribute='end=0s;oneMoreWithoutValue'
		
		data-mouse-enter-remove-attribute='end'
		data-mouse-leave-remove-attribute='begin'
	></div>
</div>
*/
// TODO: grinway--attr
export default class extends Controller {
	
	/**
     * Stimulus values
     */
	static values = {
		useHover: {
			type: Boolean,
			default: false,
		},
		attributesDelimiter: {
			type: String,
			default: ';',
		},
		attributesAssignSign: {
			type: String,
			default: '=',
		},
	}
	
	/**
     * Stimulus targets
     */
	static targets = [
		'attr',
	]
	
	/**
     * Target getter
     */
	get setAttributeElements() {
		return this.hasAttrTarget ? this.attrTargets : []
	}
	
	/**
     * Stimulus
     */
	connect() {
		if (true === this.useHoverValue) {
			useHover(this)
		}
	}
	
	/**
     * Stimulus use event listener
     */
	mouseEnter(event) {
		this.#setAttributeFor('mouseEnter', 'setAttribute')
		this.#setAttributeFor('mouseEnter', 'removeAttribute')
	}
	
	/**
     * Stimulus use event listener
     */
	mouseLeave() {
		this.#setAttributeFor('mouseLeave', 'setAttribute')
		this.#setAttributeFor('mouseLeave', 'removeAttribute')
	}
	
	/**
     * Helper
     */
	#setAttributeFor(controllerMethodName, applyMethodName) {
		this.setAttributeElements.forEach(target => {
			const useActions = target.dataset['controllerActions'] ?? ''
			if (!useActions.includes(controllerMethodName)) {
				return
			}
			
			const applyMethodNamePascalCase = applyMethodName.charAt(0).toUpperCase() + applyMethodName.slice(1)
			
			const data = target.dataset[controllerMethodName+applyMethodNamePascalCase] ?? ''
			if ('' !== data.trim()) {
				const keyVals = data.split(this.attributesDelimiterValue) ?? []
				keyVals.forEach(keyVal => {
					let [key, value] = keyVal.split(this.attributesAssignSignValue)
					
					key = key.replace(/^\s*['"](.*)['"]\s*$/, '$1')
					
					if (undefined === value) {
						value = ''
					} else {
						value = value.replace(/^\s*['"](.*)['"]\s*$/, '$1')						
					}
					
					target[applyMethodName](key, value)
				})
			}
		})
	}
}
