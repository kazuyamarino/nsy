#!/bin/bash
run_setup() {
	echo "Enter directory name > "
	read response
	if [ -n "$response" ];
	then
	    filename=$response

		if [ ! -e ./env ]
		then
			cp docs/apache/for_public/.htaccess ./public/.htaccess
			cp docs/apache/for_root/.htaccess ./.htaccess
			cp .cli/tmp/env.example ./env
			cp .cli/tmp/env.example docs/env.example/env.example
			cp .cli/tmp/system.js ./public/assets/js/config/system.js
			cp .cli/tmp/default ./docs/nginx/sites-enabled/default
			sed -i "s/nsy/$filename/g" ./env
			sed -i "s/nsy/$filename/g" ./docs/env.example/env.example
			sed -i "s/nsy/$filename/g" ./public/assets/js/config/system.js
			sed -i "s/nsy/$filename/g" ./docs/nginx/sites-enabled/default

			printf "Please wait...\n"
			sleep 3

			printf "NSY successfully prepared\n"
		else
		    printf "NSY already prepared\n"
		fi
	else
		printf "Application directory name undefined\n"
	fi
}
