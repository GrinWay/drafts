import { Controller } from '@hotwired/stimulus'
import { useTransition } from '../../stimulus-use/useTransition'

/* Usage

*/
// TODO: transition_controller
export default class extends Controller {
	
	static values = {
		initShown: {
			type: Boolean,
			default: true,
		},
		willLeave: {
			type: Boolean,
			default: true,
		},
		removeAfterLeave: {
			type: Boolean,
			default: true,
		},
		style: {
			type: String,
			default: 'fade',
		},
		disappearInMs: {
			type: Number,
			default: 3000,
		},
	}
	
	static targets = [
		'element',
	]
	
	#isUseTransitionInitialized = false
	#isProcessEnabled = false
	
	/**
	 * Target getter
	 */
	get _element() {
		return this.hasElementTarget ? this.elementTarget : undefined
	}
	
	elementTargetConnected(element) {
		if (this.#initUseTransition()) {
			this.#isProcessEnabled = true
			this.#process()			
		}
	}
	
	elementTargetDisconnected(element) {
		this.#isProcessEnabled = false
	}
	
	#initUseTransition() {
		if (true === this.#isUseTransitionInitialized) {
			return true
		}
		
		if (undefined === this._element) {
			return false
		}
		
		useTransition(this, this.styleValue, {
			initShown: false,
			element: this._element,
			hiddenClass: 'd-none',
		})
		this.#isUseTransitionInitialized = true
		return true
	}
	
	async #process() {
		if (false === this.#isProcessEnabled) {
			return
		}
		
		if (true === this.initShownValue) {
			await this.enter()
		}
		
		if (true === this.willLeaveValue) {
			setTimeout(async () => {
				await this.leave()
				if (true === this.removeAfterLeaveValue) {
					this._element?.remove()
				}
			}, this.disappearInMsValue)
		}
	}
}
