#!/bin/bash
run_dump_mysql() {
	local database="$1" user="$2" pass="$3" table="$4"

	if [ -z "$database" ] || [ -z "$user" ]; then
		printf "Database name, username, & password undefined\n"
		printf "It should be like this\n"
		printf "'dump:mysql [database name] [username] [password]'\n"
		printf "or\n"
		printf "'dump:mysql [database name] [username] [password] [table name]'\n"
		return 1
	fi

	if ! command -v mysqldump >/dev/null 2>&1; then
		printf "mysqldump not found in PATH\n"
		return 1
	fi

	local file="mig.$(date +"%Y%m%d_%H%M%S").sql"
	local dump_dir="$NSY_ROOT_DIR/dump"
	mkdir -p "$dump_dir"

	# MYSQL_PWD avoids exposing the password in the process list (unlike -p<pass>)
	if [ -n "$table" ]; then
		MYSQL_PWD="$pass" mysqldump -u "$user" "$database" "$table" > "$dump_dir/$file"
	else
		MYSQL_PWD="$pass" mysqldump -u "$user" "$database" > "$dump_dir/$file"
	fi

	if [ $? -eq 0 ]; then
		printf "%s was created:\n" "$file"
		printf "see the results in the 'dump' directory\n"
	else
		printf "Database dump failed\n"
		rm -f "$dump_dir/$file"
		return 1
	fi
}
