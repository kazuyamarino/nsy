#!/bin/bash
make_route() {
	if [ -z $1 ]
	then
		printf "Route name or table name undefined\n"
		printf "It should be like this 'make:route [route-name]'\n"
	elif [ -n $1 ]
	then
		route=$1

		# Create Route
		if [ ! -e ./System/Routes/"$route.php" ]
		then
			cp .cli/tmp/route_tmp.php ./System/Routes/"$route.php"

			printf "Route created\n"
			printf "see the results in the 'System/Routes' directory\n\n"

			printf "Route class must be registered in 'Config/App.php' on 'User Defined Routes'\n"
		else
			printf "Route already exists\n"
		fi
	fi
}
