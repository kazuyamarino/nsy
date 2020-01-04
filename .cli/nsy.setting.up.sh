#!/bin/bash
echo "Enter directory name > "
read response
if [ -n "$response" ];
then
    filename=$response

	if [ ! -e ./env ]
	then
		cp docs/env.example/env.example ./env
		cp docs/apache/for_public/.htaccess ./public/.htaccess
		cp docs/apache/for_root/.htaccess ./.htaccess
		sed -i "s/nsy/$filename/g" ./env
		sed -i "s/nsy/$filename/g" ./public/js/config/system.js
		sed -i "s/nsy/$filename/g" ./docs/nginx/sites-enabled/default

		printf "Please wait...\n"
		sleep 5

		printf "NSY successfully prepared\n"
	else
	    printf "NSY already prepared\n"
	fi
else
	printf "Application directory name undefined\n"
fi
