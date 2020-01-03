#!/bin/bash
if [ ! -e ~/nsy.cli.sh ]
then
	# Create 'nsy.cli.sh' in root folder
	cp .cli/nsy.cli.sh ~/nsy.cli.sh
	echo 'source ~/nsy.cli.sh' >> ~/.bashrc
	echo 'test -f ~/.bashrc && . ~/.bashrc' >> ~/.bash_profile
	echo 'test -f ~/.profile && . ~/.profile' >> ~/.bash_profile
	source ~/.bashrc

	printf "NSY CLI installed\n"
else
	cp .cli/nsy.cli.sh ~/nsy.cli.sh
	source ~/.bashrc
	printf "NSI CLI already installed and has been updated\n"
fi
