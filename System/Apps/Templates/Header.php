<!doctype html>
<html class="no-js" lang="@( get_lang_code() )" prefix="@( get_og_prefix() )">

<head>
	<!-- call header assets method -->
	@( header_assets() )
</head>

<body>
	<!--[if lte IE 9]>
	<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
	<![endif]-->

	<header class="header">
		<div class="header-inner">
			<a class="header-logo" href="@( base_url() )" aria-label="NSY PHP Framework">
				<img src="@( img_url('logo.png') )" alt="NSY PHP Framework">
			</a>
			<div class="header-copy">
				<h1>@( $welcome_text )</h1>
				<h3><code>Simple. Layered. Harmony in MVC and HMVC.</code></h3>
			</div>
		</div>
		<hr>
	</header>