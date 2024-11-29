import { Controller } from '@hotwired/stimulus'
import { useTransition } from '../../stimulus-use/useTransition.js'
import Cropper from 'cropperjs'

/* Usage

*/
export default class extends Controller {
	static targets = [
		'img',
	]
	
	/**
     * Target getter
     */
	get img() {
		return this.hasImgTarget ? this.imgTarget : undefined
	}
	
	connect() {
	}
	
	/**
     * dropzone:connect
     */
	dropzoneConnect(event) {
		console.error('dz connect', event)
	}
	
	/**
     * dropzone:change
     */
	dropzoneChange(event) {
		//console.error('dz change', event)
		
		const { detail: file } = event
		
		const reader = new FileReader()
		reader.readAsDataURL(file)
		
		reader.onload = async () => {
			console.log('onload')
			const base64EncodedFile = reader.result
			
			const response = await fetch('/api/save/client/image/10', {
				method: 'POST',
				body: JSON.stringify({
					data: base64EncodedFile,
					imageFilename: file.name,
				}),
			})
			if (true !== response.ok) {
				console.log('IMAGE __NOT__ SAVED')
				return
			}
			
			const json = await response.json()
			const absImgPathname = json?.absImgPathname
			if (!absImgPathname) {
				return
			}
			
			
		}
		
		
		//this.img?.setAttribute('src', this.publicImgDirValue)
	}
	
	/**
     * dropzone:clear
     */
	dropzoneClear(event) {
		console.error('dz clear', event)
	}
}
