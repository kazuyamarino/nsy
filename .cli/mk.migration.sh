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
		if [ ! -e ./System/Migrations/"$mig.php" ]
		then
			cp .cli/tmp/mig_tmp.php ./System/Migrations/"$mig.php"
			sed -i "s/mig_tmp/$mig/g" ./System/Migrations/"$mig.php"

			printf "Migration created\n"
			printf "see the results in the 'System/Migrations' directory\n"
		else
			printf "Migration already exists\n"
		fi
	fi
}
