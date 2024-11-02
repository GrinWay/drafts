import { Controller } from '@hotwired/stimulus'
import { useTransition } from '../stimulus-use/useTransition'
import Trix from "trix"
import axios from 'axios'
import { AbstractTrixController } from './grinway/Stimulus/Controller/AbstractTrixController'

export default class extends AbstractTrixController {
	
	/**
     * Abstract method
     * @class AbstractTrixController
     */
	getApiAddFile() {
		return '/api/save/image'
	}
	
	trixAttachmentRemove() {}
	
	doSomething(event) {
		const editor = this.trixEditor.editor
		
		const json = JSON.stringify({"document":[{"text":[{"type":"string","attributes":{},"string":"It's a new state"},{"type":"string","attributes":{"blockBreak":true},"string":"\n"}],"attributes":[],"htmlAttributes":{}}],"selectedRange":[16,16]})
		
		editor.loadJSON(JSON.parse(json))
	}
	
	/**
     * API
     * Trix event listener
     * 
     * custom actions starts with "x-"
     */
	trixActionInvoke({ target, invokingElement, actionName }) {
		if ('x-custom-action' === actionName) {
			this.customAction()
		}
	}
	
	/**
     * Custom Action
     */
	customAction() {
		console.error('customAction')
	}
	
	/**
     * API
     */
	isFileUpload(event) {
		const { file: { type, size } } = event
		if (/image\/\w+/.test(type) && 100 * 1024 > size) {
			return true
		}
		return super.isFileUpload(event)
	}
	
}
