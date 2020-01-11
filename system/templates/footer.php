<?php
defined('ROOT') OR exit('No direct script access allowed');
?>
<!-- Footer are 'sticky' -->
<footer class="footer">
    <hr>
    <div class="fcontent">
		<code>@( $date->isoFormat('dddd, D MMMM Y') )</code><br>
        <p>@raw( mailto('admin@kazuyamarino.com', 'Vikry Yuansah', null) )&nbsp;|&nbsp;
        <a href="@( base_url() )">NSY</a> @( get_version() ) - <a href="https://github.com/kazuyamarino/nsy#codename" target="_blank">@( get_codename() )</a>&nbsp;|&nbsp; 2018 - @( $date->isoFormat('Y') )</p>
    </div>
</footer>
<!-- call footer assets method -->
@( Pull::footer_assets() )
</body>
</html>
