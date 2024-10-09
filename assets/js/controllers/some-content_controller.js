import { Controller } from '@hotwired/stimulus';
import { useHotkeys } from 'stimulus-use/hotkeys'
import { useTargetMutation } from 'stimulus-use'

export default class extends Controller {
	click() {
		console.error(`click`)
	}
}
