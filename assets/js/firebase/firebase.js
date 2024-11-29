/**
 * https://firebase.google.com/docs/cloud-messaging/js/client
 */
import { initializeApp } from "firebase/app"
import { getMessaging, getToken } from "firebase/messaging"

class Firebase {
	/**
     * Inner
     */
	constructor() {
		this.#initializeApp()
	}
	
	/**
     * Inner
     */
	#initializeApp() {
		this.firebaseApp = initializeApp(
			JSON.parse(
				atob(process.env.APP_FIREBASE_CONFIG_BASE64)
			)
		)
		console.log('__ FIREBASE APP INITIALIZED __')
		
		this.messaging = getMessaging(this.firebaseApp)
		console.log('__ FIREBASE MESSAGING INITIALIZED __')
	}
	
	/**
     * Getter to export
     */
	get() {
		return this.firebaseApp
	}
	
	/**
     * Getter to export
     */
	getMessaging() {
		return this.messaging
	}
	
	/**
     * Getter to export
     */
	async getDeviceToken() {
		const permission = await Notification.requestPermission();
		
		if ('granted' !== permission) {
			return null
		}
		
		return await getToken(
			this.messaging,
			{
				vapidKey: process.env.APP_FIREBASE_PUBLIC_VAPID_KEY,
			},
		)
	}
}

const firebase = new Firebase()

const firebaseApp          = firebase.get()
const firebaseMessaging    = firebase.getMessaging()
const getDeviceToken       = firebase.getDeviceToken.bind(firebase)

export {
	firebaseApp,
	firebaseMessaging,
	getDeviceToken,
}