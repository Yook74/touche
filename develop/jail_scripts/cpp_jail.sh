#!/bin/bash
if [ ! -d "cpp_jail" ]; then
    mkdir cpp_jail
fi

for dir in "usr" "usr/bin" "usr/lib" "bin" "lib64" "lib" "lib/x86_64-linux-gnu" "usr/lib/x86_64-linux-gnu"
do
    mkdir ./cpp_jail/$dir
done

for lib in "libc.so.6" "libm.so.6" "libgcc_s.so.1"
do
    cp -lp /lib/x86_64-linux-gnu/$lib ./cpp_jail/lib/x86_64-linux-gnu/
done

cp -lp /bin/sh ./cpp_jail/bin/sh
cp -lp /lib64/ld-linux-x86-64.so.2 ./cpp_jail/lib64
cp -lp /usr/lib/x86_64-linux-gnu/libstdc++.so.6 ./cpp_jail/usr/lib/x86_64-linux-gnu/libstdc++.so.6
