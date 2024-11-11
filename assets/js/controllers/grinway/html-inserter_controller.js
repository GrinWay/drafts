import { Controller } from '@hotwired/stimulus';
import { useHover } from 'stimulus-use'

/* Usage:
<div	
	{{ stimulus_controller('grinway--html-inserter', controllerValues={
		html: include('/svg/animate/_three_shapes.svg.twig'),
		onHover: true,
		insertToPosition: 'beforeend',
		reinsertTargetOnInsert: true,
	}) }}
>
	<div {{ stimulus_target('grinway--html-inserter', 'target') }}></div>
	<div {{ stimulus_target('grinway--html-inserter', 'removeWhenInserted') }}></div>
</div>

*/
// TODO: html-inserter
export default class extends Controller {
	removedElements = []
	
	/**
     * Stimulus values
     */
	static values = {
		html: {
			type: String,
			default: '',
		},
		onHover: {
			type: Boolean,
			default: false,
		},
		insertToPosition: {
			type: String,
			default: 'afterend',
		},
		reinsertTargetOnInsert: {
			type: Boolean,
			default: false,
		},
	}
	
	/**
     * Stimulus targets
     */
	static targets = [
		'target',
		'insertedElement',
		'removeWhenInserted',
	]
	
	/**
     * Target getter
     */
	get target() {
		return this.hasTargetTarget ? this.targetTarget : undefined
	}
	
	/**
     * Target getter
     */
	get insertedElements() {
		return this.hasInsertedElementTarget ? this.insertedElementTargets : []
	}
	
	/**
     * Target getter
     */
	get removeElementsWhenInserted() {
		return this.hasRemoveWhenInsertedTarget ? this.removeWhenInsertedTargets : []
	}
	
	/**
     * Event listener
     */
	connect(event) {
		if (true === this.onHoverValue) {
			useHover(this)
		}
	}
	
	/**
     * Stimulus use event listener
     */
	mouseEnter() {
		this.#insertElement();
	}

	/**
     * Stimulus use event listener
     */
	mouseLeave() {
		this.#removeInsertedElement();
	}
	
	/**
     * Helper
     */
	#insertElement() {
		if (true === this.reinsertTargetOnInsertValue) {
			const parent = this.target.parentElement
			const target = this.target
			this.target.remove()
			requestAnimationFrame(() => {
				
				parent.append(target)

				this.#doInsertElement()
			})
		} else {
			this.#doInsertElement()			
		}
	}
	
	/**
     * Helper
     */
	#doInsertElement() {
		if (undefined === this.target || !this.htmlValue) {
			return;
		}
		
		this.#removeElements()
		
		const dataStimulusTargetAttr = `data-${this.identifier}-target="insertedElement"`
		
		const stringElement = `<span ${dataStimulusTargetAttr}>${this.htmlValue}</span>`
		
		this.target.insertAdjacentHTML(this.insertToPositionValue, stringElement)
	}
	
	/**
     * Helper
     */
	#removeElements() {
		this.removedElements = []
		this.removeElementsWhenInserted.forEach(el => {
			this.removedElements.push({ el, parent: el.parentElement })
			el.remove()
		})
	}
	
	/**
     * Helper
     */
	#removeInsertedElement() {
		this.insertedElements.forEach(el => el.remove())
		this.removedElements.forEach(({el, parent}) => parent.append(el))
	}
}
