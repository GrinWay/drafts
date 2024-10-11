import { Controller } from '@hotwired/stimulus';
import { useHotkeys } from 'stimulus-use/hotkeys'
import { useTransition } from '../stimulus-use/useTransition.js'

export default class extends Controller {
	static values = {
		redirectToUri: {
			type: String,
			default: '/',
		},
	}
	
	redirect(event) {
		Turbo.visit(this.redirectToUriValue, { action: 'advance' })
	}
}
