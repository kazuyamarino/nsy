<?php
defined('ROOT') OR exit('No direct script access allowed');
?>
<!-- Footer are 'sticky' -->
<footer class="footer">
	<hr>
	<div class="fcontent">
		<a target="_blank" href="mailto:admin@kazuyamarino.com">Vikry Yuansah</a>
		<span>-</span>
		<a href="<?php echo BASE_URL; ?>">NSY 2015 - <?php echo date("Y"); ?></a>
	</div>
</footer>
<?php
// call footer assets method
get::footer_assets();
?>
</body>
</html>
