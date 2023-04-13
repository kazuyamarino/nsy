#!/bin/bash
run_help() {
	printf "NAME :\n"
	printf " NSY Command Line Interface\n\n"
	printf "USAGE :\n"
	printf " * nsy [command] [command options] [arguments...]\n"
	printf " * nsy [command] [arguments...]\n\n"
	printf "VERSION :\n"
	printf " 1.0.5\n\n"
	printf "AUTHOR :\n"
	printf " Vikry Yuansah\n\n"
	printf "LINK :\n"
	printf " https://github.com/kazuyamarino/nsy-docs/blob/master/USERGUIDE.md#nsy-cli-command-line\n\n"

	printf "COMMANDS :\n"
	printf " --install\t\t Install or update NSY CLI\n"
	printf " --hello\t\t Show welcome message \n"
	printf " --help\t\t\t Show help\n"
	printf " --setup\t\t Setting up NSY for ready to use\n"
	printf " dump:autoload\t\t Call 'composer dump-autoload' with the optimize flag\n"
	printf " dump:mysql\t\t Dump mysql database\n"
	printf " show:module\t\t Show a list of HMVC modules directory\n"
	printf " show:controller\t Show a list of MVC or HMVC controller files\n"
	printf " show:model\t\t Show a list of MVC or HMVC model files\n"
	printf " show:migrate\t\t Show a list of migration class file\n"
	printf " make:controller\t Create a MVC or HMVC controller directory\n"
	printf " make:model\t\t Create a MVC or HMVC model directory\n"
	printf " make:migrate\t\t Create a migration class file\n"
	printf " run:migrate\t\t Create a migration class file\n"
	printf " make:module\t\t Run the migration class file\n"
	printf " make:after-middleware\t Create a after layer middleware\n"
	printf " make:before-middleware\t Create a before layer middleware\n\n"

	printf "COMMAND OPTIONS :\n"
	printf " mvc\t set the command value for mvc mode\n"
	printf " hmvc\t set the command value for hmvc mode\n"
	printf " all\t set the command value for running all migration classes\n"
	printf " list\t set the command value for show a list of migration classes\n\n"

	printf "ARGUMENTS... :\n"
	printf " mode\t\t\t set the mode (hmvc or mvc))\n"
	printf " controller-name\t create a controller name\n"
	printf " model-name\t\t create a model name\n"
	printf " module-name\t\t create a module\n"
	printf " migration-name\t\t create a migration class name\n"
	printf " middleware-name\t create a middleware name\n"
}
