import { Popover, Toast, Tooltip } from 'bootstrap'

document.addEventListener('turbo:load', () => {
	// SWITCH ON POPOVER
	const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
	const popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
	  return new Popover(popoverTriggerEl)
	})

	// SWITCH ON TOASTS
	const toastElList = [].slice.call(document.querySelectorAll('.toast'))
	const toastList = toastElList.map(function (toastEl) {
	  toastEl = new Toast(toastEl, {})
	  //toastEl.show()
	  return toastEl
	})

	// SWITCH ON TOOLTIP
	new Tooltip(document.querySelector('body'), {
		selector: '.auto-tooltip',
		//template: `<div class="tooltip row row-cols-1 text-center" role="tooltip"><div class="tooltip-inner bg-white border border-dark border-2 rounded-pill text-dark"></div><div class="tooltip-arrow position-relative"><div class="app-bottom-dot rounded-circle"></div></div></div>`,
	})
})