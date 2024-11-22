import { Popover, Toast, Tooltip } from 'bootstrap'

import 'bootstrap-icons/font/bootstrap-icons'
import 'bootstrap-icons/font/bootstrap-icons.scss'

import '@simonwep/pickr/dist/themes/classic.min.css'
import '@simonwep/pickr/dist/themes/monolith.min.css'
import '@simonwep/pickr/dist/themes/monolith.min.css'

import '@sweetalert2/themes/dark/dark.css'
import 'sweetalert2/dist/sweetalert2.min.js';

import 'lightgallery/css/lightgallery.css'

import 'swiper/css/bundle' // за счёт ключа exports в package.json

import '../styles/app.scss';
import './bootstrap.js';
import './turbo/settings.js';

import 'rtlcss';

import './chart/init';

//const instance1 = require('./default/new-instance.js')
//const instance2 = require('./default/new-instance.js')
//console.log(instance1 == instance2)

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