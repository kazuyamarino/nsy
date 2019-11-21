<?php
/*
User Agent

try it :
$ua = get_ua();
echo $ua['name'];
echo '<br>';
echo $ua['version'];
echo '<br>';
echo $ua['platform'];
echo '<br>';
echo $ua['userAgent'];
 */
// http://www.php.net/manual/en/function.get-browser.php#101125
function get_ua() {
	$u_agent = $_SERVER['HTTP_USER_AGENT'];
	$bname = 'Unknown';
	$platform = 'Unknown';
	$version= "";
	// First get the platform?
	if (preg_match('/Android/i', $u_agent)) {
		$platform = 'Android';
	} elseif (preg_match('/linux/i', $u_agent)) {
		$platform = 'Linux';
	} elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
		$platform = 'Macintosh';
	} elseif (preg_match('/windows|win32/i', $u_agent)) {
		$platform = 'Windows';
	}
	// Next get the name of the useragent yes seperately and for good reason
	if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) {
		$bname = 'Internet Explorer';
		$ub = "MSIE";
	} elseif(preg_match('/Firefox/i',$u_agent)) {
		$bname = 'Mozilla Firefox';
		$ub = "Firefox";
	} elseif(preg_match('/OPR/i',$u_agent) && !preg_match('/OPR1/i',$u_agent)) {
		$bname = 'Opera';
		$ub = "OPR";
	} elseif(preg_match('/Chrome/i',$u_agent)) {
		$bname = 'Google Chrome';
		$ub = "Chrome";
	} elseif(preg_match('/Safari/i',$u_agent)) {
		$bname = 'Apple Safari';
		$ub = "Safari";
	} elseif(preg_match('/Netscape/i',$u_agent)) {
		$bname = 'Netscape';
		$ub = "Netscape";
	}
	// finally get the correct version number
	$known = array('Version', $ub, 'other');
	$pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
	if (!preg_match_all($pattern, $u_agent, $matches)) {
		// we have no matching number just continue
	}
	// see how many we have
	$i = count($matches['browser']);
	if ($i != 1) {
		//we will have two since we are not using 'other' argument yet
		//see if version is before or after the name
		if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
			$version= $matches['version'][0];
		} else {
			$version= $matches['version'][1];
		}
	} else {
		$version= $matches['version'][0];
	}
	// check if we have a number
	if ($version==null || $version=="") {$version="?";}

	return array(
		'userAgent' => $u_agent,
		'name'      => $bname,
		'version'   => $version,
		'platform'  => $platform,
		'pattern'    => $pattern
	);
}

// ------------------------------------------------------------------------

/*
PHP array_flatten() function. Convert a multi-dimensional array into a single-dimensional array.
https://gist.github.com/SeanCannon/6585889#gistcomment-2922278
 */
function array_flatten($items)
{
    if (! is_array($items)) {
        return [$items];
    }

    return array_reduce($items, function ($carry, $item) {
        return array_merge($carry, array_flatten($item));
    }, []);
}

// ------------------------------------------------------------------------

/*
Create Random Number
 */
function generate_num($prefix = 'NSY-', $id_length = 6, $num_length = 10) {
	$zeros = str_pad(null, $id_length, 0, STR_PAD_LEFT);
	$nines = str_pad(null, $id_length, 9, STR_PAD_LEFT);

	$ids = str_pad(mt_rand($zeros, $nines), $num_length, $prefix, STR_PAD_LEFT);

	return $ids;
}

// ------------------------------------------------------------------------

/*
Get URI Segment
 */
function get_uri_segment($key = null) {
	$uriSegments = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

	if (array_key_exists($key, $uriSegments)) {
		return $uriSegments[$key];
	} else {
		return '<p>Segment does not exist</p>';
		exit();
	}
}

// ------------------------------------------------------------------------

// Define helpers here.
