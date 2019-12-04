<?php
defined('ROOT') OR exit('No direct script access allowed');
?>
<!doctype html>
<html class="no-js" lang="@( LANGUAGE_CODE )" prefix="@( PREFIX )">
<head>
	<!-- call header assets method -->
	@( pull::header_assets( $test_meta ) )
</head>
<body>
	<!--[if lte IE 9]>
		<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
	<![endif]-->

	<header style="text-align:center" class="header">
		<a href="@( base_url() )">
			@raw( img( IMG_DIR.'logo.png', 'width="100"' ) )
		</a>
		<h2>@( $my_name )</h2>
		<a target="_blank" href="https://github.com/kazuyamarino/nsy">View On Github</a>
		<hr>
	</header>
