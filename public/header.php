<?php
defined('ROOT') OR exit('No direct script access allowed');
?>
<!doctype html>
<html class="no-js" lang="<?php echo LANGUAGE_CODE; ?>">
<head>
	<?php
	// call header assets method
	pull::header_assets();
	?>
</head>
<body>
	<!--[if lte IE 9]>
		<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
	<![endif]-->

	<header class="header">
		<a href="<?php echo base_url(); ?>"><img src="<?php echo IMG_DIR; ?>logo.png" width="100"/></a>
		<h2><?php echo $my_name; ?></h2>
		<a target="_blank" href="https://github.com/kazuyamarino/nsy">View On Github</a>
		<hr>
	</header>
