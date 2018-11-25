#!/bin/bash

function get_type_a {

	repo=$1;
	dirname=$2

	echo "Removing current $repo"
	if [ -d tools/"$dirname" ]; then rm -rf tools/"$dirname"; fi;

	echo "Begin download of $repo";
	git clone http://github.com/TheMarlboroMan/"$repo" &> /dev/null;

	echo "Preparing $repo";
	mkdir -p tools;
	mv "$repo"/lib/src/tools/* tools/;

	echo "Cleaning up $repo";
	rm -rf "$repo";
}

echo "Removing current php-request";
if [ -d "php-request" ]; then rm -rf php-request; fi;
if [ -d "request" ]; then rm -rf request; fi;

echo "Begin download of php-request";
git clone http://github.com/TheMarlboroMan/php-request &> /dev/null;

echo "Preparing php-request";
mkdir -p request;
mv php-request/lib/src/request/* request/;

echo "Cleaning up php-request";
rm -rf php-request;

################################################################################

get_type_a php-pattern-matcher pattern_matcher;
get_type_a php-error-reporter error_reporter;

echo "Setting up permissions...";
chown -R alertasdeservidores.com:alertasdeservidores.com ./*

echo "Done";
