import { Controller } from "@hotwired/stimulus"
import Trix from "trix"
import axios from "axios"

/**
 * Abstract Class
 */
export class AbstractTrixController extends Controller {
	
	/**
     * Abstract method
     * 
     * @class AbstractTrixController
     */
	getApiAddFile() {
		throw new Error('Implement this method')
	}
	
	/**
     * Abstract method
     * 
     * @class AbstractTrixController
     */
	getApiRemoveFile() {
		throw new Error('Implement this method')
	}
	
	/**
	 * API
	 * 
     * POST data getter
     */
	getApiAddFileData({ imageFilename, base64EncodedImage }) {
		return {
			imageFilename,
			base64EncodedImage,
		}
	}
	
	/**
	 * API
	 * 
     * POST data getter
     */
	getApiRemoveFileData({ imageFilename }) {
		return {
			imageFilename,
		}
	}
	
	/**
     * API
     * 
     * Trix event listener
     * custom actions starts with "x-"
     * 
     * if ('x-custom-action' === actionName) {
	 *    //...
	 * }
     */
	trixActionInvoke({ target, invokingElement, actionName }) {}
	
	/**
     * API
     * 
     * called when trix-toolbar is not explicitly described
     */
	getTrixDefaultToolbarHTML(defaultHtml) {
		return defaultHtml
	}
	
	/**
     * API
     */
	isFileUpload(event) {
		return false
	}
	
	/**
     * API
     * 
     * @return ''				valid
     * @return 'error text'		invalid
     */
	getInvalidMessage(trixEditorElement) {
		return ''
	}
	
	/**
	 * API
	 * 
     * Trix event listener
     */
	trixBeforeInitialize(event) {
		Trix.config.toolbar.getDefaultHTML = this.getTrixDefaultToolbarHTML.bind(this, Trix.config.toolbar.getDefaultHTML())
		
		//console.log(Trix.config.blockAttributes)
		//Trix.elements.TrixEditorElement.formAssociated = false
	}
	
	/**
	 * API
	 * 
     * Trix event listener
     */
	trixInitialize(event) {}
	
	/**
	 * API
	 * 
     * Trix event listener
     */
	trixPaste(event) {}
	
	/**
	 * API
	 * 
     * Trix event listener
     */
	trixSelectionChange(event) {}
	
	/**
	 * API
	 * 
     * Trix event listener
     */
	trixFocus(event) {}
	
	/**
	 * API
	 * 
     * Trix event listener
     */
	trixBlur(event) {}
	
	/**
	 * API
	 * 
     * Trix event listener
     */
	trixСhange(event) {
		this.customValidity(event.target)
	}
	
	/**
	 * API
	 * 
     * Trix event listener
     */
	trixFileAccept(event) {
		// prevent file uploading
		if (!this.isFileUpload(event)) {
			event.preventDefault()
		}
	}
	
	/**
	 * API
	 * 
     * Trix event listener
     */
	async trixAttachmentAdd(event) {
		const { attachment } = event
		const reader = new FileReader()
		const file = event.attachment.file
		if (!file) {
			return
		}

		const imageFilename = file.name
		
		reader.onload = () => {
			const base64EncodedImage = reader.result
			axios.post(
				this.getApiAddFile(),
				this.getApiAddFileData({
					imageFilename,
					base64EncodedImage,
				}),
				{
					onUploadProgress: ({loaded, total, progress, bytes, estimated, rate, upload = true}) => {
						this.setProgress(attachment, loaded / total * 100)
					},					
				},
			)
				.then(response => {
					const { publicImgDir, filename } = response.data ?? {}
					
					if (!filename || !publicImgDir) {
						return
					}
					
					const publicImgPathname = `${publicImgDir}/${filename}`
					
					this.finishLoading(attachment, {
						url: publicImgPathname,
						href: `${publicImgPathname}?content-disposition=attachment`,
					})
				})
				.catch(error => {
					console.error(error)
				})
				//.finally(() => {})
		}
		reader.readAsDataURL(event.attachment.file)
	}
	
	/**
	 * API
	 * 
     * Trix event listener
     */
	trixAttachmentRemove(event) {
		const { attachment } = event ?? {}
		const { file } = attachment ?? {}
		if (!file) {
			return
		}
		
		const imageFilename = file.name
		
		axios.post(
			this.getApiRemoveFile(),
			this.getApiRemoveFileData({
				imageFilename,
			}),
			{},
		)
	}
	
	/**
     * Stimulus targets
     */
	static targets = [
		'trixEditor',
		'trixToolbar',
		'input',
	]
	
	/**
     * Target getter
     */
	get trixEditor() {
		return this.hasTrixEditorTarget ? this.trixEditorTarget : undefined
	}
	
	/**
     * Target getter
     */
	get trixToolbar() {
		return this.hasTrixToolbarTarget ? this.trixToolbar : undefined
	}
	
	/**
     * Target getter
     */
	get input() {
		return this.hasInputTarget ? this.inputTarget : undefined
	}
	
	/**
     * File loading Helper
     */
	setProgress(attachment, progress) {
		if (Number(progress) !== progress) {
			return
		}
		
		attachment.setUploadProgress(progress)
	}
	
	/**
     * File loading Helper
     */
	finishLoading(attachment, attributes) {
		const { url, href } = attributes ?? {}
		
		if (!url || !href) {
			return
		}
		
		attachment.setAttributes(attributes)
	}
	
	/**
     * Helper
     */
	customValidity(trixEditorElement) {
		const invalidMessage = this.getInvalidMessage(trixEditorElement)
		if (invalidMessage) {
			trixEditorElement.setCustomValidity(invalidMessage)
		}
	}
}
