#!/bin/bash
# Maintenance mode toggle (flag file: System/Storage/maintenance.flag)

run_down() {
	local msg="$*"
	local flag="$NSY_ROOT_DIR/System/Storage/maintenance.flag"

	mkdir -p "$NSY_ROOT_DIR/System/Storage"
	if printf '%s' "$msg" > "$flag"; then
		printf "Maintenance mode: ON\n"
		if [ -n "$msg" ]; then
			printf "Message: %s\n" "$msg"
		fi
	else
		printf "Failed to write %s\n" "$flag"
		return 1
	fi
}

run_up() {
	local flag="$NSY_ROOT_DIR/System/Storage/maintenance.flag"

	if [ -e "$flag" ]; then
		rm -f "$flag"
		printf "Maintenance mode: OFF\n"
	else
		printf "Maintenance mode is already OFF\n"
	fi
}
