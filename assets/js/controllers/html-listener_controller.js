import { Controller } from '@hotwired/stimulus';
import { useHotkeys } from 'stimulus-use/hotkeys'
import { useTransition } from '../stimulus-use/useTransition.js'

export default class extends Controller {
	static targets = [
		'body',
	]
	
	connect() {
		useTransition(this, 'slide', { initShown: true })
	}
	
	get body() {
		return this.hasBodyTarget ? this.bodyTarget : undefined
	}

	/**
	 * 
	 */
	turboBeforeVisit(event) {
		const { detail } = event ?? {}
		const { url } = detail ?? {}
		
		//event.preventDefault()
		//console.error(event)
		//console.error(`URL: ${url}`)
	}
	
	/**
	 * 
	 */
	async turboBeforeRender(event) {
		event.preventDefault()
		//await this.leave()
		//await this.enter()
		event.detail.resume()
	}
	
	/**
	 * 
	 */
	turboClick(event) {
		//console.error(`CLICK`)
	}
	
	/**
	 * 
	 */
	turboVisit(event) {
		console.log(`__${event.detail.action.toUpperCase()}__`)
	}
	
	/**
	 * 
	 */
	turboRender(event) {
		console.log(`__RENDER_METHOD__${event.detail.renderMethod}__`)
	}
	
	/**
	 * 
	 */
	async turboBeforePrefetch(event) {
		//event.preventDefault()
		//console.error('PREFETCH')
	}
	
	/**
	 * 
	 */
	turboBeforeFetchRequest(event) {
		let message = null
		if ('prefetch' === event.detail.fetchOptions.headers['X-Sec-Purpose']) {
			message = `Content is prefetching... (hover)`
		} else {
			message = `BEFORE A USUAL FETCH (form, link)`
		}
		console.log(message)
	}
	
	/**
	 * 
	 */
	async turboSubmitStart(event) {
		event.detail.formSubmission
	}
	
	/**
	 * 
	 */
	async turboFrameLoad(event) {
		event.target
		//debugger
	}
	
	/**
	 * 
	 */
	async turboBeforeFetchResponse(event) {
		//await this.leave()
	}
	
	/**
	 * 
	 */
	turboLoad(event) {
		console.log(`TURBO LOAD`)
		if (undefined !== event.detail.timing.visitEnd) {
			const loadedIn = Number(event.detail.timing.visitEnd) - Number(event.detail.timing.visitStart)
			console.log(`__LOADED_IN_${loadedIn}_MILI_SECONDS__`)
		}
	}
	
	/**
	 * 
	 */
	turboFrameMissing(event) {
		event.preventDefault()
		event.target.innerHTML = `<h1>ERROR! NO FRAME "#${event.target.id}" IN "${event.detail.response.url}" </h1>`
	}
	
	/**
	 * 
	 */
	turboMorph(event) {
		console.log(`MORPH`)
	}
	
	/**
	 * Always empty
	 */
	_(event) {}
}
