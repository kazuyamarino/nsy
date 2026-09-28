<div class="nsy-page">
	<div class="nsy-docs">
		<aside class="nsy-docs-side">
			<a class="nsy-docs-home" href="@( base_url() )">← All documentation</a>
			<nav class="nsy-docs-nav">
				@foreach($categories as $category => $items)
				<div class="nsy-docs-group">
					<span class="nsy-docs-group-title">@( $category )</span>
					@foreach($items as $item)
					<a class="nsy-docs-link@( ($doc && $doc['slug'] === $item['slug']) ? ' active' : '' )" href="@( base_url('docs/' . $item['slug']) )"><span class="nsy-docs-ico">@raw( $item['icon_svg'] )</span>@( $item['title'] )</a>
					@endforeach
				</div>
				@endforeach
			</nav>
			@if(!empty($toc))
			<div class="nsy-docs-toc">
				<span class="nsy-docs-group-title">On this page</span>
				@foreach($toc as $heading)
				<a class="nsy-docs-toc-link" href="#@( $heading['id'] )" title="@( $heading['text'] )"><span class="nsy-toc-no">@( $heading['num'] ).</span>@( $heading['text'] )</a>
				@endforeach
			</div>
			@endif
		</aside>

		<main class="nsy-docs-main">
			<header class="nsy-docs-head">
				<span class="nsy-badge">Documentation • v@( get_version() ) @( get_codename() )</span>
				<nav class="nsy-docs-crumbs"><a href="@( base_url() )">Docs</a> <span>/</span> <span>@( $doc ? $doc['title'] : 'Not found' )</span></nav>
				@if($doc)
				<div class="nsy-docs-meta">
					<code>@( $doc['api'] )</code>
					<span>@( $doc['category'] )</span>
					<span>@( $doc['lines'] ) lines</span>
					<a class="ext" target="_blank" rel="noopener" href="@( get_repo_url() )/blob/master/docs/@( $doc['file'] )" title="View @( $doc['file'] ) on GitHub">↗</a>
				</div>
				@endif
			</header>

			@if($doc)
			<article class="md">
				@raw( $content )
			</article>
			<nav class="nsy-docs-pager">
				@if($neighbors['prev'])
				<a class="nsy-pager prev" href="@( base_url('docs/' . $neighbors['prev']['slug']) )"><span>← Previous</span><strong>@( $neighbors['prev']['title'] )</strong></a>
				@endif
				@if($neighbors['next'])
				<a class="nsy-pager next" href="@( base_url('docs/' . $neighbors['next']['slug']) )"><span>Next →</span><strong>@( $neighbors['next']['title'] )</strong></a>
				@endif
			</nav>
			@else
			<div class="nsy-docs-missing">
				<h2>Documentation not found</h2>
				<p>The page you were looking for doesn't exist. Pick a document from the sidebar instead.</p>
				<a class="nsy-btn primary" href="@( base_url() )">← Back to documentation</a>
			</div>
			@endif
		</main>
	</div>
</div>
