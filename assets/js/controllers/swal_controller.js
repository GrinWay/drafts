import { Controller } from '@hotwired/stimulus'
import { toastSwal, defaultSwal } from '../swal/swal_collection'
import Swal from 'sweetalert2/src/sweetalert2.js'

//import somethingDefault1 from '../check-import-a-new-instance/test.js'
//import somethingDefault2 from '../check-import-a-new-instance/test.js'
//import '../check-import-a-new-instance/test.js'

export default class extends Controller {
	connect() {
		
	}
	
	async showSwal() {
		
		const result = await defaultSwal.fire({
			icon: "success",
			showConfirmButton: true,
			title: "Signed in successfully",
			inputValidator: this.swalInputValidator.bind(this),
			preConfirm: this.swalPreConfirm.bind(this),
			preDeny: this.swalPreDeny.bind(this),
			didRender: this.swalDidRender.bind(this),
			didClose: this.swalDidClose.bind(this),
		})
		
		console.error(result)
	}
	
	swalInputValidator(input) {
		if (0 < input.match(/[^0-9+ \-]/)?.length) {
			return 'Введённое значение должно быть реальным номером телефона'
		}
	}
	
	swalPreConfirm(input) {
		//console.error(`PRE CONFIRM`, Swal.getProgressSteps())
		//return input
	}
	
	swalPreDeny() {
		//console.error(`PRE DENY`)
	}
	
	swalDidRender(swal) {
		// That's not an easy element (NOT WORKING)
		//swal.setAttribute('data-turbo-temporary', '')
		
		//const s = Swal
		//debugger
		//console.error(`DID RENDER`)
		
		// Works but the page is not cached
		//this.#tryToNotCacheThisPage()
	}
	
	close() {
		if (Swal.isVisible()) {
			Swal.getPopup().style.animationDuration = '0ms'
			Swal.close()
		}
	}
	
	#tryToNotCacheThisPage() {
		const noCacheThisPageMetaAlreadyExits = document.documentElement.querySelector('head meta[name="turbo-cache-control"][data-removable]')
		if (noCacheThisPageMetaAlreadyExits) {
			return
		}
		const position = 'beforeend'
		const meta = `<meta name="turbo-cache-control" content="no-cache" data-removable="">`
		document.documentElement.querySelector('head').insertAdjacentHTML(position, meta)
	}
	
	swalDidClose() {
		//this.#removeAllRemovableMetaCacheControl()
	}
	
	#removeAllRemovableMetaCacheControl() {
		const collection = document.documentElement.querySelectorAll('head meta[name="turbo-cache-control"][data-removable]')
		
		if (!collection?.length) {
			return
		}
		
		for(const meta of collection) {
			meta.remove()
		}
	}
}
