#!/bin/bash

set -ex

DIR=/backup

# TODO Rewrite to mariabackup

#cd $DIR
#
## MariaDB ships mariabackup, Percona ships xtrabackup. They take the same
## arguments, so either will do.
#BACKUP_TOOL=$(command -v mariabackup || command -v xtrabackup || true)
#
#if [ -z "$BACKUP_TOOL" ]
#then
#	echo -e "\e[91mNeither mariabackup nor xtrabackup is installed\e[0m"
#	exit 1
#fi
#
#echo -e "\e[93m\e[4mEnter backup name (Ex. 2017-12-26_16_50_36)\e[0m"
#read BACKUP
#
#if [ ! -d $DIR/$BACKUP ]
#then
#	if [ ! -f "$DIR/${BACKUP}.tar.gz.gpg" ]
#	then
#		echo -e "\e[91mThere is no backup with this name\e[0m"
#		exit
#	else
#		echo -e "\e[103m\e[91mFound GPG file, decrypting...\e[0m"
#		gpg --batch --yes --passphrase $MYSQL_ROOT_PASSWORD -d ${BACKUP}.tar.gz.gpg | tar xzvf -
#	fi
#fi
#
#if [ ! -d $DIR/$BACKUP ]
#then
#	echo -e "\e[91mThere is no backup with this name\e[0m"
#	exit
#fi
#
#if [ ! -d $DIR/$BACKUP/base ]
#then
#	echo -e "\e[91mThere is no base folder in this backup\e[0m"
#	exit
#fi
#
#echo -e "\e[93m\e[4mEnter an hour to restore to (From 1 to 24 or 0 for base restore)\e[0m"
#read HOUR
#
#if [ ! -d $DIR/$BACKUP/incr$HOUR ] && [ "$HOUR" -gt 0 ]
#then
#	echo -e "\e[91mThere is no backup for this hour\e[0m"
#	exit
#fi
#
#trap 'echo "removing /tmp/$BACKUP"; rm -rf "/tmp/$BACKUP"' INT TERM EXIT
#
## Copy backup to Tmp folder
#cp -r $DIR/$BACKUP /tmp/$BACKUP
#
## prepare base
#echo -e "\e[103m\e[91mPreparing base backup...\e[0m"
#sleep 1
#$BACKUP_TOOL --prepare --apply-log-only --target-dir=/tmp/$BACKUP/base
#
##prepare increment
#if [ "$HOUR" -gt 0 ]
#then
#	for ((i = 1; i <= HOUR; i++))
#	do
#		echo -e "\e[103m\e[91mPreparing increment backup #$i...\e[0m"
#		sleep 1
#		$BACKUP_TOOL --prepare --apply-log-only --target-dir=/tmp/$BACKUP/base \
#			--incremental-dir=/tmp/$BACKUP/incr$i
#	done
#fi
#
#rm -rf /var/lib/mysql/*
#echo -e "\e[103m\e[91mCopying backup data to mysql folder...\e[0m"
#$BACKUP_TOOL --copy-back --target-dir=/tmp/$BACKUP/base --datadir=/var/lib/mysql
#chown -R mysql:mysql /var/lib/mysql/
#
#echo -e "\e[92mRestore complete. Restart the database service to pick up the"
#echo -e "restored data directory: docker compose restart db\e[0m"
