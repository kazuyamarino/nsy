#!/bin/bash
make_migration() {
	local mig="$1"

	if [ -z "$mig" ]; then
		printf "Migration name or table name undefined\n"
		printf "It should be like this 'make:migrate [migration-name]'\n"
		return 1
	fi

	# Refuse if a non-timestamped file with the exact name already exists
	if [ -e "$NSY_ROOT_DIR/System/Migrations/$mig.php" ]; then
		printf "Migration '%s.php' already exists\n" "$mig"
		return 0
	fi

	local stamp
	stamp="$(date +'_%d%m%Y_%H%M%S')"
	local filename="${mig}${stamp}.php"
	local dest="$NSY_ROOT_DIR/System/Migrations/$filename"

	mkdir -p "$NSY_ROOT_DIR/System/Migrations"
	cp "$NSY_ROOT_DIR/.cli/tmp/mig_tmp.php" "$dest"
	# Class name must equal the file basename for the migration runner
	sed_inplace "s/mig_tmp_class/${mig}${stamp}/g" "$dest"
	sed_inplace "s/mig_tmp/$mig/g" "$dest"

	printf "Migration created: System/Migrations/%s\n" "$filename"
}
