<div class="hmvc-hero">
	<div class="hmvc-badge">HMVC Module • Hierarchical MVC</div>
	<h2 class="hmvc-title">Hello, @( $hmvc_text )!</h2>
	<p class="hmvc-subtitle">This page is rendered from <code>System/Apps/Modules/HMVC/Views/Index_Hello.php</code> via <code>Load::view('HMVC', ...)</code></p>
	<div class="hmvc-actions">
		<a class="hmvc-btn primary" href="@( base_url() )">← Back to MVC</a>
		<a class="hmvc-btn ghost" href="@( base_url() )">Browse all docs</a>
	</div>
</div>
<div class="hmvc-note">
	<strong>How HMVC works:</strong> Controller <code>System/Apps/Modules/HMVC/Controllers/Controller_Hello.php</code> → <code>Load::view('HMVC','Index_Hello',$arr)</code> → resolves to <code>get_hmvc_view_dir() . 'HMVC/Views/Index_Hello.php'</code>. Try creating <code>System/Apps/Modules/Blog/Controllers/...</code> and <code>Route::get('/blog', [BlogController::class,'index'])</code>.
</div>
@if(!empty($docs))
<div class="hmvc-docs">
	<h3>Related documentation</h3>
	<div class="hmvc-grid">
		@foreach($docs as $doc)
		<article class="hmvc-card">
			<h4><a href="@( base_url('docs/' . $doc['slug']) )">@( $doc['emoji'] ) @( $doc['title'] )</a></h4>
			<p>@( $doc['summary'] )</p>
			<div class="hmvc-card-foot"><a href="@( base_url('docs/' . $doc['slug']) )">Read guide →</a></div>
		</article>
		@endforeach
	</div>
	<a class="hmvc-all" href="@( base_url() )">Browse the full documentation index →</a>
</div>
@endif
