<?php
/**
 * Shared shell for NSY error pages.
 *
 * Expects $code and $message; $title is optional. Rendered by
 * System\Core\NSY_ErrorPage::render().
 */
$code    = (int) ($code ?? 500);
$title   = (string) ($title ?? ('Error ' . $code));
$message = (string) ($message ?? '');
?><!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="noindex">
	<title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?> &middot; NSY</title>
	<style>
		:root { color-scheme: dark; }
		* { box-sizing: border-box; }
		body {
			margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center;
			font-family: 'Lato', system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
			background: #0F4468; color: #E4F1FB;
		}
		.card {
			max-width: 520px; margin: 24px; padding: 40px 36px; text-align: center;
			background: #133A56; border: 1px solid #235a7e; border-radius: 16px;
		}
		.code {
			font-size: 64px; font-weight: 800; line-height: 1; letter-spacing: 2px;
			background: linear-gradient(135deg, #5CB4F5, #1D7BBA);
			-webkit-background-clip: text; background-clip: text; color: transparent;
		}
		h1 { margin: 8px 0 12px; font-size: 22px; }
		p { margin: 0; color: #BFD0DE; line-height: 1.6; }
		a {
			display: inline-block; margin-top: 24px; padding: 10px 18px; border-radius: 10px;
			background: #1D7BBA; color: #fff; text-decoration: none; font-weight: 600;
		}
		a:hover { background: #3D9BE8; }
	</style>
</head>
<body>
	<main class="card">
		<div class="code"><?= $code ?></div>
		<h1><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h1>
		<p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
		<a href="<?= htmlspecialchars(base_url(), ENT_QUOTES, 'UTF-8') ?>">Back to home</a>
	</main>
</body>
</html>
