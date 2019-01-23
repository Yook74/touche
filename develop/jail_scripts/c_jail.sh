#!/bin/bash
if [ ! -d "c_jail" ]; then
	mkdir c_jail 
fi

for dir in "usr" "usr/bin" "usr/lib" "bin" "lib64" "lib" "lib/x86_64-linux-gnu"
do
    mkdir ./c_jail/$dir
done

cp -lp /lib/x86_64-linux-gnu/libc.so.6 ./c_jail/lib/x86_64-linux-gnu/libc.so.6

cp -lp /bin/sh ./c_jail/bin/sh
cp -lp /lib64/ld-linux-x86-64.so.2 ./c_jail/lib64
