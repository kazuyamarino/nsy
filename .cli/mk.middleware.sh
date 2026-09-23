#!/bin/bash
make_middleware() {
	local name="$1"

	if [ -z "$name" ]; then
		printf "Middleware name undefined\n"
		printf "It should be like this 'make:middleware [middleware-name]'\n"
		return 1
	fi

	local dest="$NSY_ROOT_DIR/System/Middlewares/$name.php"
	if [ -e "$dest" ]; then
		printf "Middleware already exists\n"
		return 0
	fi

	mkdir -p "$NSY_ROOT_DIR/System/Middlewares"
	cp "$NSY_ROOT_DIR/.cli/tmp/middleware_tmp.php" "$dest"
	sed_inplace "s/middleware_tmp/$name/g" "$dest"

	printf "Middleware created: System/Middlewares/%s.php\n" "$name"
	printf "Note: NSY has no auto middleware pipeline — call it explicitly from a route/controller.\n"
	nsy_dump_autoload
}
