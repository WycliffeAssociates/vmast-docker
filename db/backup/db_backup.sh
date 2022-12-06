#!/bin/bash

DIR=/backup

function base_backup {
	CURDATE=$(date +%Y-%m-%d_%H_%M_%S)
	PREVDATE=$(date +%Y-%m-%d -d "-30 days")

	# Delete backups older than 30 days
	if ls $DIR/$PREVDATE* 1> /dev/null 2>&1
	then
		rm -r $DIR/$PREVDATE*
	fi

	mkdir $DIR/$CURDATE
	mkdir $DIR/$CURDATE/base

	echo 0 > $DIR/counter
	echo $CURDATE > $DIR/dater

	xtrabackup --user=root --password=$MYSQL_ROOT_PASSWORD \
		--ssl-mode=PREFERRED --backup \
		--target-dir=$DIR/$CURDATE/base \
		--extra-lsndir=$DIR/$CURDATE
}

RUNDATE=$(date +%Y-%m-%d_%H_%M_%S)

echo "|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||"
echo "|---------------------------------------------------------|"
echo "|...............Creation of backup started................|"
echo "|...................$RUNDATE...................|"
echo "|---------------------------------------------------------|"
echo "|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||"

if [ ! -f $DIR/counter ]
then
	# if folder is empty, create base backup
	base_backup
else
	COUNTER=`cat $DIR/counter`
	CURDATE=`cat $DIR/dater`

	# Create incremental backups every hour
	if [ $COUNTER != "23" ]
	then
		let COUNTER+=1
		echo $COUNTER > $DIR/counter

		mkdir $DIR/$CURDATE/incr$COUNTER

		xtrabackup --user=root --password=$MYSQL_ROOT_PASSWORD \
			--ssl-mode=PREFERRED --backup \
			--incremental-basedir=$DIR/$CURDATE \
			--target-dir=$DIR/$CURDATE/incr$COUNTER \
			--extra-lsndir=$DIR/$CURDATE
		echo "|----------- Created incremental backup #$COUNTER -----------|"
	else
		base_backup
	fi
fi

# compress and encrypt
tar -cvzf - $CURDATE | gpg --batch --yes --symmetric --passphrase $MYSQL_ROOT_PASSWORD --cipher-algo aes256 -o $CURDATE.tar.gz.gpg

echo "."
echo "."
echo "."
echo "."
echo "."
