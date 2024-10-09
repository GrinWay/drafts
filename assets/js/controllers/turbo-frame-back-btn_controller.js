import { Controller } from '@hotwired/stimulus';
import { useHotkeys } from 'stimulus-use/hotkeys'
import { useTargetMutation } from 'stimulus-use'

/* Usage:
<turbo-frame
	{{ stimulus_controller('turbo-frame-back-btn', controllerValues={
		uri: app.request.uri,
	}) }}
	data-action="
		turbo:before-frame-render->turbo-frame-back-btn#addBackBtnToNextElement
	"
>
	<div class="">Item 1</div>
	<div class="">Item 2</div>
	<small>...</small>
	<a href="...">Show all items</a>
</turbo-frame>
*/
export default class extends Controller {
	static values = {
		uri: {
			type: String,
			default: '',
		},
		btnText: {
			type: String,
			default: 'Back',
		},
		formAttributes: {
			type: String,
			default: '',
		},
		formId: {
			type: String,
			default: 'app_turbo_frame_back_btn',
		},
		btnAttributes: {
			type: String,
			default: 'class="btn btn-primary"',
		},
	}
	
	connect() {
		if (!this.uriValue) {
			console.error('Provide uri value')
		}
	}
	
	addBackBtnToNextElement(event) {
		const { detail: { newFrame } } = event
		const htmlBackForm = `
		<form
			method="get"
			action="${this.uriValue}"
			id="${this.formIdValue}"
			${this.formAttributesValue}
		>
			<button
				type="submit"
				${this.btnAttributesValue}
			>${this.btnTextValue}</button>
		</form>
		`
		const turboFrameHasBackBtnForm = event.target.querySelector(`form[id="${this.formIdValue}"]`)
		if (null === turboFrameHasBackBtnForm) {
			newFrame.insertAdjacentHTML('beforeend', htmlBackForm)			
		}
	}
}
