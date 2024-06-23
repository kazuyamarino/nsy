#!/bin/bash
run_setup() {
	current_dir=$(pwd)
	last_dir_name=$(basename "$current_dir")

	echo "Prepare for NSY settings..."
	echo "The current working directory is: $last_dir_name"
	sleep 3

	if [ -n "$last_dir_name" ];
	then
		if [ ! -e ./env.php ]
		then
			cp docs/apache/for_public/.htaccess ./public/.htaccess
			cp docs/apache/for_root/.htaccess ./.htaccess
			cp .cli/tmp/env.example.php ./env.php
			cp .cli/tmp/env.example.php docs/env.example/env.example.php
			cp .cli/tmp/system.js ./public/assets/js/config/system.js
			cp .cli/tmp/default ./docs/nginx/sites-enabled/default
			sed -i "s/nsy/$last_dir_name/g" ./env.php
			sed -i "s/nsy/$last_dir_name/g" ./docs/env.example/env.example.php
			sed -i "s/nsy/$last_dir_name/g" ./public/assets/js/config/system.js
			sed -i "s/nsy/$last_dir_name/g" ./docs/nginx/sites-enabled/default

			printf "Please wait...\n"
			sleep 3

			printf "NSY has been set up\n"
		else
			printf "NSY has already been prepared\n"
		fi
	else
		printf "The application directory name is not specified\n"
	fi
}
