#!/bin/bash
if [ ! -d "java_jail" ]; then
	mkdir java_jail
fi

for dir in "usr" "usr/bin" "usr/lib" "usr/lib/jvm" "bin" "lib64" "lib" "lib/x86_64-linux-gnu" "opt" "proc"
do
    mkdir ./java_jail/ $dir
done


for lib in "libpthread.so.0" "libdl.so.2" "libc.so.6" "libm.so.6" "libnsk.so.1" "libz.so.1"
do
    cp -lp /lib/x86_64-linux-gnu/ $lib ./java_jail/lib/x86_64-linux-gnu/
done

# This weird path for java is not ideal but it is the path referenced in judge/Lang/JAVA.inc
cp -lpr /opt/sun-jdk-1.6.0.15 ./java_jail/opt/sun-jdk-1.6.0.15

cp -lp /bin/sh ./java_jail/bin/sh
cp -lp /lib64/ld-linux-x86-64.so.2 ./java_jail/lib64
