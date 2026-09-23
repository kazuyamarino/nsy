#!/bin/bash
make_view() {
	local mode="$1" arg1="$2" arg2="$3"

	if [ -z "$mode" ]; then
		printf "Mode undefined, must be hmvc or mvc\n"
		printf "It should be like this 'make:view [mode]'\n"
		return 1
	fi

	case "$mode" in
		"mvc")
			local viewname="${arg1%.php}"
			if [ -z "$viewname" ]; then
				printf "View name undefined\n"
				printf "It should be like this 'make:view mvc [view-name]'\n"
				return 1
			fi

			local dest="$NSY_ROOT_DIR/System/Apps/General/Views/$viewname.php"
			if [ -e "$dest" ]; then
				printf "View already exists\n"
				return 0
			fi

			mkdir -p "$NSY_ROOT_DIR/System/Apps/General/Views"
			cp "$NSY_ROOT_DIR/.cli/tmp/cm_view.php" "$dest"
			sed_inplace "s/cm_view/$viewname/g" "$dest"

			printf "View created: System/Apps/General/Views/%s.php\n" "$viewname"
			printf "Render it with: Load::view(null, '%s', \$data);\n" "$viewname"
			;;
		"hmvc")
			local module="$arg1" viewname="${arg2%.php}"
			if [ -z "$module" ]; then
				printf "Module name undefined\n"
				printf "It should be like this 'make:view hmvc [module-name] [view-name]'\n"
				return 1
			fi
			if [ ! -d "$NSY_ROOT_DIR/System/Apps/Modules/$module" ]; then
				printf "Module '%s' doesn't exist. Create it first: nsy make:module %s\n" "$module" "$module"
				return 1
			fi
			if [ -z "$viewname" ]; then
				printf "View name undefined\n"
				printf "It should be like this 'make:view hmvc $module [view-name]'\n"
				return 1
			fi

			local dest="$NSY_ROOT_DIR/System/Apps/Modules/$module/Views/$viewname.php"
			if [ -e "$dest" ]; then
				printf "View already exists\n"
				return 0
			fi

			mkdir -p "$NSY_ROOT_DIR/System/Apps/Modules/$module/Views"
			cp "$NSY_ROOT_DIR/.cli/tmp/md_view.php" "$dest"
			sed_inplace "s/md_view/$viewname/g" "$dest"
			sed_inplace "s/ModuleName/$module/g" "$dest"

			printf "View created: System/Apps/Modules/%s/Views/%s.php\n" "$module" "$viewname"
			printf "Render it with: Load::view('%s', '%s', \$data);\n" "$module" "$viewname"
			;;
		*)
			printf "Hmm, it seems NSY never use that mode.\n"
			return 1
			;;
	esac
}
