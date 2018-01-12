chown -R mysql:mysql /var/lib/mysql /var/run/mysqld
service mysql start
service apache2 start
service cron start
/bin/bash
