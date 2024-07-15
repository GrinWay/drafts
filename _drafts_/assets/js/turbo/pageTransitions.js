import Isser from '../helper/Isser.js';

document.addEventListener('turbo:before-fetch-request', (event) => {
	Isser.true();
});

document.addEventListener('turbo:before-render', (event) => {
	if (Isser.is()) {
		requestAnimationFrame(() => {
			event.detail.newBody.classList.remove('show');
			event.detail.newBody.classList.add('fade');	
		});		
	}
});

document.addEventListener('turbo:render', () => {
	if (Isser.is()) {
		setTimeout(() => {
			document.body.classList.add('show');
			document.body.classList.add('fade');
		}, 300);		
	}
	Isser.false();
});