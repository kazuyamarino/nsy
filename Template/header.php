<?php
	defined('ROOT') OR exit('No direct script access allowed');
?>
<!doctype html>
<html class="no-js" lang="<?php echo LANGUAGE_CODE ?>">
	<head>
    <?php
    	use Libraries\AssetManager\Assets;

      $Assets = new Assets();
      $Assets->pull_header_assets();
		?>
	</head>
	<body>
		<!--[if lt IE 8]>
			<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please upgrade your browser to improve your experience.</p>
		<![endif]-->
