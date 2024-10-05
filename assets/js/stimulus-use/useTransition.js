import { useTransition as stimulusUseTranslation } from 'stimulus-use';

// TODO: useTransition
export function useTransition(
	controller,
	style,
	{
		initShown,
		hiddenClass,
		element,
	} = {}
) {
	initShown ??= true
	hiddenClass ??= 'd-none'
	element ??= controller.element

	return stimulusUseTranslation(controller, {
		element,
		hiddenClass,
		transitioned:     initShown,
		enterActive:     `${style}-enter-active`,
		enterFrom:       `${style}-enter-from`,
		enterTo:         `${style}-enter-to`,
		leaveActive:     `${style}-leave-active`,
		leaveFrom:       `${style}-leave-from`,
		leaveTo:         `${style}-leave-to`,
	})
}