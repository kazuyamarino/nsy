<!doctype html>
<html class="no-js" lang="@( get_lang_code() )" prefix="@( get_og_prefix() )">

<head>
	<!-- Swap the no-js class before first paint, so JS-only affordances (e.g.
	     the back-to-top button) never flash in while the page loads. -->
	<script>(function (h) { h.className = h.className.replace(/\bno-js\b/, 'js'); }(document.documentElement));</script>
	<!-- Resolve the colour theme before first paint (stored choice, else the OS
	     setting) so a dark-mode visitor never sees a light flash. -->
	<script>(function (d, k) { try { var s = localStorage.getItem(k); var dark = s ? s === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches; if (dark) { d.setAttribute('data-theme', 'dark'); } } catch (e) { } }(document.documentElement, 'nsy-theme'));</script>
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
				<h1>@( $welcomeText )</h1>
				<h3><code>Simple. Layered. Harmony in MVC and HMVC.</code></h3>
			</div>
		</div>
		<button type="button" class="nsy-theme-toggle" id="nsyThemeToggle" aria-label="Toggle dark mode" aria-pressed="false" title="Toggle dark / light theme">
			<svg class="nsy-theme-ico moon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg>
			<svg class="nsy-theme-ico sun" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.2 4.2l1.4 1.4M18.4 18.4l1.4 1.4M2 12h2M20 12h2M4.2 19.8l1.4-1.4M18.4 5.6l1.4-1.4"/></svg>
		</button>
		<hr>
	</header>