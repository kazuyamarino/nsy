#!/bin/bash
# List of modules
printf "List of 'migrations' class :\n"
printf "==========================\n"
ls -d system/migrations/* | awk -F"/" '{print NR".", $NF}'
printf "==========================\n"
