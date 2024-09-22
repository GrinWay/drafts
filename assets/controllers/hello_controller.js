import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
		console.log('Yes');
        this.element.textContent = 'Hello Stimulus!';
    }
}
