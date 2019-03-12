<?php
defined('ROOT') OR exit('No direct script access allowed');
?>
<!-- Footer are 'sticky' -->
<footer class="footer">
	<hr>
	<div class="fcontent">
		<a target="_blank" href="mailto:admin@kazuyamarino.com">Vikry Yuansah</a>
		<span>-</span>
		<span>NSY 2018 - <?php echo date("Y"); ?></span><br>
		<p><?php echo $date->isoFormat('dddd, D MMMM Y'); ?></p>
	</div>
</footer>
<?php
// call footer assets method
pull::footer_assets();
?>
</body>
</html>
