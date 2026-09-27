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
<!-- call footer assets method -->
@( footer_assets() )
</body>

</html>
