#!/bin/bash
# List of modules
printf "List of modules :\n"
printf "==========================\n"
ls -d ../System/Modules/* | awk -F"/" '{print NR".", $NF}'
printf "==========================\n"
