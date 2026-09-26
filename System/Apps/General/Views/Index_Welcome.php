<div class="nsy-hero">
	<div class="nsy-badge">NSY Framework • MVC & HMVC • v@( get_version() ) @( get_codename() )</div>
	<h1 class="nsy-title">Hello, @( $mvc_text )!</h1>
	<p class="nsy-subtitle">A simple, powerful PHP framework — PSR-4, Razr, AssetManager & Query Builder in one line. Every guide below opens right here, no GitHub needed.</p>
	<div class="nsy-actions">
		<a class="nsy-btn primary" href="@( base_url('hmvc') )">Go to HMVC →</a>
		<a class="nsy-btn ghost" target="_blank" href="https://github.com/kazuyamarino/nsy">View on GitHub</a>
		<a class="nsy-btn ghost" target="_blank" href="https://github.com/kazuyamarino/nsy/releases">Releases</a>
	</div>
</div>
<div class="nsy-dochead">
	<h2>Documentation <span class="nsy-hint">@( count($docs) ) guides — click a card to read in-app</span></h2>
	<input id="nsyDocSearch" class="nsy-search" type="search" placeholder="Search docs… (router, qb, migration, helpers)" autocomplete="off">
</div>
@foreach($categories as $category => $items)
<section class="nsy-cat">
	<h3>@( $category )</h3>
	<div class="nsy-grid">
		@foreach($items as $doc)
		<article class="nsy-card" data-search="@( strtolower($doc['title'] . ' ' . $doc['category'] . ' ' . $doc['api'] . ' ' . $doc['summary'] . ' ' . $doc['file']) )">
			<h4><a href="@( base_url('docs/' . $doc['slug']) )"><span class="nsy-ico">@raw( $doc['icon_svg'] )</span>@( $doc['title'] )</a></h4>
			<code>@( $doc['api'] )</code>
			<p>@( $doc['summary'] )</p>
			<div class="nsy-card-foot">
				<span>@( $doc['lines'] ) lines</span>
				<span>
					<a class="more" href="@( base_url('docs/' . $doc['slug']) )">Read →</a>
					<a class="ext" target="_blank" rel="noopener" href="https://github.com/kazuyamarino/nsy/blob/master/docs/@( $doc['file'] )" title="View @( $doc['file'] ) on GitHub">↗</a>
				</span>
			</div>
		</article>
		@endforeach
	</div>
</section>
@endforeach
<div id="nsyDocEmpty" class="nsy-empty">No documentation matched your search.</div>
