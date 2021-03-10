//cars.js
"use strict";

document.querySelector("#btnReset").addEventListener("click", function(e) {
	
	e.stopPropagation();
	
	let inputRefs = this.parentNode.querySelectorAll("input[type=text], input[type=hidden]");
	
	for(let i = 0; i < inputRefs.length; i++) {
		inputRefs.item(i).removeAttribute("value");
		inputRefs.item(i).removeAttribute("disabled");
	}
	
})

let aRefs = document.querySelectorAll("a[data-delete]");
for( let i = 0; i < aRefs.length; i++ ) {
	
	aRefs.item( i ).addEventListener( "click", function(e) {
		
		let regnr = this.getAttribute( "data-delete" );
		if( !confirm( "Vill du tabort: " + regnr + "?") ) {
			e.preventDefault();
			e.stopPropagation();
		}
	});
}

let formRefs = document.querySelectorAll("div[class=borderClass] form");
for( let i = 0; i < formRefs.length; i++ ) {
	
	let inputDelete = formRefs.item( i ).querySelector( "input[name=btnDelete]" );
	
	inputDelete.addEventListener( "click", function(e) {
		
		let regnr = this.parentNode.querySelector( "input[name=regnr]" ).value;
		if( !confirm( "Vill du tabort: " + regnr + "?") ) {
			e.preventDefault();
			e.stopPropagation();
		}
	});
}







