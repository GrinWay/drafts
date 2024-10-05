export class D {
	static debug(context) {
		if (undefined === context) {
			console.error('The first argument is the context')
			return
		}
		
		console.log(this.#getKeysAndValuesOfObject(context))
	}
	
	static #getKeysAndValuesOfObject(context) {
		if (undefined === context) {
			return
		}
		
		const showKeysAndValues = k => {
			console.group(`${context.constructor.name}["${k}"]`)
			console.log(`${context[k]}`)
			console.groupEnd()
		}
		Object.keys(context).forEach(showKeysAndValues)
	}
}