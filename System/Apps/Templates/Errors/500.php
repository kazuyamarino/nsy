<?php
$title   = $title ?? 'Server Error';
$message = trim((string) ($message ?? '')) !== ''
	? (string) $message
	: 'Something went wrong while processing your request.';
include __DIR__ . '/_layout.php';
