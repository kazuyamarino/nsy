#!/bin/bash

# List of modules
printf "List of 'hmvc' modules :\n"
printf "==========================\n"
ls -d system/modules/* | awk -F"/" '{print NR".", $NF}'
printf "==========================\n"
