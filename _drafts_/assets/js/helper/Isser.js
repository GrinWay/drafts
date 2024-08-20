export default new class {
	status = false;
	
	false() {
		this.status = false;
	}
	
	true() {
		this.status = true;
	}
	
	is() {
		return this.status;
	}
}
