#!/bin/bash
mysqlpasswd=$1
contestpasswd=$2
echo -e "EnableSendfile off\nDefaultType None" >> /etc/apache2/suexec/www-data
sed -i 's/cgi-bin//g' /etc/apache2/suexec/www-data
sed -i 's/;cgi.force_redirect = 1/cgi.force_redirect = 0/g' /etc/php/7.0/cgi/php.ini
sed -i '299d' /etc/php/7.0/cgi/php.ini
mkdir /var/run/mysqld
chown -R mysql:mysql /var/lib/mysql /var/run/mysqld
service mysql start
mysqladmin --user=root password $mysqlpasswd
/usr/bin/mysql -uroot -ppassword -e "CREATE USER 'contest_skeleton'@'localhost' IDENTIFIED BY '$contestpasswd'; GRANT ALL PRIVILEGES ON *.* TO 'contest_skeleton'@'localhost';"
service mysql stop
adduser contest_skeleton --gecos "contest,,," --disabled-password
echo "contest_skeleton:$contestpasswd" | chpasswd
cat /home/contest_skeleton/src/php7cgi > /etc/apache2/mods-enabled/php7.0.conf
su contest_skeleton -c "sh /home/contest_skeleton/src/links.sh"
su contest_skeleton -c "mkdir /home/contest_skeleton/active-contests"
chmod -R u=rw,go=r,a+X /home
find /home -iname "*.php" | xargs chmod +x
echo -e "$(sudo crontab -u contest_skeleton -l)\n* * * * * /home/contest_skeleton/master-crontab.cron > /dev/null 2>&1" | sudo crontab -u contest_skeleton -
