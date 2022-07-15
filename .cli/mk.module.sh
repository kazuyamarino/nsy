#!/bin/bash
make_module() {
	if [ -z $1 ]
	then
		printf "Module name undefined\n"
		printf "It should be like this 'make:module <module-name>'\n"
	elif [ -n $1 ]
	then
		dirname=$1

	if [ ! -d ./System/Modules/"$dirname" ]
	then
		mkdir ./System/Modules/$dirname
		mkdir ./System/Modules/$dirname/Controllers
		mkdir ./System/Modules/$dirname/Models
		mkdir ./System/Modules/$dirname/Views
		chmod -R 775 ./System/Modules/$dirname
		chmod -R 775 ./System/Modules/$dirname/Controllers
		chmod -R 775 ./System/Modules/$dirname/Models
		chmod -R 775 ./System/Modules/$dirname/Views

		printf "Module created\n"
		printf "see the results in the 'System/Modules' directory\n"
	else
		printf "Module already exists\n"
	fi
	fi
}
