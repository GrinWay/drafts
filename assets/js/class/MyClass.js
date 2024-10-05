import AbstractMyClass from './AbstractMyClass.js'
import { constant } from '../func.js'
import { ProductType } from '../type/shop/ProductType.js';

export default class MyClass extends AbstractMyClass {
	#args = []
	publicProperty = 'publicProperty'
	
	constructor(...args) {
		super()
		
		this[ProductType.ORDERED] = false
		
		this.#args = args
	}
	
	static get TYPE() {
		return 'CONSTANT'
	}
	
	args(val = 'default', required) {
		console.log(required)
		return this.#args
	}
	
	generateFunction() {
		//return this
		//return () => this
		return (function() {
			return this
		})()
	}
	
	/*
	set args(...args) {
		this.#args = args
		return this
	}
	*/
}