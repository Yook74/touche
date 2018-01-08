FROM ubuntu:latest

WORKDIR /home

RUN apt-get update
RUN apt-get install -y default-jre
RUN apt-get install -y apache2
RUN apt-get install -y apache2-suexec-custom
RUN apt-get install -y binfmt-support
RUN apt-get install -y php
RUN apt-get install -y vim
RUN apt-get install -y php-cgi
RUN apt-get install -y php-mysqli
RUN chmod -R 755 /home
RUN a2enmod suexec
RUN a2enmod userdir
# RUN apt-get install -y mysql-server

#Set up Directories for server
COPY ./ /etc/skel/src
RUN mkdir /etc/skel/public_html
RUN ln -s /etc/skel/src/develop /etc/skel/develop
RUN ln -s /etc/skel/src/public_html /etc/skel/public_html/develop
RUN ln /etc/skel/src/createcontest.php /etc/skel/public_html/createcontest.php
RUN ln /etc/skel/src/createcontest2.php /etc/skel/public_html/createcontest2.php
RUN ln /etc/skel/src/index.php /etc/skel/public_html/index.php
RUN ln -s /etc/skel/src/dbcreate.sql /etc/skel/public_html/dbcreate.sql
RUN ln -s /etc/skel/src/lib /etc/skel/public_html/lib
RUN ln -s /etc/skel/src/readme /etc/skel/public_html/readme

EXPOSE 80
EXPOSE 3306
