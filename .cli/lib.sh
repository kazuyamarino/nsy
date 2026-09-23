#!/bin/bash
# Shared helpers for NSY CLI

NSY_CLI_VERSION="2.0.0"

# Portable in-place sed (works on GNU/Linux and BSD/macOS)
sed_inplace() {
	local expr="$1" file="$2" tmp
	tmp="$(mktemp "${TMPDIR:-/tmp}/nsy.XXXXXX")" || return 1
	if sed "$expr" "$file" > "$tmp"; then
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
