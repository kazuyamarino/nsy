#!/bin/bash
make_module() {
	local dirname="$1"

	if [ -z "$dirname" ]; then
		printf "Module name undefined\n"
		printf "It should be like this 'make:module [module-name]'\n"
		return 1
	fi

	local base="$NSY_ROOT_DIR/System/Apps/Modules/$dirname"
	if [ -d "$base" ]; then
		printf "Module already exists\n"
		return 0
	fi

	mkdir -p "$base/Controllers" "$base/Models" "$base/Views"
	chmod -R 775 "$base"

	printf "Module created: System/Apps/Modules/%s (Controllers, Models, Views)\n" "$dirname"
	nsy_dump_autoload
}
