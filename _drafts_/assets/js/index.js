//###> LIB ###
import 'font-awesome/scss/font-awesome.scss';

//###> SIMPLE JS ###
import './sync/set-timezone-with-client-one';

//###> BUNDLE ###
/**
	With turbo prefetch COME UP TWO REQUESTS!
	because stimulus controller trigger twice:
		- page loads immediately	(1)
		- after AJAX loaded			(2)
import './turbo/prefetch.js';
*/
import './turbo/confirmMethod.js';
import './turbo/pageTransitions.js';