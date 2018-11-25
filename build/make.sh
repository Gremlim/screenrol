#!/bin/bash

if [ $(hostname) != "s172-213" ] && [ $(hostname) != "s68-126" ]; then
        echo "This script can ONLY be executed in DEV01 or DEV-55. Aborting.";
		exit;
fi;

if [ "$1" != "CONFIRM" ]; then

	echo "This command will syncronyse TEST to DEV and change the file ownership as needed. It will also push the result of the merge to TEST.";
	echo "To run it, do make.sh CONFIRM"

else

	echo "Starting";

	USER='alertasdeservidores.com';
	GROUP='alertasdeservidores.com';

	git checkout dev
	git pull origin dev
	git checkout test
	git merge dev
	git push origin test
	git branch -d dev
	
	cd ..;
	for file in ./*
	do
		if [ $file != "./backup_site" ] && [ $file != "./cgi-bin" ] && [ $file != "./.git" ] && [ $file != "./logs" ] && [ $file != "./stats" ]; then
			echo "Working on $file";
			chown -R $USER:$GROUP $file;
		else
			echo "Skipping $file";
		fi;
	done;

	cd build;

	./build.sh TEST

	echo "Latest commit is:";
	git log -1

fi
