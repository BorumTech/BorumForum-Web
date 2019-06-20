function loadXMLDoc(way, user_id, msg_id, el) {
	var xmlhttp;
	if (window.XMLHttpRequest){ // code for IE7+, Firefox, Chrome, Opera, Safari
	 xmlhttp=new XMLHttpRequest();
	} else { // code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function() {
	    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
	    	console.log("IN THE READY STATE...");
	    	console.log(xmlhttp.responseText);
	        document.getElementById(el).innerHTML = xmlhttp.responseText;
	        console.log(document.getElementById(el));
	    }
  	}
	const page = "/pages/ajax/vote" + way + ".php";
	xmlhttp.open("GET", page + "?user_id=" + user_id + "&message_id=" + msg_id, true);
	xmlhttp.send();

}

const buttons = document.querySelectorAll("#question-page-table button");
let otherButtons;

buttons.forEach(function(node) {
	node.addEventListener('click', function() {
		if (this.firstChild.firstChild.style.fill == "lightgreen") {
			this.firstChild.firstChild.style.fill = "rgb(221, 221, 221)";
		} else {
			this.firstChild.firstChild.style.fill = "lightgreen";
			otherButtons = getAllSiblings(this);
			console.log(otherButtons);
			otherButtons = otherButtons.map(function(el, ind, arr) {
				return el.firstChild.firstChild;
			});
			otherButtons.forEach(function(el, ind, arr) { // Make the other button for that post unvoted color
				el.style.fill = "rgb(221, 221, 221)";
			});
		}
	});
});

function getAllSiblings(element) {
    var result = [],
	node = element.parentNode.firstChild;

	while ( node ) {
		if ( node !== element && node.nodeType === Node.ELEMENT_NODE && node.tagName === element.tagName ) 
 			result.push( node );
		node = node.nextElementSibling || node.nextSibling;
	}
	// result will contain all type 1 siblings of "this"
	return result;
}

