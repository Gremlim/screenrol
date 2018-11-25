#!/bin/bash

function use {
	echo './build.sh [DEV|PROD|TEST]	ej: ./build.sh DEV Will setup the development environment';
	echo ' - DEV : For your own local machine';
	echo ' - TEST : For DEV machines (root only)';
	echo ' - PROD : For the REAL DEAL (root only)';
}

function check_root {

	if [ "$EUID" -ne 0 ]; then
		echo "This command must be run as root";
	  	exit
	fi;

}

function setup {
	printf "Setting up '$1' environment...\n";

	if [ ! -f "./dist/css/dist.$1.css" ]; then
		echo "The dist.$1.css file does not exist in ./dist/css/. Aborting";
		exit;
	fi;

	if [ ! -f "./dist/conf/config."$1".json" ]; then
		echo "The config.$1.json file does not exist in /dist/config. Aborting";
		exit;
	fi;

	mkdir -p ../src/conf
	mkdir -p ../src/css
	cp ./dist/css/dist."$1".css ../assets/css/dist.css;
	cp ./dist/conf/config."$1".json ../src/conf/config.json;

	echo "Checking configuration files...";
	php src/check_config.php config.dist.json config."$1".json;

	case $? in
		0)
			echo "Configuration files match.";
		;;
		1)
			echo "ERROR: Configuration files checker returned an invalid number of arguments.";
		;;
		2)
			echo "WARNING: Configuration files checker detected differences between the dist and config files.";
		;;
		*)
			echo "ERROR: Configuration files checker returned an uknown error.";
		;;
	esac;

}

if [ "$1" == "" ]; then
	use;
	exit
else
	case $1 in
	"DEV")
		setup "dev"
	;;
	"TEST")
		check_root;
		setup "test";
	;;
	"PROD")
		check_root;
		setup "prod";
	;;
	*)
		use;
		exit
	;;
	esac;

	printf "Done.\n";
fi;
