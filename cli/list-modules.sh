#!/bin/bash
# List of directory before enter controller name
echo "List of modules :"
echo "=========================="
for dir in ./../System/Modules/*
do
  	echo "* ${dir##*/}"
done
echo "=========================="
