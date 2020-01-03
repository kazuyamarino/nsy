#!/bin/bash
if [ -z $1 ]
then
	printf "Application directory name undefined\n"
	printf "There must be 'nsy_setting_up <app-dir-name>'\n"
elif [ -n $1 ]
then
	app_dir=$1

	if [ ! -e ./env ]
	then
		cp docs/env.example/env.example ./env
		cp docs/apache/for_public/.htaccess ./public/.htaccess
		cp docs/apache/for_root/.htaccess ./.htaccess
		sed -i "s/nsy/$app_dir/g" ./env
		sed -i "s/nsy/$app_dir/g" ./public/js/config/system.js
		sed -i "s/nsy/$app_dir/g" ./docs/nginx/sites-enabled/default

		printf "NSY successfully prepared\n"
	else
	    printf "NSY already prepared\n"
	fi
fi
