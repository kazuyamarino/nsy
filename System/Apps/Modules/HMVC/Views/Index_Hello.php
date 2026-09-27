<div class="nsy-page">
	<section class="nsy-hero">
		<span class="nsy-badge">HMVC Module • Hierarchical MVC • v@( get_version() ) @( get_codename() )</span>
		<h1 class="nsy-hero-title">Hello, @( $hmvc_text )!</h1>
		<p class="nsy-hero-lead">This page is rendered from <code>System/Apps/Modules/HMVC/Views/Index_Hello.php</code> via&nbsp;<code>Load::view('HMVC',...)</code></p>
		<div class="nsy-actions nsy-hero-actions">
			<a class="nsy-btn primary" href="@( base_url() )">← Back to MVC</a>
			<a class="nsy-btn ghost" href="@( base_url() )">Browse all docs →</a>
		</div>
	</section>

	<div class="nsy-note">
		<strong>How HMVC works:</strong> Controller <code>System/Apps/Modules/HMVC/Controllers/Controller_Hello.php</code> → <code>Load::view('HMVC','Index_Hello',$arr)</code> → resolves to <code>get_hmvc_view_dir() . 'HMVC/Views/Index_Hello.php'</code>. Try creating <code>System/Apps/Modules/Blog/Controllers/...</code> and <code>Route::get('/blog', [BlogController::class,'index'])</code>.
	</div>
</div>
