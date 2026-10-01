#!/bin/bash
run_seeder() {
	local entry="$1"

	if [ -z "$entry" ]; then
		printf "Seeder [all|list|seeder-name] undefined\n"
		printf "It should be like this 'run:seed [all|list|seeder-name]'\n"
		return 1
	fi

	if ! command -v php >/dev/null 2>&1; then
		printf "PHP CLI not found in PATH\n"
		return 1
	fi

	local runner="$NSY_ROOT_DIR/.cli/tmp/seed.php"

	case "$entry" in
		"all")
			local files=("$NSY_ROOT_DIR"/System/Seeders/*.php)
			if [ ! -e "${files[0]}" ]; then
				printf "No seeder classes found in System/Seeders\n"
				return 1
			fi

			php "$runner" all
			;;

		"list")
			local files=("$NSY_ROOT_DIR"/System/Seeders/*.php)
			if [ ! -e "${files[0]}" ]; then
				printf "No seeder classes found in System/Seeders\n"
				return 1
			fi

			local i=1 f
			for f in "${files[@]}"; do
				printf "%s) %s\n" "$i" "$(basename "$f")"
				i=$((i + 1))
			done

			printf "Select a seeder class from the above list: "
			local opt
			IFS= read -r opt

			if ! [[ "$opt" =~ ^[0-9]+$ ]] || [ "$opt" -lt 1 ] || [ "$opt" -gt "${#files[@]}" ]; then
				printf "\nSeeder class not selected or not found\n"
				return 1
			fi

			local chosen
			chosen="$(basename "${files[$((opt - 1))]}" .php)"
			if php "$runner" "$chosen"; then
				printf "\nSuccessfully seeded %s\n" "$chosen"
			else
				return 1
			fi
			;;

		*)
			local dest="$NSY_ROOT_DIR/System/Seeders/$entry.php"
			if [ ! -e "$dest" ]; then
				printf "No seeder found: System/Seeders/%s.php\n" "$entry"
				return 1
			fi

			if php "$runner" "$entry"; then
				printf "\nSuccessfully seeded %s\n" "$entry"
			else
				return 1
			fi
			;;
	esac
}
