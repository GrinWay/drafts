import { Controller } from '@hotwired/stimulus'
import 'cookie'
import { useDebounce } from 'stimulus-use'

// TODO: theme -> grinway--theme
/** Usage:

<div class="form-check form-switch"
	{{ stimulus_controller('theme') }}
>
	<label class="form-check-label" for="app-switch-theme"
	>
		<i class="bi bi-calendar2-check"></i> Тёмная тема
	</label>
	<input class="form-check-input" type="checkbox" id="app-switch-theme"
		{{ stimulus_target('theme', 'checkbox') }}
		data-action="
			input->theme#theme
		"
		data-theme-class-param="dark"
	>
</div>
 */
export default class Theme extends Controller {
	static prefix = 'theme-'
	static defaultThemeClassWithoutPrefix = 'light'
	static themeCookieName = 'theme'
	
	/**
     * Stimulus tragets
     */
	static targets = [
		'checkbox',
	]
	
	/**
     * Target getter
     */
	get checkboxes() {
		return this.hasCheckboxTarget ? this.checkboxTargets : undefined
	}
	
	/**
     * Stimulus use
     */
	static debounces = [
		{
			name: 'doSaveThemeClassToCookie',
			wait: 300,
		},
	]
	
	/**
     * Stimulus
     */
	connect() {
		useDebounce(this)
		
		this.cookie = require('cookie')
		
		this.assignThemeClassToElements = [
			document.querySelector('body'),
		]
		
		this.#setCheckboxAttribute()
	}
	
	/**
     * Event listener
     */
	theme(event) {
		this.#lockUntilCookieSaved()
		
		const checked = event.currentTarget.checked
		const assignClassToChecked = `${Theme.prefix}${event.params.class ?? Theme.defaultThemeClassWithoutPrefix}`
		const assignClassToUnchecked = `${Theme.prefix}${Theme.defaultThemeClassWithoutPrefix}`
		
		this.#assignThemeClasseToElements(checked, assignClassToChecked, assignClassToUnchecked)
		this.#saveThemeClassToCookie(checked, assignClassToChecked, assignClassToUnchecked)
	}
	
	/**
     * Helper
     */
	#lockUntilCookieSaved() {
		this.checkboxes.forEach(el => el.disabled = true)
		this.element.classList.add('muted')
	}
	
	/**
     * Helper
     */
	#unlock() {
		this.checkboxes.forEach(el => el.disabled = false)
		this.element.classList.remove('muted')
	}
	
	/**
     * Helper: relies on the cookie theme
     */
	#setCheckboxAttribute() {
		if (undefined === this.checkboxes) {
			return
		}
		
		const parsedCookie = this.cookie.parse(document.cookie)
		const cookieSavedTheme = parsedCookie[Theme.themeCookieName]
		this.checkboxes.forEach(el => {
			const _class = el.dataset.themeClassParam ?? null
			
			if (null === _class) {
				return
			}
			
			if (cookieSavedTheme?.includes(_class)) {
				el.setAttribute('checked', '')
				el.checked = true
			} else {
				el.checked = false
				el.removeAttribute('checked')
			}
		})
	}
	
	/**
     * Helper
     */
	#saveThemeClassToCookie(checked, assignClassToChecked, assignClassToUnchecked) {
		const assignCookieToTheme = checked ? assignClassToChecked : assignClassToUnchecked
		const expires = new Date()
		expires.setFullYear(expires.getFullYear() + 1)
		const themeCookie = this.cookie.serialize(Theme.themeCookieName, assignCookieToTheme, {
			expires,
		})
		this.doSaveThemeClassToCookie({ themeCookie })
	}
	
	/**
     * Helper
     */
	doSaveThemeClassToCookie({ themeCookie }) {
		document.cookie = themeCookie
		this.#setCheckboxAttribute()
		this.#unlock()
	}
	
	/**
     * Helper
     */
	#assignThemeClasseToElements(checked, assignClassToChecked, assignClassToUnchecked) {
		this.assignThemeClassToElements.forEach(el => {
			if (true === checked) {
				el.classList.add(assignClassToChecked)
				if (el.classList.contains(assignClassToUnchecked)) {
					el.classList.remove(assignClassToUnchecked)
				}
			} else {
				el.classList.add(assignClassToUnchecked)
				if (el.classList.contains(assignClassToChecked)) {
					el.classList.remove(assignClassToChecked)
				}
			}
		})
	}
}