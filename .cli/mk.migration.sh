#!/bin/bash
make_migration() {
	if [ -z $1 ]
	then
		printf "Migration name or table name undefined\n"
		printf "It should be like this 'make:migration <migration-name>'\n"
	elif [ -n $1 ]
	then
		mig=$1

		## backup dir format ##
		backup_dir=$(date +'%d%m%Y_%H%M%S')

		# Create migration
		if [ ! -e ./system/migrations/"$mig.php" ]
		then
			# Create controller
			cp .cli/tmp/mig_tmp.php ./system/migrations/"$mig.php"
			sed -i "s/mig_tmp/$mig/g" ./system/migrations/"$mig.php"

			printf "Migration created\n"
		    printf "see the results in the 'system/migrations' directory\n"
		else
			printf "Migration already exists\n"
		fi
	fi
}
