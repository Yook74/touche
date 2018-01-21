chown -R mysql:mysql /var/lib/mysql /var/run/mysqld
output="$( bash <<EOF
hostname
EOF
)"
echo "contest_skeleton  $output = NOPASSWD: /bin/chown, /bin/chmod" >> /etc/sudoers
service mysql start
service apache2 start
service cron start
cd /etc/skel/src/client
npm install
#ng serve --host 0.0.0.0 --port 80
/bin/bash