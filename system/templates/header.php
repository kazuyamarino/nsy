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

	<header style="text-align:center" class="header">
		<a href="@( base_url() )">
			<img src="@( img_url('logo.png') )" width="100" />
		</a>
		<h1>@( $welcome )</h1>
		<h3><code>NSY is a simple PHP Framework that works well on MVC or HMVC mode.</code></h3>
		<hr>
	</header>
