#!/bin/bash
make_seeder() {
	local seeder="$1"

	if [ -z "$seeder" ]; then
		printf "Seeder name undefined\n"
		printf "It should be like this 'make:seeder [seeder-name]'\n"
		return 1
	fi

	# Accept both 'UserSeeder' and 'UserSeeder.php'
	seeder="${seeder%.php}"

	local dest="$NSY_ROOT_DIR/System/Seeders/$seeder.php"
	if [ -e "$dest" ]; then
		printf "Seeder 'System/Seeders/%s.php' already exists\n" "$seeder"
		return 0
	fi

	mkdir -p "$NSY_ROOT_DIR/System/Seeders"
	cp "$NSY_ROOT_DIR/.cli/tmp/seeder_tmp.php" "$dest"
	# Class name must equal the file basename for PSR-4 autoloading
	sed_inplace "s/seeder_tmp_class/${seeder}/g" "$dest"
	sed_inplace "s/seeder_tmp/${seeder}/g" "$dest"

	printf "Seeder created: System/Seeders/%s.php\n" "$seeder"
}
