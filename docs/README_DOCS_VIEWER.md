# NSY In-App Docs Viewer — User Guide

The in-app docs viewer turns the Markdown files in `docs/` into readable HTML
**inside your own application layout** — no GitHub, no Markdown editor, no extra
package. A file `docs/README_NSY_ROUTER.md` registered under the slug `router` is served
at:

```text
https://your-site.example/{app_dir}/docs/router
```

It ships with the framework, so the same files that document NSY on GitHub are also
browsable while you develop.

## Table of Contents

1. [How It Works](#how-it-works)
2. [Files & URLs](#files--urls)
3. [Anatomy of `Docs::MANIFEST`](#anatomy-of-docsmanifest)
4. [Adding a Page](#adding-a-page)
5. [Icons & Categories](#icons--categories)
6. [Markdown Support](#markdown-support)
7. [Rules & Gotchas](#rules--gotchas)
8. [API Reference](#api-reference)
9. [Quick Reference](#quick-reference)

---

## How It Works

```text
GET /docs/(:slug)
   │  System/Routes/General.php
   ▼
Controller_Docs::show($slug)
   │  Docs::render($slug) ─► reads docs/<file>.md
   │  Docs::all() / categories() / neighbors() ─► sidebar
   │  Markdown::toHtmlWithToc() ─► HTML + "On this page" TOC
   ▼
Load::template('Header') → Load::view('Index_Docs') → Load::template('Footer')
```

| Step | File |
|---|---|
| Route | `System/Routes/General.php` (`Route::get('/docs/(:slug)', [...])`) |
| Controller | `System/Apps/General/Controllers/Controller_Docs.php` |
| View | `System/Apps/General/Views/Index_Docs.php` |
| Layout | `System/Apps/Templates/Header.php`, `Footer.php` |
| Registry | `System/Libraries/Docs.php` |
| Renderer | `System/Libraries/Markdown.php` |
| Content | `docs/*.md` |
| Styling | `public/assets/css/main.css` (`.nsy-docs*`) |

An unknown or unregistered slug returns **HTTP 404** (`Docs::render()` returns
`null`, and the controller sets the status before rendering the "Not found" state).

---

## Files & URLs

| You write | The viewer serves |
|---|---|
| `docs/README_ALIASES.md` registered as `aliases` | `base_url('docs/aliases')` |
| `docs/OVERVIEW.md` registered as `overview` | `base_url('docs/overview')` |

The slug lives only in the manifest — the filename only needs to match the entry's
`file` value.

---

## Anatomy of `Docs::MANIFEST`

`System/Libraries/Docs.php` holds one array that drives everything: sidebar order,
category, icon, title, the small `api` hint and the summary text.

```php
'query-builder' => [
    'file'     => 'README_QUERY_BUILDER.md', // file in docs/
    'category' => 'Core',                    // Getting Started | Core | Reference
    'icon'     => 'bolt',                    // see Docs::icon()
    'title'    => 'Query Builder',           // sidebar + breadcrumb label
    'api'      => 'qb()->whereIn()->paginate()',
    'summary'  => 'Fluent SQL builder: where, joins, grouping, pagination and raw queries.',
],
```

Categories render in the order defined by `Docs::CATEGORY_ORDER`
(`Getting Started`, `Core`, `Reference`); any other category you invent appears
after those.

---

## Adding a Page

**1.** Create the Markdown file in `docs/` — start with one `# Title` and use
numbered `##` sections (only `##` headings appear in "On this page"):

```markdown
# My Feature — User Guide

One-line intro.

## Table of Contents

1. [Getting Started](#getting-started)
2. [Quick Reference](#quick-reference)

---

## Getting Started

...
```

**2.** Register it in `System/Libraries/Docs.php`:

```php
'my-feature' => [
    'file'     => 'README_MYFEATURE.md',
    'category' => 'Core',
    'icon'     => 'bolt',
    'title'    => 'My Feature',
    'api'      => 'MyClass::run()',
    'summary'  => 'Short description shown in the sidebar and home.',
],
```

**3.** It is now available at `base_url('docs/my-feature')` and appears in the
sidebar immediately — no cache to clear.

---

## Icons & Categories

| Icon name | Intended for |
|---|---|
| `book` | overview / getting started |
| `layers` | loaders and asset managers |
| `route` | routing |
| `database` | database / models |
| `migrate` | migrations |
| `bolt` | query builder / speed |
| `library` | bundled libraries |
| `log` | logging |
| `link` | aliases / links |
| `braces` | JSON |
| `cookie`, `lock`, `key`, `globe`, `check`, `shield` | cookie, encryption, session, curl, validation, security |
| `gear`, `sliders`, `server`, `package`, `eye` | helpers, config, hosting, dependencies, viewer |

`Docs::icon()` returns the matching inline SVG; an unknown name silently falls back
to `book`, so a typo never breaks the page.

---

## Markdown Support

The renderer is intentionally small but covers what the docs need:

- ATX (`#`) and Setext (`===` / `---`) headings
- Fenced code blocks (```` ```php ````) with a `language-*` class
- GFM tables, nested ordered/unordered lists, blockquotes, horizontal rules
- emphasis / strong / strikethrough, inline code, images, links, autolinks

Two important behaviours:

- **Raw HTML is escaped on purpose.** You cannot embed `<div>`/`<script>` in a doc;
  keep to Markdown so the viewer can never inject markup.
- **Only `##` headings are numbered** for the "On this page" list, and the numbering
  runs continuously across the whole document.

---

## Rules & Gotchas

- A manifest entry whose `file` is missing is shown as absent and resolves to 404 —
  add the file or remove the entry.
- Slugs are normalized to lowercase letters, digits and hyphens
  (`[^a-z0-9-]+` is stripped), so keep them simple and unique.
- `docs/*.md` are read **at runtime**, which is why they must ship with the release
  (they are not `export-ignore`d in `.gitattributes`) — that way the guides are
  readable locally while developing. Uploading `docs/` to your own server is
  optional and only needed if you want to serve the viewer there; see
  [Deploy to Shared Hosting](README_DEPLOY_HOSTING.md).
- Cross-document links written as `[JSON](README_JSON.md)` are kept for GitHub; the
  in-app viewer does **not** rewrite them, so in-app navigation relies on the
  sidebar and prev/next buttons. To link inside the app, use an absolute URL that
  includes `app_dir`, e.g. `[JSON](/nsy/docs/json)`.
- The viewer reuses the app's Header/Footer templates, so it inherits your theme
  and `get_version()` / `get_codename()` badges.

---

## API Reference

### `System\Libraries\Docs`

| Method | Purpose |
|---|---|
| `all(): array` | Every manifest entry enriched with `slug`, `path`, `exists`, `lines`, `size`, `icon_svg` |
| `categories(): array` | Entries grouped by category, in `CATEGORY_ORDER` |
| `find(string $slug): ?array` | One entry, or `null` when unknown/missing |
| `render(string $slug): ?array` | `['doc', 'html', 'toc']`, or `null` |
| `neighbors(string $slug): array` | `['prev' => …, 'next' => …]` |
| `docsDir(): string` | Absolute path to the `docs/` folder |
| `icon(string $name): string` | Inline SVG for an icon name (falls back to `book`) |

### `System\Libraries\Markdown`

| Method | Purpose |
|---|---|
| `toHtml(string $markdown): string` | Markdown → HTML |
| `toHtmlWithToc(string $markdown): array` | `['html' => …, 'toc' => …]` |
| `render(string $markdown): string` | Instance form of `toHtml()` |

---

## Quick Reference

| I want to… | Do |
|---|---|
| Read a page | `GET /{app_dir}/docs/{slug}` |
| Add a page | Add `docs/README_X.md` + a manifest entry in `Docs.php` |
| Change sidebar order | Reorder the entries in `Docs::MANIFEST` |
| Change a category | Edit the entry's `category` (or `CATEGORY_ORDER`) |
| Change an icon | Set the entry's `icon` (see `Docs::icon()`) |
| Debug a 404 | Check the entry exists and `Docs::find($slug)` is not `null` |

Related: `System/Libraries/Docs.php`, `System/Libraries/Markdown.php`,
`System/Apps/General/Controllers/Controller_Docs.php`,
`System/Apps/General/Views/Index_Docs.php`,
`System/Routes/General.php`.
