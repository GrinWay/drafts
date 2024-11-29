//import { Auth } from "googleapis // it won't work cuz it's a "web" target but not "node"
import { Controller } from '@hotwired/stimulus'
import { getDeviceToken } from '../firebase/firebase.js'

export default class extends Controller {
	async connect() {
		this.element.insertAdjacentHTML('beforeend', await getDeviceToken())
	}
}