import Swal from 'sweetalert2/src/sweetalert2.js'

export const defaultSwal = Swal.mixin({
	title: '<small>Error!</small>',
	//titleText: 'Ooops',
	footer: 'FOOTER',
	toast: false,
	allowEscapeKey: false,
	input: 'tel',
	position: 'center',
	heightAuto: false,
	timer: 120000,
	timerProgressBar: true,
	text: 'Do you want to continue',
	icon: 'question',
	showConfirmButton: true,
	showCancelButton: true,
	showCloseButton: true,

	inputLabel: `What this input about?`,
	inputPlaceholder: '+7 999 123 12 34',

	inputAttributes: {
	  'data-controller': 'some',
	},

	//imageUrl: 'https://gratisography.com/wp-content/uploads/2024/03/gratisography-holographic-suit-1170x780.jpg',
	imageWidth: '100em',

	loaderHtml: '',
	showLoaderOnConfirm: true,

	returnInputValueOnDeny: true,

	confirmButtonColor: '#228B22',
	showDenyButton: true,
	confirmButtonText: 'Cool',
})

export const toastSwal = Swal.mixin({
	toast: true,
	position: "top-end",
	showConfirmButton: false,
	timer: 3000,
	timerProgressBar: true,
	didOpen: (toast) => {
		toast.onmouseenter = Swal.stopTimer;
		toast.onmouseleave = Swal.resumeTimer;
	}
})