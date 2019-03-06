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
	for file in ./../System/Modules/$dirname/Models/*
	do
	  	echo "* ${file##*/}"
	done
	echo "=========================="
	echo "Enter model name :"
	read modname

	if [ ! -e ./../System/Modules/$dirname/Models/$modname.php ]
	then
		cp files/"module-model.php" ./../System/Modules/$dirname/Models/"$modname.php"
		sed -i "s/module_model/$modname/g" ./../System/Modules/$dirname/Models/"$modname.php"
		echo
	    echo "Model created"
	else
		echo
		echo "Model already exists"
	fi
fi
