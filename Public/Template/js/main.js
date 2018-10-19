/*
BASE_URL Config Javascript
*/
var slash = "/";
var slashes ="//";
var protocol = location.protocol;
var host = window.location.hostname;

var dirname = "nsy"; // change according to the name of your project folder

// if dirname empty or undefined
if ( dirname ) {
	// then show this base_url with dirname + slash result
	var base_url = protocol + slashes + host + slash + dirname + slash;
} else {
	// else show base_url without dirname + slash result
	var base_url = protocol + slashes + host + slash;
}

/*
Place any jQuery/helper plugins in here.
*/
$(document).foundation();
