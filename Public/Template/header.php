<?php
defined('ROOT') OR exit('No direct script access allowed');
?>

<!doctype html>
<html class="no-js" lang="<?php echo LANGUAGE_CODE ?>">
<head>
	<?php
	use System\Libraries\AssetManager\Assets;

	$Assets = new Assets();

	// call footer assets function js
	$Assets->pull_header_assets();
	?>
</head>
<body>
	<!--[if lt IE 8]>
	<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please upgrade your browser to improve your experience.</p>
	<![endif]-->

	<header style="margin-top: 1rem;" class="header grid-x grid-padding-x">
		<div class="large-9 cell">
			<h1>Welcome to NSY HMVC Page</h1>
			<h4>NSY PHP Framework</h4>
		</div>
		<div class="large-3 cell">
			<div class="text-center">
				<a target="_blank" href="https://github.com/kazuyamarino/nsy"><i class="fab fa-github fa-5x"></i>
					<p>View On Github</p>
				</a>
			</div>
		</div>
	</header>
