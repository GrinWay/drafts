import { Controller } from '@hotwired/stimulus';
import { useHotkeys } from 'stimulus-use/hotkeys'
import { useTargetMutation } from 'stimulus-use'

export default class extends Controller {
	eventSource = null
	
	connect() {
		return;
		this.eventSource = new EventSource("http://127.0.0.1:8000/product/types")
		
		this.eventSource.onmessage = event => {
		  console.log("Новое сообщение", event?.data);
		}
	}
	
	disconnect() {
		if (this.eventSource) {
			this.eventSource.close()
			this.eventSource = null
		}
	}
}
