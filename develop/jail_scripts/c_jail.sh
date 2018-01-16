#!/bin/bash
if [ ! -d "c_jail" ]; then
	mkdir c_jail 
fi

mkdir ./c_jail/usr
mkdir ./c_jail/usr/bin
mkdir ./c_jail/usr/lib
mkdir ./c_jail/bin
mkdir ./c_jail/lib64
mkdir ./c_jail/lib
mkdir ./c_jail/lib/x86_64-linux-gnu
cp /bin/sh ./c_jail/bin/sh
cp /lib/x86_64-linux-gnu/libc.so.6 ./c_jail/lib/x86_64-linux-gnu/libc.so.6
cp /lib64/ld-linux-x86-64.so.2 ./c_jail/lib64/ld-linux-x86-64.so.2
