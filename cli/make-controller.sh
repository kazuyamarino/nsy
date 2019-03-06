#!/bin/bash
# List of directory before enter controller name
echo "List of modules :"
echo "=========================="
for dir in ./../System/Modules/*
do
  	echo "* ${dir##*/}"
done
echo "=========================="
echo "Enter module name :"
read dirname

# if directory doesnt exist
if [ ! -d ./../System/Modules/$dirname ]
then
	echo
	echo "Module doesn't exists"
else # if exist
	# List of controllers after enter module name
	echo
	echo "List of controllers :"
	echo "=========================="
	for file in ./../System/Modules/$dirname/Controllers/*
	do
	  	echo "* ${file##*/}"
	done
	echo "=========================="
	echo "Enter controller name :"
	read conname

	if [ ! -e ./../System/Modules/$dirname/Controllers/"$conname.php" ]
	then
		cp files/"module-controller.php" ./../System/Modules/$dirname/Controllers/"$conname.php"
		sed -i "s/module_controller/$conname/g" ./../System/Modules/$dirname/Controllers/"$conname.php"
		echo
	    echo "Controller created"
	else
		echo
		echo "Controller already exists"
	fi
fi
