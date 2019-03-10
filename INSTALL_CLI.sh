#!/bin/bash
if [ ! -e ~/nsy.cli.sh ]
then
	# Create 'nsy.cli.sh' in root folder
	cp .cli/nsy.cli.sh ~/nsy.cli.sh
	echo 'source ~/nsy.cli.sh' >> ~/.bashrc

	printf "NSY CLI installed\n"
	printf "Please close the Terminal & reopen it again\n"
else
	printf "NSI CLI already installed\n"
fi
