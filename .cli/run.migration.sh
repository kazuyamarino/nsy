#!/bin/bash
run_migration() {
	if [ -z $1 ]
	then
		printf "Migration [command options] undefined\n"
		printf "It should be like this 'run:migration [command options]'\n"
	elif [ -n $1 ]
	then
		entry=$1

		project_name=$(basename $PWD)
		hs_name='127.0.0.1'
		
		case $entry in
			"all")
				search_dir=./System/Migrations/*.php
				for entry in $search_dir
				do
					mig_name=$(basename $entry)

					wget -q -O MIGRATIONLOG http://$hs_name/$project_name/migdown=${mig_name%.*}
					wget -q -O MIGRATIONLOG http://$hs_name/$project_name/migup=${mig_name%.*}

					echo "Successfully migrating down "${mig_name%.*}
					echo "Successfully migrating up "${mig_name%.*}
				done

				printf "\nAll migration classes run successfully\n"
				printf "Please see the results in the 'Database'\n"
			;;
			"list")
				search_dir=./System/Migrations/*.php
				arr_mig_name=()
				for entry in $search_dir
				do
					mig_name=($(basename $entry))
					arr_mig_name+=("$mig_name")
				done

				for i in "${!arr_mig_name[@]}"; do
					printf "%s) %s\n" $(($i + 1)) "${arr_mig_name[$i]}"
				done
				printf 'Select a migration class from the above list: '
				IFS= read -r opt
				if (( (opt > 0) && (opt <= "${#arr_mig_name[@]}") )); then
					
					wget -q -O MIGRATIONLOG http://$hs_name/$project_name/migdown=${arr_mig_name[$((opt - 1))]%.*}
					wget -q -O MIGRATIONLOG http://$hs_name/$project_name/migup=${arr_mig_name[$((opt - 1))]%.*}

					printf "\n"
					echo "Successfully migrating down "${arr_mig_name[$((opt - 1))]%.*}
					echo "Successfully migrating up "${arr_mig_name[$((opt - 1))]%.*}

					printf "\nMigration class run successfully\n"
					printf "Please see the results in the 'Database'\n"
				else
					printf "\nMigration class not selected or not found\n"
				fi
			;;
			*)
				if [ ! -e ./System/Migrations/"$entry.php" ]
				then
					printf "Migration warning\n"
					printf "No Migration found\n"
				else
					mig_name=$(basename $entry)

					wget -q -O MIGRATIONLOG http://$hs_name/$project_name/migdown=${mig_name%.*}
					wget -q -O MIGRATIONLOG http://$hs_name/$project_name/migup=${mig_name%.*}

					echo "Successfully migrating down "${mig_name%.*}
					echo "Successfully migrating up "${mig_name%.*}

					printf "\nMigration class run successfully\n"
					printf "Please see the results in the 'Database'\n"
				fi
			;;
		esac
	fi
}
