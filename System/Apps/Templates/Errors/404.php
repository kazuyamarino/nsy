<?php
$title   = $title ?? 'Page Not Found';
$message = trim((string) ($message ?? '')) !== ''
	? (string) $message
	: 'The page you are looking for could not be found.';
include __DIR__ . '/_layout.php';
