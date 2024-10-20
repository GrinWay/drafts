import { Controller } from '@hotwired/stimulus'
import { getComponent } from '@symfony/ux-live-component'
import { useHover } from 'stimulus-use'

export default class extends Controller {
	#component = null
	
	static targets = [
		'firstName',
	]
	
	async initialize() {
		this.#component = await getComponent(this.element)
		
		//useHover(this, { element: this.#component.element, dispatchEvent: false })
		
		this.#component.on('connect', component => {
			//console.error(`LIVE: connect`)
		})
		this.#component.on('disconnect', component => {
			//console.error(`LIVE: disconnect`)
		})
		this.#component.on('render:started', () => {
			//console.error(`LIVE: render:started`)
		})
		this.#component.on('render:finished', component => {
			//console.error(`LIVE: render:finished`)
		})
		this.#component.on('response:error', () => {
			//console.error(`LIVE: response:error`)
		})
		this.#component.on('loading.state:started', () => {
			//console.error(`LIVE: loading.state:started`)
		})
		this.#component.on('loading.state:finished', () => {
			//console.error(`LIVE: loading.state:finished`)
		})
		this.#component.on('model:set', () => {
			//console.error(`LIVE: model:set`)
		})
	}
	
	async mouseEnter() {
		if (!this.#component) {
			return
		}
		
		/*
		this.#component.set('userDto.firstName', 'ENTER: The value was set via Stimulus Controller')
		//const result = await this.#component.action('getItems')
		this.#component.render()
		*/
	}

	mouseLeave() {
		if (!this.#component) {
			return
		}
		
		this.#component.set('userDto.firstName', 'LEAVE: The value was set via Stimulus Controller')
		this.#component.render()
	}
}
