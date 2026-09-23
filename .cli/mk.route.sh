#!/bin/bash
make_route() {
	local route="$1"

	if [ -z "$route" ]; then
		printf "Route name undefined\n"
		printf "It should be like this 'make:route [route-name]'\n"
		return 1
	fi

	local dest="$NSY_ROOT_DIR/System/Routes/$route.php"
	if [ -e "$dest" ]; then
		printf "Route already exists\n"
		return 0
	fi

	mkdir -p "$NSY_ROOT_DIR/System/Routes"
	cp "$NSY_ROOT_DIR/.cli/tmp/route_tmp.php" "$dest"

	printf "Route created: System/Routes/%s.php\n" "$route"
	printf "It is auto-loaded (no registration needed). Edit the new file to add routes.\n"
}
