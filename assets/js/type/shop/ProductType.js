export class ProductType {

	/**
	 *
	 */
	static get ORDERED() {
		return Symbol.for('ORDERED')
	}

	/**
	 *
	 */
	static get SOLD_OUT() {
		return Symbol.for('SOLD_OUT')
	}
}