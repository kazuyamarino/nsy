#!/bin/bash
show_migration() {
	printf "List of 'migrations' class :\n"
	printf "==========================\n"

	local files=("$NSY_ROOT_DIR"/System/Migrations/*.php)
	if [ -e "${files[0]}" ]; then
		local i=1 f
		for f in "${files[@]}"; do
			printf "%s. %s\n" "$i" "$(basename "$f")"
			i=$((i + 1))
		done
	else
		printf "No such file or directory\n"
	fi
	printf "==========================\n"
}
