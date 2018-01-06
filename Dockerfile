FROM ubuntu:latest

WORKDIR /home

RUN apt-get update
RUN apt-get install -y default-jre
RUN apt-get install -y apache2
RUN apt-get install -y apache2-suexec-custom
RUN apt-get install -y binfmt-support
RUN apt-get install -y php
RUN apt-get install -y libapache2-mod-php
RUN apt-get install -y vim
RUN chmod -R 755 /home
# RUN apt-get install -y mysql-server


# cpp jail
# RUN mkdir cpp_jail
# RUN mkdir cpp_jail/usr
# RUN mkdir cpp_jail/usr/bin
# RUN mkdir cpp_jail/usr/lib
# RUN mkdir cpp_jail/bin
# RUN mkdir cpp_jail/lib64
# RUN mkdir cpp_jail/lib
# RUN mkdir cpp_jail/lib/x86_64-linux-gnu
# RUN cp /bin/sh cpp_jail/bin/
# RUN cp /bin/ls cpp_jail/bin/
# RUN cp /lib/x86_64-linux-gnu/libc.so.6 cpp_jail/lib/x86_64-linux-gnu/
# RUN cp /lib/x86_64-linux-gnu/libgcc_s.so.1 cpp_jail/lib/x86_64-linux-gnu/
# RUN cp /lib/x86_64-linux-gnu/libm.so.6 cpp_jail/lib/x86_64-linux-gnu/
# RUN cp /lib64/ld-linux-x86-64.so.2 cpp_jail/lib64/
# RUN cp /bin/sh cpp_jail/bin/
# RUN cp -r /usr/lib/x86_64-linux-gnu cpp_jail/usr/lib/

# # c jail
# RUN mkdir c_jail
# RUN mkdir c_jail/usr
# RUN mkdir c_jail/usr/bin
# RUN mkdir c_jail/usr/lib
# RUN mkdir c_jail/bin
# RUN mkdir c_jail/lib64
# RUN mkdir c_jail/lib
# RUN mkdir c_jail/lib/x86_64-linux-gnu
# RUN cp /bin/sh c_jail/bin/
# RUN cp /lib/x86_64-linux-gnu/libc.so.6 c_jail/lib/x86_64-linux-gnu/
# RUN cp /lib64/ld-linux-x86-64.so.2 c_jail/lib64/

# # java jail
# RUN mkdir java_jail
# RUN mkdir java_jail/bin
# RUN mkdir java_jail/usr
# RUN mkdir java_jail/usr/bin
# RUN mkdir java_jail/lib
# RUN mkdir java_jail/lib/x86_64-linux-gnu
# RUN mkdir java_jail/lib64
# RUN mkdir -p java_jail/usr/lib/jvm/java-1.8.0-openjdk-amd64/
# RUN cp /bin/sh java_jail/bin/
# RUN cp /usr/bin/java java_jail/usr/bin/
# RUN cp /lib/x86_64-linux-gnu/libpthread.so.0 java_jail/lib/x86_64-linux-gnu/
# RUN cp /lib/x86_64-linux-gnu/libdl.so.2 java_jail/lib/x86_64-linux-gnu/
# RUN cp /lib/x86_64-linux-gnu/libc.so.6 java_jail/lib/x86_64-linux-gnu/
# RUN cp /lib64/ld-linux-x86-64.so.2 java_jail/lib64
# RUN cp /lib/x86_64-linux-gnu/libz.so.1 java_jail/lib/x86_64-linux-gnu/
# RUN cp -r /usr/lib/jvm/java-1.8.0-openjdk-amd64 java_jail/usr/lib/jvm/java-1.8.0-openjdk-amd64

#Set up Directories for server
COPY ./ /home/src
RUN mkdir /home/public_html
RUN ln -s /home/src/develop develop
RUN ln -s /home/src/public_html public_html/develop
RUN ln /home/src/createcontest.php public_html/createcontest.php
RUN ln /home/src/createcontest2.php public_html/createcontest2.php
RUN ln /home/src/index.php public_html/index.php
RUN ln -s /home/src/dbcreate.sql public_html/dbcreate.sql
RUN ln -s /home/src/readme public_html/readme

EXPOSE 80
