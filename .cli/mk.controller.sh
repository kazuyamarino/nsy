#!/bin/bash
make_controller() {
	local mode="$1" arg1="$2" arg2="$3"

	if [ -z "$mode" ]; then
		printf "Mode undefined, must be hmvc or mvc\n"
		printf "It should be like this 'make:controller [mode]'\n"
		return 1
	fi

	case "$mode" in
		"mvc")
			local conname="$arg1"
			if [ -z "$conname" ]; then
				printf "Controller name undefined\n"
				printf "It should be like this 'make:controller mvc [controller-name]'\n"
				return 1
			fi

			local dest="$NSY_ROOT_DIR/System/Apps/General/Controllers/$conname.php"
			if [ -e "$dest" ]; then
				printf "Controller already exists\n"
				return 0
			fi

			mkdir -p "$NSY_ROOT_DIR/System/Apps/General/Controllers"
			cp "$NSY_ROOT_DIR/.cli/tmp/cm_ctrl.php" "$dest"
			sed_inplace "s/cm_ctrl/$conname/g" "$dest"

			printf "Controller created: System/Apps/General/Controllers/%s.php\n" "$conname"
			nsy_dump_autoload
			;;
		"hmvc")
			local module="$arg1" conname="$arg2"
			if [ -z "$module" ]; then
				printf "Module name undefined\n"
				printf "It should be like this 'make:controller hmvc [module-name] [controller-name]'\n"
				return 1
			fi
			if [ ! -d "$NSY_ROOT_DIR/System/Apps/Modules/$module" ]; then
				printf "Module '%s' doesn't exist. Create it first: nsy make:module %s\n" "$module" "$module"
				return 1
			fi
			if [ -z "$conname" ]; then
				printf "Controller name undefined\n"
				printf "It should be like this 'make:controller hmvc $module [controller-name]'\n"
				return 1
			fi

			local dest="$NSY_ROOT_DIR/System/Apps/Modules/$module/Controllers/$conname.php"
			if [ -e "$dest" ]; then
				printf "Controller already exists\n"
				return 0
			fi

			mkdir -p "$NSY_ROOT_DIR/System/Apps/Modules/$module/Controllers"
			cp "$NSY_ROOT_DIR/.cli/tmp/md_ctrl.php" "$dest"
			sed_inplace "s/md_ctrl/$conname/g" "$dest"
			sed_inplace "s/ModuleName/$module/g" "$dest"

			printf "Controller created: System/Apps/Modules/%s/Controllers/%s.php\n" "$module" "$conname"
			nsy_dump_autoload
			;;
		*)
			printf "Hmm, it seems NSY never use that mode.\n"
			return 1
			;;
	esac
}
