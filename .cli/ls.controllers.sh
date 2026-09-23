#!/bin/bash
show_controller() {
	local mode="$1" module="$2"

	if [ -z "$mode" ]; then
		printf "Mode undefined, must be hmvc or mvc\n"
		printf "It should be like this 'show:controller [mode]'\n"
		return 1
	fi

	case "$mode" in
		"mvc")
			printf "List of 'mvc' controllers :\n"
			printf "==========================\n"
			local files=("$NSY_ROOT_DIR"/System/Apps/General/Controllers/*.php)
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
			;;
		"hmvc")
			if [ -z "$module" ]; then
				printf "Module name undefined\n"
				printf "It should be like this 'show:controller hmvc [module-name]'\n"
				return 1
			fi
			if [ ! -d "$NSY_ROOT_DIR/System/Apps/Modules/$module" ]; then
				printf "Module doesn't exists\n"
				return 1
			fi

			printf "List of 'hmvc' controllers :\n"
			printf "==========================\n"
			local files=("$NSY_ROOT_DIR"/System/Apps/Modules/"$module"/Controllers/*.php)
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
			;;
		*)
			printf "Hmm, it seems NSY never use that mode.\n"
			return 1
			;;
	esac
}
