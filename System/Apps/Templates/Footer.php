<!--
	The "sticky" footer is a CSS concern, not a markup one: `footer` is pinned to
	the bottom of the flex column on <body> via `flex-shrink: 0`, while the
	content wrapper absorbs the leftover height. See public/assets/css/main.css.
-->
<footer class="footer">
	<hr>
	<div class="fcontent">
		<p class="footer-date">@( get_today() )</p>
		<p class="footer-links">
			<a href="mailto:@( get_site_email() )">@( get_author() )</a>
			<a href="@( base_url() )">NSY</a>
			<span>v@( get_version() )</span>
			<a href="@( get_repo_url() )/#codename" target="_blank" rel="noopener noreferrer">@( get_codename() )</a>
			<span>@( get_since() )&ndash;@( get_year() )</span>
		</p>
	</div>
</footer>
<!-- site-wide back to top (plain "#top" anchor: works without JS; docs.js only toggles visibility) -->
<a class="nsy-top" href="#top" aria-label="Back to top" title="Back to top"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M12 19V5"/><path d="m5 12 7-7 7 7"/></svg></a>
<!-- call footer assets method -->
@( footer_assets() )
</body>

</html>
