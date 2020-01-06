#!/bin/bash

if [ -z $1 ]
then
	printf "Module name undefined\n"
	printf "There must be 'module <module-name>'\n"
elif [ -n $1 ]
then
	dirname=$1

	if [ ! -d ./system/modules/"$dirname" ]
	then
	    mkdir ./system/modules/$dirname
	    mkdir ./system/modules/$dirname/controllers
	    mkdir ./system/modules/$dirname/models
	    mkdir ./system/modules/$dirname/views
		chmod -R 775 ./system/modules/$dirname
		chmod -R 775 ./system/modules/$dirname/controllers
		chmod -R 775 ./system/modules/$dirname/models
		chmod -R 775 ./system/modules/$dirname/views

	    printf "Module created\n"
	    printf "see the results in the 'system/modules' directory\n"
	else
	    printf "Module already exists\n"
	fi
fi
