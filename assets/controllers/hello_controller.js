import { Controller } from '@hotwired/stimulus';
import testFunction from '../js/function/test_function';

export default class extends Controller {
    static targets = ['input', 'output']
	
	clicked() {
		this.outputTarget.textContent = this.inputTarget.value
		
		const text = this.element.dataset.proto
		const position = 'beforeend'
		this.element.insertAdjacentHTML(position, text)
	}
}
