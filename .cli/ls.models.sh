#!/bin/bash
show_model() {
	if [ -z $1 ]
	then
		printf "Mode undefined, must be hmvc or mvc\n"
		printf "It should be like this 'show:model <mode>'\n"
	elif [ -n $1 ]
	then
		mode=$1

		case $mode in
			"mvc")
				# List of 'mvc' models
				printf "List of '$mode' models :\n"
				printf "==========================\n"
				ls -1 ./system/models/* | awk -F"/" '{print NR".", $NF}'
				printf "==========================\n"
			;;
			"hmvc")
				if [ -z $2 ]
				then
					printf "Module name undefined\n"
					printf "It should be like this 'show:model $mode <module>'\n"
				elif [ -n $2 ]
				then
					dirname=$2

					# if directory doesnt exist
					if [ ! -d ./system/modules/$dirname ]
					then
						printf "Module doesn't exists\n"
					else # if exist
						# List of 'hmvc' models;
						printf "List of '$mode' models :\n"
						printf "==========================\n"
						ls -1 ./system/modules/$dirname/models/* | awk -F"/" '{print NR".", $NF}'
						printf "==========================\n"
					fi
				fi
			;;
			*)
				printf "Hmm, it seems NSY never use that mode.\n"
			;;
		esac
	fi
}
