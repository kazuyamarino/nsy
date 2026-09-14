# AGENTS.md

NSY — a custom lightweight PHP framework supporting MVC and HMVC modes (Codename: Talindo). Everything core lives under `System/`.

## Commands & CLI

`composer install`                 # runs INSTALL.sh (appends alias to ~/.bashrc)
`composer dump-autoload -o`        # vendor dir is System/Vendor/, not ./vendor
`nsy --setup`                      # initial setup CLI command (after sourcing shell/alias)
`bash ./.cli/nsy.core.sh --help`   # NSY CLI manual execution

CLI Generators & Runner: 
- `make:controller`, `make:model`, `make:module`, `make:migration`, `make:route`
- Migrations: `run:migrate`

## Tests & Quality Notice

- `phpunit.xml` points at `./nsy/` and Travis runs `System/Test/SimpleTest.php` — **verify before relying on it** as it may be stale or require environment adjustments.

## Architecture & Core Layout

- **Entry Point:** `public/index.php` → loads root `env.php` (config + DB credentials, not `.env`) → boots `NSY_System` → dispatches `NSY_RouterOptimized`.
- **Environment:** `env.php` is committed with real-looking template credentials. Use `docs/env.example/env.example.php` as reference.
- **Autoloading:** PSR-4 mapping `System\` → `System/`; global helpers auto-loaded via composer `files`: `System/Helpers/CodeIgniterHelpers.php`.
- **App Structure:** 
  - MVC: `System/Apps/General/{Controllers,Models,Views}`
  - HMVC: `System/Apps/Modules/HMVC/<Module>/{Controllers,Models,Views}`
- **Routing:** Plain PHP files inside `System/Routes/` using a `Route::route(...)` facade (`RouteExample.php`). Router init with security/perf options sits at the top of `System/Routes/General.php`.
- **Core & Database:** Core classes in `System/Core/` (DB, Load, Router, Migration). Migrations located in `System/Migrations/`.

## Special Features

- **Middleware:** Supports request filtering before hitting controllers.
- **Razr Template Engine:** Supports `.razr` syntax (inspired by ASP.NET Razor) alongside standard PHP code inside View components.

## Conventions

- Indent with **tabs** (`.editorconfig`).
- **Do not restructure** `public/index.php` or change bootstrap logic, as it breaks the underlying framework wiring.
- Never modify files inside `System/Vendor/`.

<!-- gitnexus:start -->
# GitNexus — Code Intelligence

This project is indexed by GitNexus as **nsy** (25790 symbols, 54307 relationships, 636 execution flows).

> Index stale? Run `node .gitnexus/run.cjs analyze --index-only` from the project root — it auto-selects an available runner. No `.gitnexus/run.cjs` yet? Bootstrap with `npx`, `bunx`, or `pnpm dlx` — e.g. `bunx gitnexus@latest analyze` (npm 11 npx crash; #1939).

## Always Do

- **MUST run impact before editing.** Use `impact({target: "symbolName", direction: "upstream"})` or `node .gitnexus/run.cjs impact "symbolName" --direction upstream --repo .`; report callers, processes, and risk. Never substitute grep for graph analysis.
- **MUST analyze graph changes before committing.** Use `detect_changes({scope: "all"})` (MCP) or `node .gitnexus/run.cjs detect-changes --scope all --repo .` (CLI fallback). `partial: true` or `truncated: true` is not a clean check — a zero means unseen, not unaffected; re-run it. For regression review: `detect_changes({scope: "compare", base_ref: "master"})` or `node .gitnexus/run.cjs detect-changes --scope compare --base-ref "master" --repo .`.
- MUST warn on HIGH/CRITICAL `risk` pre-edit; never use `riskSharedAxes` to waive a HIGH/CRITICAL `risk` warning. Compare File/symbol: MCP File omits axes; Graph-RAG expands File.
- **MUST treat `risk: UNKNOWN` as unresolved, not as low.** An empty caller set is not evidence the symbol is unused — it can also mean the callers are not resolvable by the index (plain-object property access, dynamic dispatch, cross-language calls). `impact` pairs `UNKNOWN` with a `riskNote` saying so. Confirm with a text search before treating the symbol as safe to change or delete; do not proceed on the strength of a zero.
- **MUST use `query({search_query: "concept"})` for concepts/flows, `context({name: "symbolName"})` for a named symbol, or `impact` for blast radius, on read-only callers, dependencies, imports, or execution flow.** Graph first; text search only for empty/`UNKNOWN`/literals.
- For security review, `explain({target: "fileOrSymbol"})` lists taint findings (source→sink flows; needs `analyze --pdg`).

## Never Do

- NEVER edit a function, class, or method before MCP/CLI impact analysis.
- NEVER ignore HIGH or CRITICAL risk warnings from impact analysis, and never read `UNKNOWN` as an all-clear — it means the walk could not answer, which is the one verdict that requires confirming by other means.
- NEVER rename symbols with find-and-replace — use `rename` which understands the call graph.
- NEVER commit before MCP/CLI graph change analysis.

## Resources

| Resource | Use for |
| --- | --- |
| `gitnexus://repo/nsy/context` | Codebase overview, check index freshness |
| `gitnexus://repo/nsy/clusters` | All functional areas |
| `gitnexus://repo/nsy/processes` | All execution flows |
| `gitnexus://repo/nsy/process/{name}` | Step-by-step execution trace |

## CLI

| Task | Read this skill file |
| --- | --- |
| Understand architecture / "How does X work?" | `.claude/skills/gitnexus-exploring/SKILL.md` |
| Blast radius / "What breaks if I change X?" | `.claude/skills/gitnexus-impact-analysis/SKILL.md` |
| Trace bugs / "Why is X failing?" | `.claude/skills/gitnexus-debugging/SKILL.md` |
| Rename / extract / split / refactor | `.claude/skills/gitnexus-refactoring/SKILL.md` |
| Tools, resources, schema reference | `.claude/skills/gitnexus-guide/SKILL.md` |
| Index, status, clean, wiki CLI commands | `.claude/skills/gitnexus-cli/SKILL.md` |

<!-- gitnexus:end -->

## System Tools & Preferences
- **Search:** Always use `ripgrep` (`rg`) for text and pattern searches across the codebase. Never use standard `grep`.
- **File Preview:** Use `bat` (or `batcat`) when inspecting file contents in the terminal for syntax highlighting.