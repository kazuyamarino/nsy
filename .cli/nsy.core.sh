#!/bin/bash
. .cli/ls.modules.sh
. .cli/ls.controllers.sh
. .cli/ls.models.sh
. .cli/ls.migrations.sh
. .cli/nsy.cliinstall.sh
. .cli/nsy.greeting.sh
. .cli/nsy.help.sh
. .cli/nsy.dumpautoload.sh
. .cli/nsy.mysqldumpdb.sh
. .cli/nsy.settingup.sh
. .cli/mk.controller.sh
. .cli/mk.migration.sh
. .cli/mk.model.sh
. .cli/mk.module.sh
. .cli/mk.route.sh
. .cli/mk.middleware.sh
. .cli/run.migration.sh

if [ -z $1 ]; then
	printf "Command does not exist or undefined\n"
	printf "It should be like this 'nsy [command]'\n"
else
	if [ $1 = "show:module" ]; then
		show_module
	elif [ $1 = "show:controller" ]; then
		show_controller $2 $3
	elif [ $1 = "show:model" ]; then
		show_model $2 $3
	elif [ $1 = "show:migrate" ]; then
		show_migration
	elif [ $1 = "--install" ]; then
		run_install
	elif [ $1 = "--hello" ]; then
		run_hello
	elif [ $1 = "--help" ]; then
		run_help
	elif [ $1 = "--setup" ]; then
		run_setup
	elif [ $1 = "dump:autoload" ]; then
		run_dump_autoload
	elif [ $1 = "dump:mysql" ]; then
		run_dump_mysql $2 $3 $4 $5
	elif [ $1 = "make:controller" ]; then
		make_controller $2 $3 $4
	elif [ $1 = "make:model" ]; then
		make_model $2 $3 $4
	elif [ $1 = "make:migrate" ]; then
		make_migration $2
	elif [ $1 = "make:module" ]; then
		make_module $2
	elif [ $1 = "make:route" ]; then
		make_route $2
	elif [ $1 = "make:after-middleware" ]; then
		make_after_middleware $2
	elif [ $1 = "make:before-middleware" ]; then
		make_before_middleware $2
	elif [ $1 = "run:migrate" ]; then
		run_migration $2
	else
		printf "NSY Command $1 : not found\n"
	fi
fi
