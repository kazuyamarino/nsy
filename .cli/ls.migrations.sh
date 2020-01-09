#!/bin/bash
show_migration() {
	count=`ls -1 system/migrations/*.php 2>/dev/null | wc -l`
	if [ $count != 0 ]; then
		# List of migrations class
		printf "List of 'migrations' class :\n"
		printf "==========================\n"
		ls -d system/migrations/*.php | awk -F"/" '{print NR".", $NF}'
		printf "==========================\n"
	else
		# List of migrations class
		printf "List of 'migrations' class :\n"
		printf "==========================\n"
		printf "No such file or directory\n"
		printf "==========================\n"
	fi
}
