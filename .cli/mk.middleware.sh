#!/bin/bash
make_after_middleware() {
	if [ -z $1 ]
	then
		printf "After Middleware name or table name undefined\n"
		printf "It should be like this 'make:after-middleware [middleware-name]'\n"
	elif [ -n $1 ]
	then
		mid=$1

		# Create middleware
		if [ ! -e ./System/Middlewares/"$mid.php" ]
		then
			# Create controller
			cp .cli/tmp/after_lyr.php ./System/Middlewares/"$mid.php"
			sed -i "s/after_lyr/$mid/g" ./System/Middlewares/"$mid.php"

			printf "After Middleware created\n"
			printf "see the results in the 'System/Middlewares' directory\n"
		else
			printf "After Middleware already exists\n"
		fi
	fi
}

make_before_middleware() {
	if [ -z $1 ]
	then
		printf "Before Middleware name or table name undefined\n"
		printf "It should be like this 'make:before-middleware [middleware-name]'\n"
	elif [ -n $1 ]
	then
		mid=$1

		# Create middleware
		if [ ! -e ./System/Middlewares/"$mid.php" ]
		then
			# Create controller
			cp .cli/tmp/before_lyr.php ./System/Middlewares/"$mid.php"
			sed -i "s/before_lyr/$mid/g" ./System/Middlewares/"$mid.php"

			printf "Before Middleware created\n"
			printf "see the results in the 'System/Middlewares' directory\n"
		else
			printf "Before Middleware already exists\n"
		fi
	fi
}
