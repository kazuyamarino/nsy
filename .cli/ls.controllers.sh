#!/bin/bash

if [ -z $1 ]
then
	printf "Mode undefined, must be hmvc or mvc\n"
	printf "There must be 'show_controllers <mode>'\n"
elif [ -n $1 ]
then
	mode=$1

	case $mode in
		"mvc")
			# List of 'mvc' controllers
			printf "List of '$mode' controllers :\n"
			printf "==========================\n"
			ls -1 ./system/controllers/* | awk -F"/" '{print NR".", $NF}'
			printf "==========================\n"
		;;
		"hmvc")
			if [ -z $2 ]
			then
				printf "Module name undefined\n"
				printf "There must be 'show_controllers <mode> <module>'\n"
			elif [ -n $2 ]
			then
				dirname=$2

				# if directory doesnt exist
				if [ ! -d ./system/modules/$dirname ]
				then
					printf "Module doesn't exists\n"
				else # if exist
					# List of 'hmvc' controllers
					printf "List of '$mode' controllers :\n"
					printf "==========================\n"
					ls -1 ./system/modules/$dirname/controllers/* | awk -F"/" '{print NR".", $NF}'
					printf "==========================\n"
				fi
			fi
		;;
		*)
			printf "Hmm, seems NSY never used that mode.\n"
		;;
	esac
fi
