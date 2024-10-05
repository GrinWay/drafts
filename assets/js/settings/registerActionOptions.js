export function registerActionOptions(app) {
	app.registerActionOption('open', ({ event, value: exclamationSignPassedBeforeOption }) => {
		if ('toggle' === event.type) {
			return exclamationSignPassedBeforeOption === event.target.open
		} else {
			return true
		}	
	})

	app.registerActionOption('upper_case', ({ name, event, value, element, controller }) => {
		const { params } = event
		
		if (undefined !== event.key) {
			const isUpperCase = event.key === event.key.toUpperCase()
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