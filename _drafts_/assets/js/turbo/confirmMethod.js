import * as Turbo from '@hotwired/turbo';
import Swal from 'sweetalert2';

/*
	form data-turbo-confirm="<message>"
*/
Turbo.setConfirmMethod( async (message) => {
	const r = await Swal.fire({
		icon: 'question',
		title: message,
		showDenyButton: true,
	});
	return await r.value;
});