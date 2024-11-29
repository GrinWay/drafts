/**
 * Stimulus action option:
 * https://stimulus.hotwired.dev/reference/actions
 */
export function registerActionOptions(app) {
	app.registerActionOption('upper_case', ({ name, event, value, element, controller }) => {
		const { params } = event
		
		if (undefined !== event.key) {
			const isUpperCase = event.key === event.key?.toUpperCase()
			if (value) {
				return isUpperCase
			} else {
				return !isUpperCase
			}
		} else {
			return true
		}
	})
}