#!/bin/bash

set -ex

DIR=/backup

echo -e "\e[93m\e[4mEnter backup name (Ex. 2017-12-26_16_50_36)\e[0m"
read BACKUP

if [ ! -d $DIR/$BACKUP ]
then
	if [ ! -f "$DIR/${BACKUP}.tar.gz.gpg" ]
	then
		echo -e "\e[91mThere is no backup with this name\e[0m"
		exit
	else
		echo -e "\e[103m\e[91mFound GPG file, decrypting...\e[0m"
		gpg --batch --yes --passphrase $MYSQL_ROOT_PASSWORD -d ${BACKUP}.tar.gz.gpg | tar xzvf -
	fi
fi

if [ ! -d $DIR/$BACKUP ]
then
	echo -e "\e[91mThere is no backup with this name\e[0m"
	exit
fi

if [ ! -d $DIR/$BACKUP/base ]
then
	echo -e "\e[91mThere is no base folder in this backup\e[0m"
	exit
fi

echo -e "\e[93m\e[4mEnter an hour to restore to (From 1 to 24 or 0 for base restore)\e[0m"
read HOUR

if [ ! -d $DIR/$BACKUP/incr$HOUR ] && [ "$HOUR" -gt 0 ]
then
	echo -e "\e[91mThere is no backup for this hour\e[0m"
	exit
fi

trap 'echo "removing /tmp/$backup"; rm -rf /tmp/$backup' INT TERM EXIT

# Copy backup to Tmp folder
cp -r $DIR/$BACKUP /tmp/$BACKUP

# prepare base
echo -e "\e[103m\e[91mPreparing base backup...\e[0m"
sleep 1
xtrabackup --prepare --apply-log-only --target-dir=/tmp/$BACKUP/base

#prepare increment
if [ "$HOUR" -gt 0 ]
then
	for ((i = 1; i <= hour; i++))
	do 
		echo -e "\e[103m\e[91mPreparing increment backup #$i...\e[0m"
		sleep 1
		xtrabackup --prepare --apply-log-only --target-dir=/tmp/$BACKUP/base \
			--incremental-dir=/tmp/$BACKUP/incr$i
	done
fi

rm -rf /var/lib/mysql/*
echo -e "\e[103m\e[91mCopying backup data to mysql folder...\e[0m"
xtrabackup --copy-back --target-dir=/tmp/$BACKUP/base --datadir=/var/lib/mysql
chown -R mysql:mysql /var/lib/mysql/
service mysql restart
