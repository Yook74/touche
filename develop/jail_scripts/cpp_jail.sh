#!/bin/bash
if [ ! -d "cpp_jail" ]; then
	mkdir cpp_jail
fi

mkdir ./cpp_jail/usr
mkdir ./cpp_jail/usr/bin
mkdir ./cpp_jail/usr/lib
mkdir ./cpp_jail/bin
mkdir ./cpp_jail/lib64
mkdir ./cpp_jail/lib
mkdir ./cpp_jail/lib/x86_64-linux-gnu
cp /bin/sh ./cpp_jail/bin/sh
cp /lib/x86_64-linux-gnu/libc.so.6 ./cpp_jail/lib/x86_64-linux-gnu/libc.so.6
cp /lib/x86_64-linux-gnu/libgcc_s.so.1 ./cpp_jail/lib/x86_64-linux-gnu/libgcc_s.so.1
cp /lib/x86_64-linux-gnu/libm.so.6 ./cpp_jail/lib/x86_64-linux-gnu/libm.so.6
cp /lib64/ld-linux-x86-64.so.2 ./cpp_jail/lib64/ld-linux-x86-64.so.2
cp /usr/bin/sh ./cpp_jail/usr/bin/sh
cp /usr/lib/x86_64-linux-gnu ./cpp_jail/usr/lib/x86_64-linux-gnu
