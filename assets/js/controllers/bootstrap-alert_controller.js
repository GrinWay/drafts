import { Controller } from '@hotwired/stimulus';
import { useHotkeys } from 'stimulus-use/hotkeys'
import { useTransition } from '../stimulus-use/useTransition.js'

/**
 * 
 */
export default class extends Controller {
	onClose() {
		console.log(`Bootstrap alert is inclined to get closed`)
	}

	onClosed() {
		console.log(`Bootstrap alert was closed`)
	}
}