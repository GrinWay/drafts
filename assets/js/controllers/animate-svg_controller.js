import { Controller } from '@hotwired/stimulus';
import { useHover } from 'stimulus-use'

// TODO: animate-svg -> grinway--animate-svg
/* Usage:
<svg 
	{{ stimulus_controller('animate-svg', {
		useHover: true,
	}) }}
	...
>
	<path .../>
	
	<animate
		{{ stimulus_target('animate-svg', 'hover') }}
		data-animate-svg-dispatch-click-to-id="masked_shape"
		data-animate-svg-run-on-enter
		...
	/>
</svg>
*/
export default class extends Controller {
	/**
     * Stimulus values
     */
	static values = {
		useHover: Boolean,
		useClick: Boolean,
	}
	
	/**
     * Stimulus targets for animation svg elements (animate, animateMotion, ...)
     */
	static targets = [
		'hover',
		'click',
	]
	
	/**
     * Target getter
     */
	get hovers() {
		return this.hasHoverTarget ? this.hoverTargets : []
	}
	
	/**
     * Target getter
     */
	get clicks() {
		return this.hasClickTarget ? this.clickTargets : []
	}
	
	/**
     * Stimulus
     */
	connect() {
		if (true === this.useHoverValue) {
			useHover(this)
		}
		if (true === this.useClickValue) {
			this.onClickEventListener = this.onClick.bind(this)
			this.element.addEventListener('click', this.onClickEventListener)
		}
	}
	
	/**
     * Stimulus
     */
	disconnect() {
		this.element.removeEventListener('click', this.onClickEventListener)
	}
	
	/**
     * 
     */
	onClick(event) {
		this.#desideWhatToDo(event)
	}
	
	/**
     * Stimulus use event listener
     */
	mouseEnter(event) {
		this.#desideWhatToDo(event)
	}
	
	/**
     * Stimulus use event listener
     */
	mouseLeave(event) {
		this.#desideWhatToDo(event)
	}
	
	/**
     * Helper
     */
	#desideWhatToDo(event) {
		const eventType = event.type
		
		//###> EVENT
		if ('mouseenter' === eventType) {
			this.hovers.forEach(el => {
				if (undefined !== el.dataset[this.#convertToSnakeCase(`${this.identifier}-run-on-enter`)]) {
					this.#runSvgAnimation(el, event.target)
					return
				}
				if (undefined !== el.dataset[this.#convertToSnakeCase(`${this.identifier}-run-on-leave`)]) {
					this.#stopSvgAnimation(el)
					return
				}
			})
			return
		}
		
		//###> EVENT
		if ('mouseleave' === eventType) {
			this.hovers.forEach(el => {
				if (undefined !== el.dataset[this.#convertToSnakeCase(`${this.identifier}-run-on-leave`)]) {
					this.#runSvgAnimation(el)
					return
				}
				if (undefined !== el.dataset[this.#convertToSnakeCase(`${this.identifier}-run-on-enter`)]) {
					this.#stopSvgAnimation(el, event.target)
					return
				}
			})
			return
		}
		
		//###> EVENT
		if ('click' === eventType) {
			this.clicks.forEach(el => {
				if (undefined !== el.dataset[this.#convertToSnakeCase(`${this.identifier}-run-on-click`)]) {
					this.#runSvgAnimation(el)
					return
				}
				
				if (undefined !== el.dataset[this.#convertToSnakeCase(`${this.identifier}-stop-on-click`)]) {
					this.#stopSvgAnimation(el)
					return
				}
			})
			return
		}
		
		throw new Error(`Event "${eventType}" was not caught in "${this.identifier}"`)
	}
	
	/**
     * Helper: runs svg animation
     */
	#runSvgAnimation(animationElement, defaultElementToDispatchClick) {
		animationElement.setAttribute('repeatDur', '1000h')
		const key = this.#convertToSnakeCase(`${this.identifier}-dispatch-click-to-id`)
		const elementToDispatchClickId = animationElement.dataset[key] ?? null
		
		let elementToDispatchClick = null
		if (null === elementToDispatchClickId) {
			elementToDispatchClick = defaultElementToDispatchClick
		} else {
			elementToDispatchClick = document.getElementById(elementToDispatchClickId)
		}
		
		elementToDispatchClick?.dispatchEvent(new Event('click', {
			bubbles: false, // Ensure don't influence on parents
		}))
	}
	
	/**
     * Helper: stops svg animation
     */
	#stopSvgAnimation(animationElement) {
		animationElement.setAttribute('repeatDur', '0.001s')
		requestAnimationFrame(() => {
			animationElement.removeAttribute('repeatDur')			
		})
	}
	
	/**
     * Helper
     */
	#convertToSnakeCase(string) {
		return string.replaceAll(/[\-](\w)/g, letter => letter.replace(/^[\-]/, '').toUpperCase())
	}
}
