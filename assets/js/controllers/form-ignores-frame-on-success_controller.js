import { Controller } from '@hotwired/stimulus'

/* Usage:
<turbo-frame id="..."
	{{ stimulus_controller('form-ignores-frame-on-success') }}
	data-action="
		turbo:submit-end->form-ignores-frame-on-success#ignoreFrame
		turbo:before-frame-render->form-ignores-frame-on-success#pauseDefaultBehaviour
	"
>
	<form method="post">...</form>
</turbo-frame>
*/
// TODO: form-ignores-frame-on-success_controller.js
export default class extends Controller {
	
	/**
	 * 
	 */
	ignoreFrame(event) {
		this.isSuccessFormSubmission = event.detail.success
		this.isRedirectResponse = event.detail.fetchResponse.response.redirected
		if (!this.#isIgnoreFrame) {
			return
		}
		const uri = event.detail.fetchResponse.response.url
		Turbo.visit(uri, { action: 'advance' })
	}
	
	/**
	 * 
	 */
	pauseDefaultBehaviour(event) {
		if (!this.#isIgnoreFrame) {
			return
		}
		event.preventDefault()			
	}

	get #isIgnoreFrame() {
		return this.isSuccessFormSubmission && this.isRedirectResponse
	}
}
