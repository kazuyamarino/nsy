#!/bin/bash
echo "Enter directory name :"
read dirname

if [ ! -d ./../System/Modules/"$dirname" ]
then
    mkdir ./../System/Modules/$dirname
    mkdir ./../System/Modules/$dirname/Controllers
    mkdir ./../System/Modules/$dirname/Models
    mkdir ./../System/Modules/$dirname/Views
	chmod -R 775 ./../System/Modules/$dirname
	chmod -R 775 ./../System/Modules/$dirname/Controllers
	chmod -R 775 ./../System/Modules/$dirname/Models
	chmod -R 775 ./../System/Modules/$dirname/Views
	echo
    echo "Module directory created"
    echo "see the results in the System/Modules folder"
else
	echo
    echo "Module directory already exists"
fi
