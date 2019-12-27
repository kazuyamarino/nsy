<?php
defined('ROOT') OR exit('No direct script access allowed');
?>
<!-- Footer are 'sticky' -->
<footer class="footer">
	<hr>
	<div class="fcontent">
		@raw( mailto('admin@kazuyamarino.com', 'Vikry Yuansah', null) )
		<span>-</span>
		<span><a href="@( base_url() )">NSY</a> @(VERSION) | <a href="https://github.com/kazuyamarino/nsy#codename" target="_blank">@(CODENAME)</a>, 2018 - @( $date->isoFormat('Y') ).</span><br>
		<p>@( $date->isoFormat('dddd, D MMMM Y') ).</p>
	</div>
</footer>
<!-- call footer assets method -->
@( pull::footer_assets() )
</body>
</html>
