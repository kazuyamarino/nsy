<?php
$title   = $title ?? 'Under Maintenance';
$message = trim((string) ($message ?? '')) !== ''
	? (string) $message
	: 'We are performing scheduled maintenance. Please check back soon.';
include __DIR__ . '/_layout.php';
