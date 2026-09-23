#!/bin/bash
run_setup() {
	local last_dir_name
	last_dir_name="$(basename "$NSY_ROOT_DIR")"

	printf "Prepare for NSY settings...\n"
	printf "The project directory name is: %s\n" "$last_dir_name"

	if [ -z "$last_dir_name" ]; then
		printf "The application directory name is not specified\n"
		return 1
	fi

	if [ -e "$NSY_ROOT_DIR/env.php" ]; then
		printf "NSY has already been prepared\n"
		return 0
	fi

	cp "$NSY_ROOT_DIR/docs/apache/for_public/.htaccess" "$NSY_ROOT_DIR/public/.htaccess"
	cp "$NSY_ROOT_DIR/docs/apache/for_root/.htaccess" "$NSY_ROOT_DIR/.htaccess"
	cp "$NSY_ROOT_DIR/.cli/tmp/env.example.php" "$NSY_ROOT_DIR/env.php"
	cp "$NSY_ROOT_DIR/.cli/tmp/system.js" "$NSY_ROOT_DIR/public/assets/js/config/system.js"
	cp "$NSY_ROOT_DIR/.cli/tmp/default" "$NSY_ROOT_DIR/docs/nginx/sites-enabled/default"

	# Scope replacements to the APP_DIR value / known markers (no blanket "nsy" replace)
	sed_inplace "s/'APP_DIR' => '[^']*'/'APP_DIR' => '$last_dir_name'/" "$NSY_ROOT_DIR/env.php"
	sed_inplace "s/var dirname = \"[^\"]*\";/var dirname = \"$last_dir_name\";/" "$NSY_ROOT_DIR/public/assets/js/config/system.js"
	sed_inplace "s#/nsy/#/$last_dir_name/#g" "$NSY_ROOT_DIR/docs/nginx/sites-enabled/default"

	printf "NSY has been set up\n"
}
