#!/bin/bash
if [ -z $1 ]
then
	printf "Mode undefined, must be hmvc or mvc\n"
elif [ -n $1 ]
then
	mode=$1

	case $mode in
		"mvc")
			# List of 'mvc' controllers
			printf "List of '$mode' controllers :\n"
			printf "==========================\n"
			ls -1 ./../System/Controllers/* | awk -F"/" '{print NR".", $NF}'
			printf "==========================\n"
		;;
		"hmvc")
			if [ -z $2 ]
			then
				printf "Module name undefined\n"
			elif [ -n $2 ]
			then
				dirname=$2

				# if directory doesnt exist
				if [ ! -d ./../System/Modules/$dirname ]
				then
					printf "Module doesn't exists\n"
				else # if exist
					# List of 'hmvc' controllers
					printf "List of '$mode' controllers :\n"
					printf "==========================\n"
					ls -1 ./../System/Modules/$dirname/Controllers/* | awk -F"/" '{print NR".", $NF}'
					printf "==========================\n"
				fi
			fi
		;;
		*)
			printf "Hmm, seems NSY never used that mode.\n"
		;;
	esac
fi
