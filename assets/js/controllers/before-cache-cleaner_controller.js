import { Controller } from '@hotwired/stimulus';
import { useHotkeys } from 'stimulus-use/hotkeys'
import { useTargetMutation } from 'stimulus-use'
import { htmlDecode } from '../function/parser'

/* Usage:
<div
	data-controller="before-cache-cleaner"
	data-action="turbo:before-cache@window->before-cache-cleaner#clean"
	clear_html="YOUR EMPTY/CLEAR HTML IS HERE"
></div>
*/
export default class extends Controller {
	static values = {
		clearHtmlAttributeName: {
			type: String,
			default: 'clear_html',
		},
	}
	
	/**
	 * Saves clear html during: turbo:before-cache
	 */
	clean(event) {
		this.element.innerHTML = htmlDecode(this.element.getAttribute(this.clearHtmlAttributeNameValue))
	}
}
