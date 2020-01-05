/**
 * BASE_URL Config Javascript
 */

/**
 * Change according to the name of your project folder
 *
 * @type {String}
 */
var dirname = "nsy"; // defined
// var dirname = ""; // undefined

function base_url(url)
{
    var slash = "/";
    var slashes ="//";
    var protocol = location.protocol;
    var host = window.location.hostname;

    // if dirname not empty or defined
    if (dirname ) {
        // then show this base_url with dirname + slash result
        var base_url = protocol + slashes + host + slash + dirname + slash;
        if (url ) {
            return base_url + url;
        } else {
            return base_url;
        }
    } else {
        // else show base_url without dirname + slash result
        var base_url = protocol + slashes + host + slash;
        if (url ) {
            return base_url + url;
        } else {
            return base_url;
        }
    }
}
