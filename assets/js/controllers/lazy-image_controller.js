import { Controller } from '@hotwired/stimulus';
import { useTransition } from '../stimulus-use/useTransition.js';

// TODO: lazy-image -> grinway--lazy-image
/** Note: Exactly this arrangement of controllers definitions
<img ...
	{{ stimulus_controller('lazy-image')|stimulus_controller('symfony/ux-lazy-image/lazy-image', {
		src: asset(<>)
	}) }}
>
 */
export default class extends Controller {
	connect() {
		useTransition(this, 'fade')
		
		this.boundOnConnect = this._onConnect.bind(this)
		this.boundOnReady = this._onReady.bind(this)
		
        this.element.addEventListener('lazy-image:connect', this.boundOnConnect);
        this.element.addEventListener('lazy-image:ready', this.boundOnReady);
    }

    disconnect() {
        // You should always remove listeners when the controller is disconnected to avoid side-effects
        this.element.removeEventListener('lazy-image:connect', this.boundOnConnect);
        this.element.removeEventListener('lazy-image:ready', this.boundOnReady);
    }

	/**
     * Event listener
     */
    _onConnect(event) {
		this.currentImageSrc = event.currentTarget.src
		this.newImageSrc = event.detail.image.src
	}

	/**
     * Event listener
     */
    async _onReady(event) {
		this.element.src = this.currentImageSrc
        await this.leave()
		this.element.src = this.newImageSrc
        await this.enter()
    }
}
