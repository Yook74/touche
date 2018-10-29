#!/bin/bash
if [ ! -d "java_jail" ]; then
	mkdir java_jail
fi
mkdir ./java_jail/usr
mkdir ./java_jail/usr/bin
mkdir ./java_jail/usr/lib
mkdir ./java_jail/usr/lib/jvm
mkdir ./java_jail/bin
mkdir ./java_jail/lib64
mkdir ./java_jail/lib
mkdir ./java_jail/lib/x86_64-linux-gnu
mkdir ./java_jail/usr/lib/jvm/java-6-openjdk-amd64
mkdir ./java_jail/usr/lib/jvm/java-1.8.0-openjdk-amd64
cp /bin/sh ./java_jail/bin/sh
cp /lib/x86_64-linux-gnu/libpthread.so.0 ./java_jail/lib/x86_64-linux-gnu/
cp /lib/x86_64-linux-gnu/libdl.so.2 ./java_jail/lib/x86_64-linux-gnu/
cp /lib/x86_64-linux-gnu/libc.so.6 ./java_jail/lib/x86_64-linux-gnu/
cp /lib64/ld-linux-x86-64.so.2 ./java_jail/lib64
cp /lib/x86_64-linux-gnu/libz.so.1 ./java_jail/lib/x86_64-linux-gnu/
cp -r /usr/lib/jvm/java-1.8.0-openjdk-amd64/* ./java_jail/usr/lib/jvm/java-1.8.0-openjdk-amd64/
