#!/bin/bash
show_module() {
	count=`ls -1 system/modules/* 2>/dev/null | wc -l`
	if [ $count != 0 ]; then
		# List of modules
		printf "List of 'hmvc' modules :\n"
		printf "==========================\n"
		ls -d system/modules/* | awk -F"/" '{print NR".", $NF}'
		printf "==========================\n"
	else
		# List of empty modules
		printf "List of 'hmvc' modules :\n"
		printf "==========================\n"
		printf "No such file or directory\n"
		printf "==========================\n"
	fi
}
