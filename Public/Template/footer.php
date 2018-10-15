<?php
defined('ROOT') OR exit('No direct script access allowed');

use System\Libraries\AssetManager\Assets;

// instantiate Assets class
$Assets = new Assets();

// call footer assets method
$Assets->pull_footer_assets();

// call datatables init method
$Assets->datatables_init();
?>

<!-- Footer are 'sticky' -->
<footer class="footer">
	<div style="margin-bottom:1rem;" class="grid-x grid-padding-x">
		<hr />
		<div class="large-12 cell">
			<div class="grid-x grid-padding-x">
				<div class="large-5 medium-5 cell">
					<p><i class="fab fa-html5 fa-3x"></i>&nbsp;<i class="fab fa-css3 fa-3x"></i></p>
					<p>This is a Sticky Footer.</p>
					<p><a target="_blank" href="mailto:admin@kazuyamarino.com">Vikry Yuansah </a><i class="fas fa-forward"></i> <a href="<?php echo BASE_URL ?>">NSY 2015 - <?php echo date("Y"); ?></a></p>
					<hr class="show-for-small-only"/>
				</div>
				<div class="large-3 medium-2 cell">
					<p><strong><a target="_blank" href="http://fortawesome.github.io/Font-Awesome/">Font Awesome</a></strong> is The iconic font and CSS toolkit</p>
					<div class="list-group">
						<i class="fab fa-twitter fa-2x fa-fw"></i><a class="list-group-item" target="_blank" href="https://twitter.com/abang_marino">&nbsp; Twitter</a></br>
						<i class="fab fa-facebook fa-2x fa-fw"></i><a class="list-group-item" target="_blank" href="https://www.facebook.com/kazuya.marino">&nbsp; Facebook</a></br>
						<i class="fab fa-linkedin fa-2x fa-fw"></i><a class="list-group-item" target="_blank" href="https://id.linkedin.com/in/vikry-yuansyah-1265a4a7">&nbsp; Linkedin</a></br>
						<i class="fas fa-envelope fa-2x fa-fw"></i><a class="list-group-item" href="mailto:admin@kazuyamarino.com">&nbsp; Email</a>
					</div>
					<hr class="show-for-small-only"/>
				</div>
				<div class="large-4 medium-3 cell">
					<p>Newsletter:</p>
					<p><input type="text" value="Email Address"><input type="submit" class="button radius right"></p>
				</div>
			</div>
		</div>
	</div>
</footer>

</body>
</html>
