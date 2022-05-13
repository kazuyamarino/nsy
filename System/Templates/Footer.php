<!-- Footer are 'sticky' -->
<footer class="footer">
    <hr>
    <div class="fcontent">
		<code>@( $date->isoFormat('dddd, D MMMM Y') )</code><br>
        <p><a target="_blank" href="@raw( 'mailto:vikry.yuansah@gmail.com' )">Vikry Yuansah</a>&nbsp;|&nbsp;
        <a href="@( base_url() )">NSY</a> @( get_version() ) - <a href="https://github.com/kazuyamarino/nsy-docs#codename" target="_blank">@( get_codename() )</a>&nbsp;|&nbsp; 2018 - @( $date->isoFormat('Y') )</p>
    </div>
</footer>
<!-- call footer assets method -->
@( footer_assets() )
</body>
</html>
