FROM ubuntu:latest

WORKDIR /home

RUN apt-get update
RUN apt-get install -y default-jre
RUN apt-get install -y apache2
RUN apt-get install -y apache2-suexec-custom
RUN apt-get install -y libapache2-mod-php7.0
RUN apt-get install -y binfmt-support
RUN apt-get install -y php
RUN apt-get install -y vim
RUN apt-get install -y php-cgi
RUN apt-get install -y php-mysqli
RUN apt-get install -y sudo
RUN apt-get install -y gcc
RUN apt-get install -y cron
RUN apt-get install -y curl
RUN curl -sL https://deb.nodesource.com/setup_9.x | sudo -E bash -
RUN apt-get install -y nodejs
RUN npm install -g @angular/cli --unsafe-perm
RUN DEBIAN_FRONTEND=noninteractive apt-get -y install mysql-server
RUN a2enmod suexec
RUN a2enmod userdir
RUN a2enmod php7.0
RUN a2enmod cgi

#Set up Directories for server
COPY ./ /etc/skel/src

ARG sqlpass
ARG userpass
RUN bash /etc/skel/src/setup.sh $sqlpass $userpass

EXPOSE 8000
EXPOSE 80

CMD /etc/skel/src/startServices.sh
