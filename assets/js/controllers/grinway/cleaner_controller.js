import { Controller } from '@hotwired/stimulus';
import { useHotkeys } from 'stimulus-use/hotkeys'
import { useTargetMutation } from 'stimulus-use'
import { htmlDecode } from '../../function/parser'

/* Usage:
<div
	data-controller="grinway--cleaner"
	data-action="turbo:before-cache@window->grinway--cleaner#clean"
	clear_element="YOUR CLEAR ELEMENT IS HERE"
></div>
*/
// TODO: before-cache-cleaner
export default class extends Controller {
	static values = {
		clearElementAttributeName: {
			type: String,
			default: 'clear_element',
		},
	}
	
	/**
	 * Saves clear html during: turbo:before-cache
	 */
	clean(event) {
		this.element.innerHTML = htmlDecode(this.element.getAttribute(this.clearElementAttributeNameValue))
	}
}
