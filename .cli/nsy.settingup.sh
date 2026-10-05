#!/bin/bash
run_setup() {
	local last_dir_name env_file
	last_dir_name="$(basename "$NSY_ROOT_DIR")"

	printf "Prepare for NSY settings...\n"
	printf "The project directory name is: %s\n" "$last_dir_name"

	if [ -z "$last_dir_name" ]; then
		printf "The application directory name is not specified\n"
		return 1
	fi

	env_file="$NSY_ROOT_DIR/env.php"

	# env.php: created from the example only when it is absent. An existing file
	# is never overwritten, so database credentials and other values a user has
	# already filled in survive a re-run.
	if [ ! -e "$env_file" ]; then
		cp "$NSY_ROOT_DIR/docs/env.example/env.example.php" "$env_file"
		printf "Created env.php from docs/env.example/env.example.php\n"
	fi

	cp "$NSY_ROOT_DIR/docs/apache/for_public/.htaccess" "$NSY_ROOT_DIR/public/.htaccess"
	cp "$NSY_ROOT_DIR/docs/apache/for_root/.htaccess" "$NSY_ROOT_DIR/.htaccess"
	cp "$NSY_ROOT_DIR/.cli/tmp/system.js" "$NSY_ROOT_DIR/public/assets/js/config/system.js"
	cp "$NSY_ROOT_DIR/.cli/tmp/default" "$NSY_ROOT_DIR/docs/nginx/sites-enabled/default"

	# Scope replacements to the APP_DIR value / known markers (no blanket "nsy" replace).
	#
	# An env.php that already exists is common — either copied from the example
	# by hand, or created by an earlier run. Point it at this folder only while it
	# still holds the example's placeholder; a value the user deliberately changed
	# (a custom APP_DIR, or '' for a hosting layout) is left alone.
	if grep -Fq "'APP_DIR' => 'nsy'" "$env_file"; then
		sed_inplace "s/'APP_DIR' => '[^']*'/'APP_DIR' => '$last_dir_name'/" "$env_file"
		printf "env.php: APP_DIR set to '%s'\n" "$last_dir_name"
	elif grep -Fq "'APP_DIR' => '$last_dir_name'" "$env_file"; then
		printf "env.php: APP_DIR already points at '%s'\n" "$last_dir_name"
	else
		printf "env.php: APP_DIR keeps its existing value (not the 'nsy' default)\n"
	fi

	sed_inplace "s/var dirname = \"[^\"]*\";/var dirname = \"$last_dir_name\";/" "$NSY_ROOT_DIR/public/assets/js/config/system.js"
	sed_inplace "s#/nsy/#/$last_dir_name/#g" "$NSY_ROOT_DIR/docs/nginx/sites-enabled/default"

	printf "NSY has been set up\n"
}
