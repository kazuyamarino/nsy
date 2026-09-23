#!/bin/bash
make_model() {
	local mode="$1" arg1="$2" arg2="$3"

	if [ -z "$mode" ]; then
		printf "Mode undefined, must be hmvc or mvc\n"
		printf "It should be like this 'make:model [mode]'\n"
		return 1
	fi

	case "$mode" in
		"mvc")
			local mdlname="$arg1"
			if [ -z "$mdlname" ]; then
				printf "Model name undefined\n"
				printf "It should be like this 'make:model mvc [model-name]'\n"
				return 1
			fi

			local dest="$NSY_ROOT_DIR/System/Apps/General/Models/$mdlname.php"
			if [ -e "$dest" ]; then
				printf "Model already exists\n"
				return 0
			fi

			mkdir -p "$NSY_ROOT_DIR/System/Apps/General/Models"
			cp "$NSY_ROOT_DIR/.cli/tmp/cm_mdl.php" "$dest"
			sed_inplace "s/cm_mdl/$mdlname/g" "$dest"

			printf "Model created: System/Apps/General/Models/%s.php\n" "$mdlname"
			nsy_dump_autoload
			;;
		"hmvc")
			local module="$arg1" mdlname="$arg2"
			if [ -z "$module" ]; then
				printf "Module name undefined\n"
				printf "It should be like this 'make:model hmvc [module-name] [model-name]'\n"
				return 1
			fi
			if [ ! -d "$NSY_ROOT_DIR/System/Apps/Modules/$module" ]; then
				printf "Module '%s' doesn't exist. Create it first: nsy make:module %s\n" "$module" "$module"
				return 1
			fi
			if [ -z "$mdlname" ]; then
				printf "Model name undefined\n"
				printf "It should be like this 'make:model hmvc $module [model-name]'\n"
				return 1
			fi

			local dest="$NSY_ROOT_DIR/System/Apps/Modules/$module/Models/$mdlname.php"
			if [ -e "$dest" ]; then
				printf "Model already exists\n"
				return 0
			fi

			mkdir -p "$NSY_ROOT_DIR/System/Apps/Modules/$module/Models"
			cp "$NSY_ROOT_DIR/.cli/tmp/md_mdl.php" "$dest"
			sed_inplace "s/md_mdl/$mdlname/g" "$dest"
			sed_inplace "s/ModuleName/$module/g" "$dest"

			printf "Model created: System/Apps/Modules/%s/Models/%s.php\n" "$module" "$mdlname"
			nsy_dump_autoload
			;;
		*)
			printf "Hmm, it seems NSY never use that mode.\n"
			return 1
			;;
	esac
}
