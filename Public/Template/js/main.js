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
	// then show this base_url_js with dirname + slash result
	var base_url_js = protocol + slashes + host + slash + dirname + slash;
} else {
	// else show base_url_js without dirname + slash result
	var base_url_js = protocol + slashes + host + slash;
}

/*
Place any jQuery/helper plugins in here.
*/
$(document).foundation();
