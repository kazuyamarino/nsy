#!/bin/bash
# NSY CLI entrypoint — resolves its own location so it works from any CWD

CLI_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
NSY_ROOT_DIR="$(cd "$CLI_DIR/.." && pwd)"
export NSY_ROOT_DIR
cd "$NSY_ROOT_DIR" || exit 1

. "$CLI_DIR/lib.sh"
. "$CLI_DIR/ls.modules.sh"
. "$CLI_DIR/ls.controllers.sh"
. "$CLI_DIR/ls.models.sh"
. "$CLI_DIR/ls.migrations.sh"
. "$CLI_DIR/nsy.cliinstall.sh"
. "$CLI_DIR/nsy.greeting.sh"
. "$CLI_DIR/nsy.help.sh"
. "$CLI_DIR/nsy.version.sh"
. "$CLI_DIR/nsy.dumpautoload.sh"
. "$CLI_DIR/nsy.mysqldumpdb.sh"
. "$CLI_DIR/nsy.settingup.sh"
. "$CLI_DIR/nsy.serve.sh"
. "$CLI_DIR/mk.controller.sh"
. "$CLI_DIR/mk.migration.sh"
. "$CLI_DIR/mk.model.sh"
. "$CLI_DIR/mk.module.sh"
. "$CLI_DIR/mk.route.sh"
. "$CLI_DIR/mk.view.sh"
. "$CLI_DIR/mk.middleware.sh"
. "$CLI_DIR/run.migration.sh"

if [ -z "$1" ]; then
	printf "Command does not exist or undefined\n"
	printf "It should be like this 'nsy [command]'\n"
	printf "Run 'nsy --help' for the command list\n"
	exit 0
fi

case "$1" in
	show:module|show:modules) show_module ;;
	show:controller)   show_controller "$2" "$3" ;;
	show:model)        show_model "$2" "$3" ;;
	show:migrate)      show_migration ;;
	--install)         run_install ;;
	--hello)           run_hello ;;
	--help)            run_help ;;
	--version|-v)      run_version ;;
	--setup)           run_setup ;;
	serve)             run_serve "$2" "$3" ;;
	dump:autoload)     run_dump_autoload ;;
	dump:mysql)        run_dump_mysql "$2" "$3" "$4" "$5" ;;
	make:controller)   make_controller "$2" "$3" "$4" ;;
	make:model)        make_model "$2" "$3" "$4" ;;
	make:migrate)      make_migration "$2" ;;
	make:module)       make_module "$2" ;;
	make:route)        make_route "$2" ;;
	make:view)         make_view "$2" "$3" "$4" ;;
	make:middleware)   make_middleware "$2" ;;
	run:migrate)       run_migration "$2" "$3" ;;
	*)
		printf "NSY Command %s : not found\n" "$1"
		printf "Run 'nsy --help' for the command list\n"
		exit 1
		;;
esac
