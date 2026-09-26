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
			<a class="nsy-docs-toc-link nsy-lvl-@( $heading['level'] )" href="#@( $heading['id'] )">@( $heading['text'] )</a>
			@endforeach
		</div>
		@endif
	</aside>
	<main class="nsy-docs-main">
		@if($doc)
		<nav class="nsy-docs-crumbs"><a href="@( base_url() )">Docs</a> <span>/</span> <span>@( $doc['title'] )</span></nav>
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
			<a class="nsy-docs-btn" href="@( base_url() )">← Back to documentation</a>
		</div>
		@endif
	</main>
</div>
