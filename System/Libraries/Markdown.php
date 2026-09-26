<?php
declare(strict_types=1);

namespace System\Libraries;

/**
 * Minimal, dependency-free Markdown → HTML renderer tuned for the NSY docs.
 *
 * Supported: ATX & Setext headings, fenced code blocks, GFM tables, nested
 * ordered/unordered lists, blockquotes, horizontal rules, emphasis, inline
 * code, links, images, autolinks and Table-of-Contents extraction with
 * GitHub-compatible (non-collapsing) heading slugs.
 *
 * Raw HTML is escaped on purpose: code samples stay readable and the viewer
 * can never be used to inject markup. Code fences are extracted before any
 * other parsing so their contents are never re-interpreted.
 */
class Markdown
{
	/** @var array<int,string> Rendered <pre> blocks, restored after parsing. */
	private array $blocks = [];

	/** @var array<int,array{level:int,text:string,id:string}> */
	private array $toc = [];

	/** @var array<string,bool> Slug dedupe registry. */
	private array $slugs = [];

	public static function toHtml(string $markdown): string
	{
		return (new self())->render($markdown);
	}

	/**
	 * @return array{html:string,toc:array<int,array{level:int,text:string,id:string}>}
	 */
	public static function toHtmlWithToc(string $markdown): array
	{
		$parser = new self();
		$html = $parser->render($markdown);

		return ['html' => $html, 'toc' => $parser->toc];
	}

	public function render(string $markdown): string
	{
		$markdown = str_replace(["\r\n", "\r"], "\n", $markdown);
		$markdown = preg_replace('/^\xEF\xBB\xBF/', '', $markdown) ?? $markdown;
		$markdown = $this->extractFencedCode($markdown);

		$html = $this->parseBlocks(explode("\n", $markdown));

		return (string) preg_replace_callback('/\x00B(\d+)\x00/', function (array $m): string {
			return $this->blocks[(int) $m[1]] ?? '';
		}, $html);
	}

	// ---------------------------------------------------------------------
	// Block level
	// ---------------------------------------------------------------------

	/**
	 * @param string[] $lines
	 */
	private function parseBlocks(array $lines): string
	{
		$html = '';
		$i = 0;
		$n = count($lines);

		while ($i < $n) {
			$line = $lines[$i];

			if (trim($line) === '') {
				$i++;
				continue;
			}

			// Fenced code placeholder
			if (preg_match('/^\x00B(\d+)\x00$/', $line, $m)) {
				$html .= $this->blocks[(int) $m[1]] . "\n";
				$i++;
				continue;
			}

			// ATX heading
			if (preg_match('/^(#{1,6})\s+(.*?)\s*#*\s*$/', $line, $m)) {
				$html .= $this->heading(strlen($m[1]), $m[2]);
				$i++;
				continue;
			}

			// Setext heading (=== → h1, --- → h2)
			if (isset($lines[$i + 1]) && $this->isPlainText($line)
				&& preg_match('/^ {0,3}(=+|-+)\s*$/', $lines[$i + 1], $m)) {
				$html .= $this->heading($m[1][0] === '=' ? 1 : 2, trim($line));
				$i += 2;
				continue;
			}

			// Horizontal rule
			if (preg_match('/^ {0,3}([-*_])(?:\s*\1){2,}\s*$/', $line)) {
				$html .= "<hr>\n";
				$i++;
				continue;
			}

			// Blockquote
			if (preg_match('/^ {0,3}>\s?(.*)$/', $line)) {
				$buf = [];
				while ($i < $n && preg_match('/^ {0,3}>\s?(.*)$/', $lines[$i], $m)) {
					$buf[] = $m[1];
					$i++;
				}
				$html .= "<blockquote>\n" . trim($this->parseBlocks($buf)) . "\n</blockquote>\n";
				continue;
			}

			// GFM table
			if (strpos($line, '|') !== false && isset($lines[$i + 1]) && $this->isTableSeparator($lines[$i + 1])) {
				$html .= $this->parseTable($lines, $i);
				continue;
			}

			// List
			if ($this->matchListLine($line) !== null) {
				$html .= $this->parseList($lines, $i);
				continue;
			}

			// Paragraph
			$buf = [$line];
			$i++;
			while ($i < $n) {
				$next = $lines[$i];
				if (
					trim($next) === ''
					|| preg_match('/^\x00B\d+\x00$/', $next)
					|| preg_match('/^(#{1,6})\s+/', $next)
					|| preg_match('/^ {0,3}>/', $next)
					|| $this->matchListLine($next) !== null
					|| preg_match('/^ {0,3}([-*_])(?:\s*\1){2,}\s*$/', $next)
					|| (strpos($next, '|') !== false && isset($lines[$i + 1]) && $this->isTableSeparator($lines[$i + 1]))
				) {
					break;
				}
				$buf[] = $next;
				$i++;
			}
			$html .= '<p>' . $this->inline(implode("\n", $buf)) . "</p>\n";
		}

		return $html;
	}

	/**
	 * @param string[] $lines
	 */
	private function parseList(array $lines, int &$i): string
	{
		$first = $this->matchListLine($lines[$i]);
		if ($first === null) {
			return '';
		}

		$ordered = $first['ordered'];
		$base = $first['indent'];
		$items = [];
		$n = count($lines);

		while ($i < $n) {
			$current = $this->matchListLine($lines[$i]);
			if ($current === null || $current['indent'] !== $base) {
				break;
			}

			$childLines = [];
			$i++;

			while ($i < $n) {
				$line = $lines[$i];

				if (trim($line) === '') {
					// Peek past blank lines. The list ends when the next
					// non-blank line is a new same-level item, is not indented
					// far enough to belong to this item, or the input ends.
					$j = $i;
					while ($j < $n && trim($lines[$j]) === '') {
						$j++;
					}
					if ($j >= $n) {
						break;
					}
					$peek = $this->matchListLine($lines[$j]);
					if ($peek !== null && $peek['indent'] <= $base) {
						break;
					}
					if ($this->indentWidth($lines[$j]) < $current['contentIndent']) {
						break;
					}
					$childLines[] = '';
					$i++;
					continue;
				}

				$peek = $this->matchListLine($line);
				if ($peek !== null && $peek['indent'] <= $base) {
					break;
				}

				$childLines[] = $this->dedent($line, $current['contentIndent']);
				$i++;
			}

			$item = $this->inline($current['text']);
			if ($childLines !== []) {
				$child = trim($this->parseBlocks($childLines));
				if ($child !== '') {
					$item .= "\n" . $child;
				}
			}
			$items[] = $item;
		}

		if ($items === []) {
			return '';
		}

		$tag = $ordered ? 'ol' : 'ul';
		$out = "<{$tag}>\n";
		foreach ($items as $item) {
			$out .= '<li>' . $item . "</li>\n";
		}
		$out .= "</{$tag}>\n";

		return $out;
	}

	/**
	 * @param string[] $lines
	 */
	private function parseTable(array $lines, int &$i): string
	{
		$header = $this->splitRow($lines[$i]);
		$align = array_map(fn(string $c): string => $this->cellAlign(trim($c)), $this->splitRow($lines[$i + 1]));
		$i += 2;

		$rows = [];
		$n = count($lines);
		while ($i < $n) {
			$line = $lines[$i];
			if (trim($line) === '' || strpos($line, '|') === false) {
				break;
			}
			$rows[] = $this->splitRow($line);
			$i++;
		}

		$html = "<div class=\"md-table-wrap\">\n<table>\n<thead>\n<tr>\n";
		foreach ($header as $idx => $cell) {
			$html .= '<th' . $this->alignStyle($align[$idx] ?? '') . '>' . $this->inline(trim($cell)) . "</th>\n";
		}
		$html .= "</tr>\n</thead>\n<tbody>\n";
		foreach ($rows as $row) {
			$html .= "<tr>\n";
			foreach ($header as $idx => $_) {
				$html .= '<td' . $this->alignStyle($align[$idx] ?? '') . '>' . $this->inline(trim($row[$idx] ?? '')) . "</td>\n";
			}
			$html .= "</tr>\n";
		}
		$html .= "</tbody>\n</table>\n</div>\n";

		return $html;
	}

	// ---------------------------------------------------------------------
	// Helpers
	// ---------------------------------------------------------------------

	private function heading(int $level, string $raw): string
	{
		$level = max(1, min(6, $level));
		$plain = $this->plainText($raw);
		$id = $this->slug($plain);
		$this->toc[] = ['level' => $level, 'text' => $plain, 'id' => $id];

		return '<h' . $level . ' id="' . $id . '">' . $this->inline($raw) . '</h' . $level . ">\n";
	}

	private function extractFencedCode(string $markdown): string
	{
		$lines = explode("\n", $markdown);
		$out = [];
		$in = false;
		$fenceChar = '';
		$lang = '';
		$buf = [];

		foreach ($lines as $line) {
			if (!$in) {
				if (preg_match('/^\s*(`{3,}|~{3,})\s*([\w#+.\-]*)\s*$/', $line, $m)) {
					$in = true;
					$fenceChar = $m[1][0];
					$lang = strtolower($m[2]);
					$buf = [];
					continue;
				}
				$out[] = $line;
				continue;
			}

			if (preg_match('/^\s*' . preg_quote($fenceChar, '/') . '{3,}\s*$/', $line)) {
				$out[] = $this->storeCode(implode("\n", $buf), $lang);
				$in = false;
				$buf = [];
				continue;
			}

			$buf[] = $line;
		}

		if ($in) {
			$out[] = $this->storeCode(implode("\n", $buf), $lang);
		}

		return implode("\n", $out);
	}

	private function storeCode(string $code, string $lang = ''): string
	{
		$class = $lang !== ''
			? ' class="language-' . htmlspecialchars($lang, ENT_QUOTES, 'UTF-8') . '"'
			: '';
		$index = count($this->blocks);
		$this->blocks[$index] = '<pre class="md-pre"><code' . $class . '>'
			. htmlspecialchars(rtrim($code, "\n"), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')
			. '</code></pre>';

		return "\x00B{$index}\x00";
	}

	/**
	 * @return array{indent:int,ordered:bool,marker:string,text:string,contentIndent:int}|null
	 */
	private function matchListLine(string $line): ?array
	{
		if (!preg_match('/^(\s*)([-*+]|\d{1,9}[.)])\s+(.*)$/', $line, $m)) {
			return null;
		}

		$indent = strlen(str_replace("\t", '    ', $m[1]));
		$marker = $m[2];

		return [
			'indent' => $indent,
			'ordered' => ctype_digit($marker[0]),
			'marker' => $marker,
			'text' => $m[3],
			'contentIndent' => $indent + strlen($marker) + 1,
		];
	}

	private function indentWidth(string $line): int
	{
		$width = 0;
		$len = strlen($line);

		for ($i = 0; $i < $len; $i++) {
			if ($line[$i] === ' ') {
				$width++;
			} elseif ($line[$i] === "\t") {
				$width += 4;
			} else {
				break;
			}
		}

		return $width;
	}

	private function dedent(string $line, int $width): string
	{
		$i = 0;
		$removed = 0;
		$len = strlen($line);

		while ($i < $len && $removed < $width) {
			if ($line[$i] === ' ') {
				$removed++;
				$i++;
			} elseif ($line[$i] === "\t") {
				$removed += 4;
				$i++;
			} else {
				break;
			}
		}

		return substr($line, $i);
	}

	private function isPlainText(string $line): bool
	{
		$trimmed = ltrim($line);
		if ($trimmed === '') {
			return false;
		}

		return !preg_match('/^(#{1,6}\s|>|([-*+]|\d+[.)])\s)/', $trimmed);
	}

	private function isTableSeparator(string $line): bool
	{
		$line = trim($line);
		if ($line === '' || strpos($line, '-') === false) {
			return false;
		}

		return (bool) preg_match('/^\|?\s*:?-+:?\s*(\|\s*:?-+:?\s*)*\|?$/', $line);
	}

	/**
	 * @return string[]
	 */
	private function splitRow(string $line): array
	{
		$line = trim($line);
		$line = preg_replace('/^\|/', '', $line) ?? $line;
		$line = preg_replace('/\|$/', '', $line) ?? $line;
		$cells = preg_split('/(?<!\\\\)\|/', $line) ?: [];

		return array_map(static fn(string $c): string => str_replace('\\|', '|', trim($c)), $cells);
	}

	private function cellAlign(string $cell): string
	{
		$left = str_starts_with($cell, ':');
		$right = str_ends_with($cell, ':');

		if ($left && $right) {
			return 'center';
		}
		if ($left) {
			return 'left';
		}
		if ($right) {
			return 'right';
		}

		return '';
	}

	private function alignStyle(string $align): string
	{
		return $align !== '' ? ' style="text-align:' . $align . '"' : '';
	}

	private function inline(string $text): string
	{
		$text = htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false);

		// Inline code spans → placeholders so emphasis never touches them.
		$code = [];
		$text = preg_replace_callback('/(`+)(.+?)\1/s', function (array $m) use (&$code): string {
			$index = count($code);
			$code[$index] = '<code>' . $m[2] . '</code>';
			return "\x00C{$index}\x00";
		}, $text) ?? $text;

		// Emphasis / strikethrough
		$text = preg_replace('/\*\*\*(.+?)\*\*\*/s', '<strong><em>$1</em></strong>', $text) ?? $text;
		$text = preg_replace('/___(.+?)___/s', '<strong><em>$1</em></strong>', $text) ?? $text;
		$text = preg_replace('/\*\*(.+?)\*\*/s', '<strong>$1</strong>', $text) ?? $text;
		$text = preg_replace('/__(.+?)__/s', '<strong>$1</strong>', $text) ?? $text;
		$text = preg_replace('/(?<![\w*])\*(?!\s)(.+?)(?<!\s)\*(?![\w*])/s', '<em>$1</em>', $text) ?? $text;
		$text = preg_replace('/(?<![\w_])_(?!\s)(.+?)(?<!\s)_(?![\w_])/s', '<em>$1</em>', $text) ?? $text;
		$text = preg_replace('/~~(.+?)~~/s', '<del>$1</del>', $text) ?? $text;

		// Images
		$text = preg_replace_callback('/!\[([^\]]*)\]\(\s*([^\s)]+)(?:\s+&quot;([^&]*)&quot;)?\s*\)/', function (array $m): string {
			$src = $this->safeUrl($m[2]);
			if ($src === null) {
				return $m[0];
			}
			$alt = strip_tags($m[1]);
			$title = isset($m[3]) && $m[3] !== '' ? ' title="' . $m[3] . '"' : '';
			return '<img src="' . $src . '" alt="' . $alt . '"' . $title . ' loading="lazy">';
		}, $text) ?? $text;

		// Links
		$text = preg_replace_callback('/\[([^\]]+)\]\(\s*([^\s)]+)(?:\s+&quot;([^&]*)&quot;)?\s*\)/', function (array $m): string {
			$href = $this->safeUrl($m[2]);
			if ($href === null) {
				return $m[1];
			}
			$title = isset($m[3]) && $m[3] !== '' ? ' title="' . $m[3] . '"' : '';
			$external = preg_match('#^https?:#i', $m[2]) ? ' target="_blank" rel="noopener"' : '';
			return '<a href="' . $href . '"' . $title . $external . '>' . $m[1] . '</a>';
		}, $text) ?? $text;

		// Autolinks (text is already escaped at this point)
		$text = preg_replace(
			'/&lt;(https?:\/\/[^\s&]+)&gt;/i',
			'<a href="$1" target="_blank" rel="noopener">$1</a>',
			$text
		) ?? $text;
		$text = preg_replace(
			'/&lt;([A-Za-z0-9._%+\-]+@[A-Za-z0-9.\-]+\.[A-Za-z]{2,})&gt;/',
			'<a href="mailto:$1">$1</a>',
			$text
		) ?? $text;

		// Hard line breaks (two or more trailing spaces)
		$text = preg_replace('/ {2,}\n/', "<br>\n", $text) ?? $text;

		// Restore inline code
		$text = preg_replace_callback('/\x00C(\d+)\x00/', function (array $m) use ($code): string {
			return $code[(int) $m[1]] ?? '';
		}, $text) ?? $text;

		return $text;
	}

	private function safeUrl(string $url): ?string
	{
		$decoded = strtolower(html_entity_decode($url, ENT_QUOTES, 'UTF-8'));
		if (preg_match('/^\s*(javascript|vbscript|data):/', $decoded)) {
			return null;
		}

		return $url;
	}

	private function plainText(string $text): string
	{
		$plain = preg_replace('/`+([^`]*)`+/', '$1', $text) ?? $text;
		$plain = preg_replace('/!?\[([^\]]*)\]\([^)]*\)/', '$1', $plain) ?? $plain;
		// Strip emphasis/strikethrough markers but keep literal underscores —
		// GitHub slugs preserve them (e.g. "NSY_AssetManager").
		$plain = preg_replace('/[*~]+/', '', $plain) ?? $plain;
		$plain = html_entity_decode($plain, ENT_QUOTES, 'UTF-8');

		return trim($plain);
	}

	/**
	 * GitHub-compatible slug: lowercase, drop punctuation/symbols (keeping
	 * letters, numbers, spaces, "_" and "-"), replace each space with "-".
	 * Hyphens are NOT collapsed; duplicates get a "-1", "-2", … suffix.
	 */
	private function slug(string $text): string
	{
		$slug = strtolower(trim($text));
		$slug = preg_replace('/[^\p{L}\p{N}\s_-]+/u', '', $slug) ?? $slug;
		$slug = str_replace(' ', '-', $slug);

		if ($slug === '') {
			$slug = 'section';
		}

		$base = $slug;
		$n = 1;
		while (isset($this->slugs[$slug])) {
			$slug = $base . '-' . $n;
			$n++;
		}
		$this->slugs[$slug] = true;

		return $slug;
	}
}
