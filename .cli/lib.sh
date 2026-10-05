#!/bin/bash
# Shared helpers for NSY CLI

NSY_CLI_VERSION="2.0.0"

# File mode as octal (e.g. 755). GNU stat first, then BSD/macOS `stat -f`.
# Returns nothing (and false) when neither form is available.
file_mode() {
	stat -c '%a' "$1" 2>/dev/null || stat -f '%Lp' "$1" 2>/dev/null
}

# Portable in-place sed (works on GNU/Linux and BSD/macOS).
#
# The rewrite goes through a temp file, and `mv` replaces the target rather than
# writing into it — so the temp file's own mode would otherwise become the
# result. mktemp creates 0600, which silently stripped the mode from every file
# this touched: env.php and system.js after `--setup`, and every `make:*`
# generated class. Restore the original mode on the temp file before the move so
# a 0755 file stays 0755 and a 0644 file stays 0644.
#
# The temp file is deliberately left writable (mktemp's 0600) during the sed
# pass: copying the source mode first would make a read-only source (0444) fail
# to open for writing.
sed_inplace() {
	local expr="$1" file="$2" tmp mode
	tmp="$(mktemp "${TMPDIR:-/tmp}/nsy.XXXXXX")" || return 1
	mode="$(file_mode "$file")"
	if sed "$expr" "$file" > "$tmp"; then
		[ -n "$mode" ] && chmod "$mode" "$tmp" 2>/dev/null
		mv "$tmp" "$file"
	else
		rm -f "$tmp"
		return 1
	fi
}

# Capitalize first letter (bash 4+, portable)
ucfirst() {
	local s="$1"
	printf '%s%s' "${s:0:1}" "${s:1}"
}

# Regenerate composer autoload after creating classes
nsy_dump_autoload() {
	if command -v composer >/dev/null 2>&1; then
		(cd "$NSY_ROOT_DIR" && composer dump-autoload -o >/dev/null 2>&1) \
			&& printf "Autoload updated\n" \
			|| printf "Note: 'composer dump-autoload -o' failed, run it manually\n"
	else
		printf "Note: composer not found. Run 'composer dump-autoload -o' for autoloading.\n"
	fi
}

# Validate a generated class/file name (letters, digits, underscore only).
# Guards the `sed` substitutions and file paths used by the mk.* generators.
nsy_valid_name() {
	case "$1" in
		''|*[!A-Za-z0-9_]*) return 1 ;;
		*) return 0 ;;
	esac
}
