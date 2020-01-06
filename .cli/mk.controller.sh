#!/bin/bash

if [ -z $1 ]
then
	printf "Mode undefined, must be hmvc or mvc\n"
	printf "There must be 'controller <mode>'\n"
elif [ -n $1 ]
then
	mode=$1

	case $mode in
		"mvc")
			if [ -z $2 ]
			then
				printf "Controller name undefined\n"
				printf "There must be 'controller <mode> <controller-name>'\n"
			elif [ -n $2 ]
			then
				conname=$2

				# Create 'mvc' controller
				if [ ! -e ./system/controllers/"$conname.php" ]
				then
					# Create 'mvc' controller
					cp .cli/tmp/cm_ctrl.php ./system/controllers/"$conname.php"
					sed -i "s/cm_ctrl/$conname/g" ./system/controllers/"$conname.php"

					printf "Controller created\n"
					printf "see the results in the 'system/controllers' directory\n"
				else
					printf "Controller already exists\n"
				fi
			fi
		;;
		"hmvc")
			if [ -z $2 ]
			then
				printf "Module name undefined\n"
				printf "There must be 'controller <mode> <module>'\n"
			elif [ -n $2 ]
			then
				dirname=$2

				# if directory doesnt exist
				if [ ! -d ./system/modules/$dirname ]
				then
					printf "Module doesn't exists\n"
				else # if exist
					if [ -z $3 ]
					then
						printf "Controller name undefined\n"
						printf "There must be 'controller <mode> <module> <controller-name>'\n"
					elif [ -n $3 ]
					then
						conname=$3

						# Create 'hmvc' controller
						if [ ! -e ./system/modules/$dirname/controllers/"$conname.php" ]
						then
							cp .cli/tmp/md_ctrl.php ./system/modules/$dirname/controllers/"$conname.php"
							sed -i "s/md_ctrl/$conname/g" ./system/modules/$dirname/controllers/"$conname.php"
							sed -i "s/ModuleName/"$(echo "$dirname" |sed -e "s/\b\(.\)/\u\1/g")"/g" ./system/modules/$dirname/controllers/"$conname.php"

						    printf "Controller created\n"
							printf "see the results in the 'system/modules/$dirname/controllers' directory\n"
						else
							printf "Controller already exists\n"
						fi
					fi
				fi
			fi
		;;
		*)
			printf "Hmm, seems NSY never used that mode.\n"
		;;
	esac
fi
