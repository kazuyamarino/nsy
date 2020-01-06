#!/bin/bash

if [ ! -e ~/nsy.cli.sh ]
then
	# Create 'nsy.cli.sh' in root folder
	cp .cli/nsy.cli.sh ~/nsy.cli.sh
	cp .cli/reloader.sh ~/reloader.sh
	echo 'source ~/nsy.cli.sh' >> ~/.bashrc
	echo 'test -f ~/.bashrc && . ~/.bashrc' >> ~/.bash_profile
	echo 'test -f ~/.profile && . ~/.profile' >> ~/.bash_profile

	printf "Please wait...\n"
	sleep 3

	printf "NSY CLI installed\n"
	printf "Please close the Terminal & reopen it again\n"
	printf "Or\n"
	printf "Please run 'source ~/reloader.sh'\n"
else
	cp .cli/nsy.cli.sh ~/nsy.cli.sh
	cp .cli/reloader.sh ~/reloader.sh

	printf "Please wait...\n"
	sleep 3

	printf "NSI CLI already installed and has been updated\n"
	printf "Please close the Terminal & reopen it again\n"
	printf "Or\n"
	printf "Please run 'source ~/reloader.sh'\n"
fi
