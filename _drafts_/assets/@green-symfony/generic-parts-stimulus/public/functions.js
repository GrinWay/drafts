import { useTransition } from 'stimulus-use';

export function gsUseFadeTransition(
	controller,
	element, 
	showInTheBeginning = true,
) {
	return gsUseBaseTransition(controller, element, 'fade', showInTheBeginning);
}

export function gsUseSwapTransition(
	controller,
	element, 
	showInTheBeginning = true,
) {
	return gsUseBaseTransition(controller, element, 'swap', showInTheBeginning);
}

// ###> HELPER ###

export function gsUseBaseTransition(controller, element, cssPrefix, showInTheBeginning) {
	const [ enter, leave, toggleTransition ] = useTransition(controller, {
		element: element,
		enterActive:				`${cssPrefix}-enter-active`,
		enterFrom:					`${cssPrefix}-enter-from`,
		enterTo:					`${cssPrefix}-enter-to`,
		leaveActive:				`${cssPrefix}-leave-active`,
		leaveFrom:					`${cssPrefix}-leave-from`,
		leaveTo:					`${cssPrefix}-leave-to`,
		hiddenClass:				'd-none',
		transitioned:				showInTheBeginning,
	});
	return { enter, leave, toggleTransition };
}