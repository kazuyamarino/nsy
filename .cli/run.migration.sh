#!/bin/bash
run_migration() {
	local entry="$1" direction="${2:-up}"

	if [ -z "$entry" ]; then
		printf "Migration [command options] undefined\n"
		printf "It should be like this 'run:migrate [all|list|migration-name] [up|down]'\n"
		return 1
	fi

	if ! command -v php >/dev/null 2>&1; then
		printf "PHP CLI not found in PATH\n"
		return 1
	fi

	local runner="$NSY_ROOT_DIR/.cli/tmp/migrate.php"

	run_one() {
		local cls="$1" dir="$2"
		if php "$runner" "$cls" "$dir"; then
			return 0
		fi
		printf "Failed: %s (%s)\n" "$cls" "$dir"
		return 1
	}

	case "$entry" in
		"all")
			local files=("$NSY_ROOT_DIR"/System/Migrations/*.php)
			if [ ! -e "${files[0]}" ]; then
				printf "No migration classes found in System/Migrations\n"
				return 1
			fi

			local f cls failed=0
			for f in "${files[@]}"; do
				cls="$(basename "$f" .php)"
				if run_one "$cls" "$direction"; then
					printf "Successfully migrated %s (%s)\n" "$cls" "$direction"
				else
					failed=$((failed + 1))
				fi
			done

			printf "\nAll migration classes processed (failed: %s)\n" "$failed"
			;;

		"list")
			local files=("$NSY_ROOT_DIR"/System/Migrations/*.php)
			if [ ! -e "${files[0]}" ]; then
				printf "No migration classes found in System/Migrations\n"
				return 1
			fi

			local i=1 f
			for f in "${files[@]}"; do
				printf "%s) %s\n" "$i" "$(basename "$f")"
				i=$((i + 1))
			done

			printf "Select a migration class from the above list: "
			local opt
			IFS= read -r opt

			if ! [[ "$opt" =~ ^[0-9]+$ ]] || [ "$opt" -lt 1 ] || [ "$opt" -gt "${#files[@]}" ]; then
				printf "\nMigration class not selected or not found\n"
				return 1
			fi

			local chosen
			chosen="$(basename "${files[$((opt - 1))]}" .php)"
			if run_one "$chosen" "$direction"; then
				printf "\nSuccessfully migrated %s (%s)\n" "$chosen" "$direction"
			else
				return 1
			fi
			;;

		*)
			local dest="$NSY_ROOT_DIR/System/Migrations/$entry.php"
			if [ ! -e "$dest" ]; then
				printf "No migration found: System/Migrations/%s.php\n" "$entry"
				return 1
			fi
			if run_one "$entry" "$direction"; then
				printf "\nSuccessfully migrated %s (%s)\n" "$entry" "$direction"
			else
				return 1
			fi
			;;
	esac
}
