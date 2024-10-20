import { Controller } from '@hotwired/stimulus';
import { useHotkeys } from 'stimulus-use/hotkeys'
import { useTargetMutation } from 'stimulus-use'
import { renderStreamMessage } from '@hotwired/turbo'

/* Usage
<div class=""
	{{ stimulus_controller('counter') }}
>
	<h1>
	<div class=""
		{{ stimulus_target('counter', 'output') }}
	></div>
	</h1>
	<div class="btn btn-primary"
		{{ stimulus_target('counter', 'add') }}
		data-action="
			click->counter#add
		"
	>+</div>
	<div class="btn btn-secondary"
		{{ stimulus_target('counter', 'sub') }}
		data-action="
			click->counter#sub
		"
	>-</div>
</div>
*/
export default class extends Controller {
	static values = {
		number: {
			type: Number,
			default: 0,
		},
	}
	
	static targets = [
		'output',
		'add',
		'sub',
	]
	
	get output() {
		return this.hasOutputTarget ? this.outputTarget : undefined
	}
	
	connect() {
		
		const streamMessage = `
<turbo-stream action="update" target="app_post_all_items">
	<template>
		This content was replaced by turbo-stream VIA JAVASCRIPT DIRECTLY (without fetch)!
	</template>
</turbo-stream>
		`
		//renderStreamMessage(streamMessage)
		
		const cookieNumber = this.cookieNumber
		if (undefined !== cookieNumber && null !== cookieNumber && NaN !== Number(cookieNumber)) {
			this.numberValue = Number(cookieNumber)
		}
		
		if (undefined === this.output) {
			return
		}
		this.output.innerText = this.numberValue
	}
	
	get cookieNumber() {
		return document.cookie.match(/counter_controller=(?<number>[^;]+)/)?.groups?.number
	}
	
	add(event) {
		if (undefined === this.output) {
			return
		}
		this.output.innerText = ++this.numberValue
	}
	
	sub(event) {
		Turbo.cache.clear()
		
		if (undefined === this.output) {
			return
		}
		this.output.innerText = --this.numberValue
	}
	
	numberValueChanged(nextValue, prevValue) {
		if (undefined === prevValue || nextValue !== Number(nextValue)) {
			return
		}
		
		let dateTime = new Date()
		dateTime.setDate(dateTime.getDate() + 1)
		dateTime = dateTime.toUTCString()
		document.cookie = `counter_controller=${nextValue}; expires=${dateTime}`
	}
}
