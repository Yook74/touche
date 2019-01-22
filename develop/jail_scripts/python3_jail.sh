#!/bin/bash
if [ ! -d "python3_jail" ]; then
  mkdir python3_jail
fi

for dir in "bin" "lib64" "usr" "usr/bin" "usr/lib" "lib" "lib/x86_64-linux-gnu" "usr/lib/python3.5" "usr/lib/python3.5/plat-x86_64-linux-gnu"
do
  mkdir -p ./python3_jail/$dir
done

cp -lp /bin/sh ./python3_jail/bin/sh
cp -lp /usr/bin/head ./python3_jail/bin/head

for lib in "libc.so.6" "libpthread.so.0" "libdl.so.2" "libm.so.6" "libutil.so.1" "libz.so.1" "ld-linux-x86-64.so.2"  \
 "libncurses.so.5" "libreadline.so.6" "libexpat.so.1" 
do
  cp -lp /lib/x86_64-linux-gnu/$lib ./python3_jail/lib/x86_64-linux-gnu/
done

cp -lp /lib/x86_64-linux-gnu/ld-linux-x86-64.so.2 ./python3_jail/lib64/

cp -lp /usr/bin/python3.5 ./python3_jail/usr/bin/

for py in "codecs.py" "io.py" "abc.py" "genericpath.py" "os.py" "posixpath.py" "site.py" "stat.py" "sysconfig.py" "_weakrefset.py" \
 "_collections_abc.py" "_sitebuiltins.py" "_sysconfigdata.py"
do
   cp -lp /usr/lib/python3.5/$py ./python3_jail/usr/lib/python3.5/
done

cp -lp /usr/lib/python3.5/plat-x86_64-linux-gnu/_sysconfigdata_m.py  ./python3_jail/usr/lib/python3.5/plat-x86_64-linux-gnu/

# The following python modules are being included in order to run example files, but we're not sure if they should actually be included in the jail
for py in "numbers.py" "__future__.py" "decimal.py" "fractions.py" "random.py" "hashlib.py" "struct.py" "functools.py" \
 "_pydecimal.py" "re.py" "sre_compile.py" "sre_parse.py" "sre_constants.py" "copyreg.py" "operator.py" "warnings.py" "types.py" \
 "keyword.py" "heapq.py" "reprlib.py" "weakref.py"
do
  cp -lp /usr/lib/python3.5/$py ./python3_jail/usr/lib/python3.5/
done

for dir in "lib-dynload" "encodings" "collections"
do
  cp -pr /usr/lib/python3.5/$dir ./python3_jail/usr/lib/python3.5/
done

# Included for debugging the jail script
cp /usr/bin/strace python3_jail/usr/bin/