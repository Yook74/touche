#!/bin/bash
mysqlpasswd=$1
contestpasswd=$2
echo -e "EnableSendfile off\nDefaultType None" >> /etc/apache2/suexec/www-data
sed -i 's/cgi-bin//g' /etc/apache2/suexec/www-data
sed -i 's/;cgi.force_redirect = 1/cgi.force_redirect = 0/g' /etc/php/7.0/cgi/php.ini
DEBIAN_FRONTEND=noninteractive apt-get -y install mysql-server
mkdir /var/run/mysqld
chown -R mysql:mysql /var/lib/mysql /var/run/mysqld
service mysql start
mysqladmin --user=root password $mysqlpasswd
/usr/bin/mysql -uroot -ppassword -e "CREATE USER 'contest_skeleton'@'localhost' IDENTIFIED BY '$contestpasswd'; GRANT ALL PRIVILEGES ON *.* TO 'contest_skeleton'@'localhost';"
service mysql stop
adduser contest_skeleton --gecos "contest,,," --disabled-password
echo "contest_skeleton:$contestpasswd" | chpasswd
cat /home/contest_skeleton/src/php7cgi > /etc/apache2/mods-enabled/php7.0.conf
chmod 755 /home/contest_skeleton/src/links.sh
su contest_skeleton -c "sh /home/contest_skeleton/src/links.sh"
chmod -R 755 /home/contest_skeleton
output="$( bash <<EOF
hostname
EOF
)"
echo "contest_skeleton	$output = NOPASSWD: /bin/chown, /bin/chmod" >> /etc/sudoers

