#!/bin/bash
run_dump_mysql() {
	#----------------------------------------------------------
	# a simple mysql database backup script.
	# version 2, updated March 26, 2011.
	# modified by Vikry Yuansah 2019.
	# copyright 2011 alvin alexander, http://alvinalexander.com
	#----------------------------------------------------------
	# This work is licensed under a Creative Commons
	# Attribution-ShareAlike 3.0 Unported License;
	# see http://creativecommons.org/licenses/by-sa/3.0/
	# for more information.
	#----------------------------------------------------------

	if [ -z $1 ] || [ -z $2 ]
	then
		printf "Database name, username, & password undefined\n"
		printf "It should be like this\n"
		printf "'dump:mysql <database name> <username> <password>'\n"
		printf "or\n"
		printf "'dump:mysql <database name> <username> <password> <table name>'\n"
	elif [ -n $1 ]
	then
		if [ -z $4 ]
		then
			# (1) set up all the mysqldump variables
			FILE=mig.`date +"%Y%m%d_%H%M%S"`.sql
			DBSERVER=localhost
			DATABASE=$1
			USER=$2
			PASS=$3

			# (2) in case you run this more than once a day, remove the previous version of the file
			# unalias rm	2> /dev/null
			# rm ${FILE}	2> /dev/null
			# rm ${FILE}	2> /dev/null

			# (3) do the mysql database backup (dump)

			# use this command for a database server on a separate host:
			# mysqldump --opt --protocol=TCP --user=${USER} --password=${PASS} --host=${DBSERVER} ${DATABASE} > ${FILE}

			# use this command for a database server on localhost. add other options if need be.
			mysqldump -u ${USER} -p${PASS} ${DATABASE} > ./dump/${FILE}

			# (4) gzip the mysql database dump file
			# gzip $FILE

			# (5) show the user the result
			printf "${FILE} was created:\n"
			printf "see the results in the 'dump' directory\n"
		else
			# (1) set up all the mysqldump variables
			FILE=mig.`date +"%Y%m%d_%H%M%S"`.sql
			DBSERVER=localhost
			DATABASE=$1
			USER=$2
			PASS=$3
			TABLE=$4

			# (2) in case you run this more than once a day, remove the previous version of the file
			# unalias rm	2> /dev/null
			# rm ${FILE}	2> /dev/null
			# rm ${FILE}	2> /dev/null

			# (3) do the mysql database backup (dump)

			# use this command for a database server on a separate host:
			# mysqldump --opt --protocol=TCP --user=${USER} --password=${PASS} --host=${DBSERVER} ${DATABASE} > ${FILE}

			# use this command for a database server on localhost. add other options if need be.
			mysqldump -u ${USER} -p${PASS} ${DATABASE} ${TABLE} > ./dump/${FILE}

			# (4) gzip the mysql database dump file
			# gzip $FILE

			# (5) show the user the result
			printf "${FILE} was created:\n"
			printf "see the results in the 'dump' directory\n"
		fi
	fi
}
