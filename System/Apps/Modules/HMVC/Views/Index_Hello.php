<style>
.hmvc-hero{max-width:720px;margin:0 auto;padding:36px 16px;text-align:center}
.hmvc-badge{display:inline-block;background:#fff;border:1px solid #e2e8f0;padding:4px 10px;border-radius:999px;font-size:12px;color:#0f172a}
.hmvc-title{font-size:28px;font-weight:800;margin:12px 0 4px}
.hmvc-subtitle{color:#64748b;margin:0 0 14px}
.hmvc-actions{display:flex;gap:10px;justify-content:center;flex-wrap:wrap;margin:14px 0}
.hmvc-btn{padding:10px 16px;border-radius:10px;text-decoration:none;font-weight:600;border:1px solid #e2e8f0}
.hmvc-btn.primary{background:#0f172a;color:#fff;border-color:#0f172a}
.hmvc-btn.ghost{background:#fff;color:#0f172a}
.hmvc-note{max-width:720px;margin:0 auto 24px;padding:12px 14px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;color:#475569;font-size:13px;line-height:1.5}
</style>
<div class="hmvc-hero">
	<div class="hmvc-badge">HMVC Module • Hierarchical MVC</div>
	<h2 class="hmvc-title">Hello, @( $hmvc_text )!</h2>
	<p class="hmvc-subtitle">This page is rendered from <code>System/Apps/Modules/HMVC/Views/Index_Hello.php</code> via <code>Load::view('HMVC', ...)</code></p>
	<div class="hmvc-actions">
		<a class="hmvc-btn primary" href="@( base_url() )">← Back to MVC</a>
		<a class="hmvc-btn ghost" target="_blank" href="https://github.com/kazuyamarino/nsy/blob/master/docs/README_LOAD_AND_ASSETMANAGER.md">Docs: Load & HMVC</a>
	</div>
</div>
<div class="hmvc-note">
	<strong>How HMVC works:</strong> Controller <code>System/Apps/Modules/HMVC/Controllers/Controller_Hello.php</code> → <code>Load::view('HMVC','Index_Hello',$arr)</code> → resolves to <code>get_hmvc_view_dir() . 'HMVC/Views/Index_Hello.php'</code>. Try creating <code>System/Apps/Modules/Blog/Controllers/...</code> and <code>Route::get('/blog', [BlogController::class,'index'])</code>.
</div>
