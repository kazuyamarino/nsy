#!/bin/bash
show_controller() {
	if [ -z $1 ]
	then
		printf "Mode undefined, must be hmvc or mvc\n"
		printf "It should be like this 'show:controller [mode]'\n"
	elif [ -n $1 ]
	then
		mode=$1

		case $mode in
			"mvc")
				# List of 'mvc' controllers
				printf "List of '$mode' controllers :\n"
				printf "==========================\n"
				ls -1 ./System/Controllers/*.php | awk -F"/" '{print NR".", $NF}'
				printf "==========================\n"
			;;
			"hmvc")
				if [ -z $2 ]
				then
					printf "Module name undefined\n"
					printf "It should be like this 'show:controller $mode [module-name]'\n"
				elif [ -n $2 ]
				then
					dirname=$2

					# if directory doesnt exist
					if [ ! -d ./System/Modules/$dirname ]
					then
						printf "Module doesn't exists\n"
					else # if exist
						count=`ls -1 ./System/Modules/$dirname/Controllers/*.php 2>/dev/null | wc -l`
						if [ $count != 0 ]; then
							# List of 'hmvc' controllers
							printf "List of '$mode' controllers :\n"
							printf "==========================\n"
							ls -1 ./System/Modules/$dirname/Controllers/*.php | awk -F"/" '{print NR".", $NF}'
							printf "==========================\n"
						else
							# List of empty 'hmvc' controllers
							printf "List of '$mode' controllers :\n"
							printf "==========================\n"
							printf "No such file or directory\n"
							printf "==========================\n"
						fi
					fi
				fi
			;;
			*)
				printf "Hmm, it seems NSY never use that mode.\n"
			;;
		esac
	fi
}
