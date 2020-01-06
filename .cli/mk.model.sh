#!/bin/bash

if [ -z $1 ]
then
	printf "Mode undefined, must be hmvc or mvc\n"
	printf "There must be 'model <mode>'\n"
elif [ -n $1 ]
then
	mode=$1

	case $mode in
		"mvc")
			if [ -z $2 ]
			then
				printf "Model name undefined\n"
				printf "There must be 'model <mode> <model-name>'\n"
			elif [ -n $2 ]
			then
				conname=$2

				# Create 'mvc' model
				if [ ! -e ./system/models/"$conname.php" ]
				then
					# Create 'mvc' model
					cp .cli/tmp/cm_mdl.php ./system/models/"$conname.php"
					sed -i "s/cm_mdl/$conname/g" ./system/models/"$conname.php"

					printf "Model created\n"
					printf "see the results in the 'system/models' directory\n"
				else
					printf "Model already exists\n"
				fi
			fi
		;;
		"hmvc")
			if [ -z $2 ]
			then
				printf "Module name undefined\n"
				printf "There must be 'model <mode> <module>'\n"
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
						printf "Model name undefined\n"
						printf "There must be 'model <mode> <module> <model-name>'\n"
					elif [ -n $3 ]
					then
						conname=$3

						# Create 'hmvc' model
						if [ ! -e ./system/modules/$dirname/models/"$conname.php" ]
						then
							cp .cli/tmp/md_mdl.php ./system/modules/$dirname/models/"$conname.php"
							sed -i "s/md_mdl/$conname/g" ./system/modules/$dirname/models/"$conname.php"
							sed -i "s/ModuleName/"$(echo "$dirname" |sed -e "s/\b\(.\)/\u\1/g")"/g" ./system/modules/$dirname/models/"$conname.php"

						    printf "Model created\n"
							printf "see the results in the 'system/modules/$dirname/models' directory\n"
						else
							printf "Model already exists\n"
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
