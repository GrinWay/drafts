import { Controller } from '@hotwired/stimulus'

export default class extends Controller {
	static targets = [
		'input',
	]
	
	connect() {
		/*
		setTimeout(() => {
			this.inputTarget.value = 'sajldkfjasldkfjaslkdfjsadl'
			this.dispatch('input', { target: this.inputTarget, prefix: false })
		}, 1000)
		*/
	}
	
	onChange(event) {
		console.log(`ON CHANGE`, event)
	}
}
