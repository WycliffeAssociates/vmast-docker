#!/bin/bash

while ! mysqladmin ping -h$DB_HOST -uroot -p$MYSQL_ROOT_PASSWORD --silent; do
    sleep 1
done

create_db () {
    mysql -h$DB_HOST -uroot -p$MYSQL_ROOT_PASSWORD -e"CREATE DATABASE $DB_NAME;"
    mysql -h$DB_HOST -uroot -p$MYSQL_ROOT_PASSWORD -e"GRANT SELECT, INSERT, UPDATE, DELETE on $DB_NAME.* TO '$DB_USER'@'%';"
    mysql -h$DB_HOST -uroot -p$MYSQL_ROOT_PASSWORD -e"FLUSH PRIVILEGES;"
}

import_dump () {
    gunzip /db_dump/dump.sql.gz
    mysql -h$DB_HOST -uroot -p$MYSQL_ROOT_PASSWORD $DB_NAME < /db_dump/dump.sql
}

if ! mysql -h$DB_HOST -u root -p$MYSQL_ROOT_PASSWORD -e "use $DB_NAME;"; then
    echo "Creating database and giving the user permissions"
    create_db
    import_dump
elif ! mysql -h$DB_HOST -u root -p$MYSQL_ROOT_PASSWORD -e "use $DB_NAME; select * from admin_user;"; then
    echo "Unpacking and importing database dump"
    import_dump
fi

/docker-entrypoint.sh

nginx -g "daemon off;"
