import { Controller } from '@hotwired/stimulus';
import { useHotkeys } from 'stimulus-use/hotkeys'
import { useTargetMutation } from 'stimulus-use'

export default class extends Controller {
	connect() {
		this.element.innerText = this.#getText()
		this.element.classList.add('badge')
		this.element.classList.add('text-bg-dark')
		
		//Turbo.visit('/')
	}
	
	#getText() {
		return Math.random().toFixed(2) * 100
	}
}
