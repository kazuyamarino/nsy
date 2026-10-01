#!/bin/bash
make_factory() {
	local factory="$1"

	if [ -z "$factory" ]; then
		printf "Factory name undefined\n"
		printf "It should be like this 'make:factory [factory-name]'\n"
		return 1
	fi

	# Accept both 'UserFactory' and 'UserFactory.php'
	factory="${factory%.php}"

	if ! nsy_valid_name "$factory"; then
		printf "Invalid factory name '%s' (use letters, digits, underscore only)\n" "$factory"
		return 1
	fi

	local dest="$NSY_ROOT_DIR/System/Factories/$factory.php"
	if [ -e "$dest" ]; then
		printf "Factory 'System/Factories/%s.php' already exists\n" "$factory"
		return 0
	fi

	mkdir -p "$NSY_ROOT_DIR/System/Factories"
	cp "$NSY_ROOT_DIR/.cli/tmp/factory_tmp.php" "$dest"
	# Class name must equal the file basename for PSR-4 autoloading
	sed_inplace "s/factory_tmp_class/${factory}/g" "$dest"
	sed_inplace "s/factory_tmp/${factory}/g" "$dest"

	printf "Factory created: System/Factories/%s.php\n" "$factory"
}
