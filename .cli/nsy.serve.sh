#!/bin/bash
run_serve() {
	local port="${1:-8000}" host="${2:-127.0.0.1}"

	if ! command -v php >/dev/null 2>&1; then
		printf "PHP CLI not found in PATH\n"
		return 1
	fi

	local app_dir
	app_dir="$(php -r '$e=@include $argv[1]; echo (is_array($e) && !empty($e["APP_DIR"])) ? trim((string) $e["APP_DIR"], "/") : "";' "$NSY_ROOT_DIR/env.php" 2>/dev/null)"

	# With APP_DIR the URL is /<APP_DIR>/... so docroot must be the project's parent.
	# Without APP_DIR, docroot is public/.
	local docroot
	if [ -n "$app_dir" ]; then
		docroot="$(dirname "$NSY_ROOT_DIR")"
	else
		docroot="$NSY_ROOT_DIR/public"
	fi

	if [ ! -d "$docroot" ]; then
		printf "Document root not found: %s\n" "$docroot"
		return 1
	fi

	printf "NSY dev server running:\n"
	if [ -n "$app_dir" ]; then
		printf "  http://%s:%s/%s/\n" "$host" "$port" "$app_dir"
	else
		printf "  http://%s:%s/\n" "$host" "$port"
	fi
	printf "  Docroot : %s\n" "$docroot"
	printf "  Router  : .cli/tmp/router.php\n"
	printf "Press Ctrl+C to stop.\n\n"

	php -S "$host:$port" -t "$docroot" "$NSY_ROOT_DIR/.cli/tmp/router.php"
}
