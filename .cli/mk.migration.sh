#!/bin/bash
if [ -z $1 ] || [ -z $2 ]
then
	printf "Migration name or table name undefined\n"
	printf "There must be 'make_migration <migration-name> <table-name>'\n"
elif [ -n $1 ]
then
	mig=$1
	table=$2

	## backup dir format ##
	backup_dir=$(date +'%d%m%Y_%H%M%S')

	# Create migration
	if [ ! -e ./system/migrations/${backup_dir}_"$mig.php" ]
	then
		# Create controller
		cp .cli/tmp/mig_tmp.php ./system/migrations/${backup_dir}_"$mig.php"
		sed -i "s/mig_tmp/$mig/g" ./system/migrations/${backup_dir}_"$mig.php"
		sed -i "s/my_table/$table/g" ./system/migrations/${backup_dir}_"$mig.php"

		printf "Migration created\n"
	    printf "see the results in the 'system/migrations' directory\n"
	else
		printf "Migration already exists\n"
	fi
fi
