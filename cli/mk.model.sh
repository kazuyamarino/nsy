#!/bin/bash
if [ -z $1 ]
then
	printf "Mode undefined, must be hmvc or mvc\n"
elif [ -n $1 ]
then
	mode=$1

	case $mode in
		"mvc")
			if [ -z $2 ]
			then
				printf "Model name undefined\n"
			elif [ -n $2 ]
			then
				conname=$2

				# Create 'mvc' model
				if [ ! -e ./../System/Models/"$conname.php" ]
				then
					# Create 'mvc' model
					cp tmp/"cm_mdl.php" ./../System/Models/"$conname.php"
					sed -i "s/cm_mdl/$conname/g" ./../System/Models/"$conname.php"

					printf "Model created\n"
					printf "see the results in the 'System/Models' directory\n"
				else
					printf "Model already exists\n"
				fi
			fi
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
					if [ -z $3 ]
					then
						printf "Model name undefined\n"
					elif [ -n $3 ]
					then
						conname=$3

						# Create 'hmvc' model
						if [ ! -e ./../System/Modules/$dirname/Models/"$conname.php" ]
						then
							cp tmp/"md_mdl.php" ./../System/Modules/$dirname/Models/"$conname.php"
							sed -i "s/md_mdl/$conname/g" ./../System/Modules/$dirname/Models/"$conname.php"

						    printf "Model created\n"
							printf "see the results in the 'System/Modules/$dirname/Models' directory\n"
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
