<?php
	defined('ROOT') OR exit('No direct script access allowed');
?>
<!doctype html>
<html class="no-js" lang="<?php echo LANGUAGE_CODE ?>">
<head>
	<?php
		// call header assets method
		Assets::pull_header_assets();
	?>
</head>
<body>
	<!--[if lte IE 9]>
		<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
	<![endif]-->

	<header class="header">
		<img src="<?php echo IMG_DIR ?>logo.png" width="100"/>
		<h2>Welcome to NSY PHP Framework</h2>
		<h4>A very simple PHP Framework in history</h4>
		<a target="_blank" href="https://github.com/kazuyamarino/nsy">View On Github</a>
		<hr>
	</header>
