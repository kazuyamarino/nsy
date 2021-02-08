#!/bin/bash
make_model() {
	if [ -z $1 ]
	then
		printf "Mode undefined, must be hmvc or mvc\n"
		printf "It should be like this 'make:model <mode>'\n"
	elif [ -n $1 ]
	then
		mode=$1

		case $mode in
			"mvc")
				if [ -z $2 ]
				then
					printf "Model name undefined\n"
					printf "It should be like this 'make:model $mode <model-name>'\n"
				elif [ -n $2 ]
				then
					conname=$2

					# Create 'mvc' model
					if [ ! -e ./System/Models/"$conname.php" ]
					then
						# Create 'mvc' model
						cp .cli/tmp/cm_mdl.php ./System/Models/"$conname.php"
						sed -i "s/cm_mdl/$conname/g" ./System/Models/"$conname.php"

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
					printf "It should be like this 'make:model $mode <module>'\n"
				elif [ -n $2 ]
				then
					dirname=$2

					# if directory doesnt exist
					if [ ! -d ./System/Modules/$dirname ]
					then
						printf "Module doesn't exists\n"
					else # if exist
						if [ -z $3 ]
						then
							printf "Model name undefined\n"
							printf "It should be like this 'make:model $mode $dirname <model-name>'\n"
						elif [ -n $3 ]
						then
							conname=$3

							# Create 'hmvc' model
							if [ ! -e ./System/Modules/$dirname/Models/"$conname.php" ]
							then
								cp .cli/tmp/md_mdl.php ./System/Modules/$dirname/Models/"$conname.php"
								sed -i "s/md_mdl/$conname/g" ./System/Modules/$dirname/Models/"$conname.php"
								sed -i "s/ModuleName/"$(echo "$dirname" |sed -e "s/\b\(.\)/\u\1/g")"/g" ./System/Modules/$dirname/Models/"$conname.php"

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
				printf "Hmm, it seems NSY never use that mode.\n"
			;;
		esac
	fi
}
