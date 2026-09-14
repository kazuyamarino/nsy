#!/bin/bash
make_module() {
	if [ -z $1 ]
	then
		printf "Module name undefined\n"
		printf "It should be like this 'make:module [module-name]'\n"
	elif [ -n $1 ]
	then
		dirname=$1

	if [ ! -d ./System/Apps/Modules/"$dirname" ]
	then
		mkdir ./System/Apps/Modules/$dirname
		mkdir ./System/Apps/Modules/$dirname/Controllers
		mkdir ./System/Apps/Modules/$dirname/Models
		mkdir ./System/Apps/Modules/$dirname/Views
		chmod -R 775 ./System/Apps/Modules/$dirname
		chmod -R 775 ./System/Apps/Modules/$dirname/Controllers
		chmod -R 775 ./System/Apps/Modules/$dirname/Models
		chmod -R 775 ./System/Apps/Modules/$dirname/Views

		printf "Module created\n"
		printf "see the results in the 'System/Apps/Modules' directory\n"
	else
		printf "Module already exists\n"
	fi
	fi
}
