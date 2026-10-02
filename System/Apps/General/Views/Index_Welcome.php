<div class="nsy-page">
	<section class="nsy-hero">
		<span class="nsy-badge">NSY PHP Framework • v@( get_version() ) @( get_codename() ) is OUT! Try Now!</span>
		<div class="nsy-hero-searchwrap">
			<label for="nsyDocSearch" class="nsy-hero-caption">Search</label>
			<input id="nsyDocSearch" class="nsy-hero-search" type="search" placeholder="Search docs… (router, qb, migration, helpers)" autocomplete="off" aria-label="Search documentation">
		</div>
	</section>

	<div class="nsy-dochead">
		<h2>Documentation <span class="nsy-hint">@( count($docs) ) guides</span></h2>
		<div class="nsy-actions">
			<a class="nsy-btn primary" href="@( base_url('hmvc') )">Go to HMVC →</a>
			<a class="nsy-btn ghost" target="_blank" rel="noopener" href="@( get_repo_url() )">View on GitHub</a>
			<a class="nsy-btn ghost" id="nsyReleaseBtn" target="_blank" rel="noopener" href="@( get_repo_url() )/releases">Releases</a>
		</div>
	</div>

	@foreach($categories as $category => $items)
	<section class="nsy-cat" data-cat="@( strtolower(str_replace(' ', '-', $category)) )">
		<div class="nsy-cat-head">
			<h3>@( $category )</h3>
			<span class="nsy-cat-count">@( count($items) )</span>
		</div>
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
						<a class="ext" target="_blank" rel="noopener" href="@( get_repo_url() )/blob/master/docs/@( $doc['file'] )" title="View @( $doc['file'] ) on GitHub">↗</a>
					</span>
				</div>
			</article>
			@endforeach
		</div>
	</section>
	@endforeach

	<div id="nsyDocEmpty" class="nsy-empty">No documentation matched your search.</div>
</div>