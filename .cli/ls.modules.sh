#!/bin/bash
show_module() {
	printf "List of 'hmvc' modules :\n"
	printf "==========================\n"

	local dirs=("$NSY_ROOT_DIR"/System/Apps/Modules/*)
	if [ -d "${dirs[0]}" ]; then
		local i=1 d
		for d in "${dirs[@]}"; do
			printf "%s. %s\n" "$i" "$(basename "$d")"
			i=$((i + 1))
		done
	else
		printf "No such file or directory\n"
	fi
	printf "==========================\n"
}
