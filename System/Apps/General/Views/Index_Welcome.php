<style>
.nsy-hero{max-width:960px;margin:0 auto;padding:32px 16px;text-align:center}
.nsy-badge{display:inline-block;background:#0f172a;color:#fff;padding:4px 12px;border-radius:999px;font-size:12px;letter-spacing:.08em;margin-bottom:12px}
.nsy-title{font-size:32px;font-weight:800;margin:8px 0 4px}
.nsy-subtitle{color:#64748b;margin:0 0 16px}
.nsy-actions{display:flex;gap:12px;justify-content:center;flex-wrap:wrap;margin:16px 0 24px}
.nsy-btn{padding:10px 18px;border-radius:10px;text-decoration:none;font-weight:600;border:1px solid #e2e8f0;display:inline-block}
.nsy-btn.primary{background:#0f172a;color:#fff;border-color:#0f172a}
.nsy-btn.ghost{background:#fff;color:#0f172a}
.nsy-grid{max-width:960px;margin:0 auto;display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:14px;padding:0 16px 24px;text-align:left}
.nsy-card{border:1px solid #e2e8f0;border-radius:14px;padding:14px;background:#fff;transition:box-shadow .15s,transform .15s}
.nsy-card:hover{box-shadow:0 8px 24px rgba(15,23,42,.08);transform:translateY(-2px)}
.nsy-card h4{margin:0 0 4px;font-size:14px}
.nsy-card h4 a{color:#0f172a;text-decoration:none}
.nsy-card code{font-size:11px;color:#475569;background:#f8fafc;padding:2px 6px;border-radius:6px}
.nsy-card p{margin:6px 0 0;font-size:12px;color:#64748b;line-height:1.4}
</style>
<div class="nsy-hero">
	<div class="nsy-badge">NSY Framework • MVC & HMVC • v@( get_version() ) @( get_codename() )</div>
	<h1 class="nsy-title">Hello, @( $mvc_text )!</h1>
	<p class="nsy-subtitle">A simple, powerful PHP framework — PSR-4, Razr, AssetManager & Query Builder in one line.</p>
	<div class="nsy-actions">
		<a class="nsy-btn primary" href="@( base_url('hmvc') )">Go to HMVC →</a>
		<a class="nsy-btn ghost" target="_blank" href="https://github.com/kazuyamarino/nsy">View on GitHub</a>
		<a class="nsy-btn ghost" target="_blank" href="https://github.com/kazuyamarino/nsy/releases">Releases</a>
	</div>
</div>
<div class="nsy-grid">
	<div class="nsy-card"><h4><a target="_blank" href="https://github.com/kazuyamarino/nsy/blob/master/docs/README_LIBRARIES.md">📚 Libraries</a></h4><code>File · LanguageCode · qb()</code><p>File, LanguageCode, Query Builder, Validate — fluent & minimal.</p></div>
	<div class="nsy-card"><h4><a target="_blank" href="https://github.com/kazuyamarino/nsy/blob/master/docs/README_LOAD_AND_ASSETMANAGER.md">🧩 Load & Asset</a></h4><code>Load::view() · Add::link()</code><p>Razr views, templates, models & <code>?v=filemtime</code> assets.</p></div>
	<div class="nsy-card"><h4><a target="_blank" href="https://github.com/kazuyamarino/nsy/blob/master/docs/README_HELPERS_GLOBAL.md">🔧 Global Helpers</a></h4><code>base_url() · is_filled()</code><p>URI, asset & config helpers — env-aware.</p></div>
	<div class="nsy-card"><h4><a target="_blank" href="https://github.com/kazuyamarino/nsy/blob/master/docs/README_NSY_ROUTER.md">🛣️ Router</a></h4><code>Route::get/post/group</code><p>14-section example in <code>RouteExample.php</code>.</p></div>
	<div class="nsy-card"><h4><a target="_blank" href="https://github.com/kazuyamarino/nsy/blob/master/docs/README_MIGRATION.md">🗄️ Migration</a></h4><code>Mig::create_table()</code><p>DRY <code>quoteIdent/execDDL</code>, chainable.</p></div>
	<div class="nsy-card"><h4><a target="_blank" href="https://github.com/kazuyamarino/nsy/blob/master/docs/README_MODEL.md">🗃️ Model & DB</a></h4><code>DB::query() · NSY_DB::connect()</code><p>Unified single-source DB.</p></div>
	<div class="nsy-card"><h4><a target="_blank" href="https://github.com/kazuyamarino/nsy/blob/master/docs/README_QUERY_BUILDER.md">⚡ Query Builder</a></h4><code>qb()->whereIn()->paginate()</code><p>1 line per query — powerful.</p></div>
	<div class="nsy-card"><h4><a target="_blank" href="https://github.com/kazuyamarino/nsy/blob/master/docs/OVERVIEW.md">📖 Overview</a></h4><code>Composer · CLI · Config</code><p>Start here: install, env, MVC/HMVC.</p></div>
</div>
