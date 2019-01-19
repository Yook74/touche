#!/bin/bash
if [ ! -d "python2_jail" ]; then
  mkdir python2_jail
fi
  
for dir in "bin" "lib" "lib64" "usr" "usr/lib64" "usr/bin" "usr/include" "lib/x86_64-linux-gnu" "usr/include/python2.7" "usr/bin/lib/python2.7"
do
  mkdir -p ./python2_jail/$dir
done

cp -lp /lib/x86_64-linux-gnu/ld-linux-x86-64.so.2 ./python2_jail/lib64/
cp -lp /bin/sh ./python2_jail/bin/sh
cp -lp /usr/bin/head ./python2_jail/bin/head

for python in "python" "python2.7"
do
  cp -lp /usr/bin/$python ./python2_jail/usr/bin/
done

for lib in "libc.so.6" "libpthread.so.0" "libdl.so.2" "libm.so.6" "libutil.so.1" "libz.so.1"
do
  cp -lp /lib/x86_64-linux-gnu/$lib ./python2_jail/lib/x86_64-linux-gnu
done

# The following files were in Geisler's jail, but python runs fine without them
# cp -lp /usr/include/x86_64-linux-gnu/python2.7/pyconfig.h ./python2_jail/usr/include/python2.7/
# cp -lp /usr/lib/x86_64-linux-gnu/libpython2.7.so.1.0 ./python2_jail/usr/lib64/

for py in "os.py" "site.py" "posixpath.py" "stat.py" "genericpath.py" "warnings.py" "linecache.py" "types.py" "UserDict.py" \
 "_abcoll.py" "abc.py" "_weakrefset.py" "copy_reg.py" "traceback.py" "sysconfig.py" "re.py" "sre_compile.py" "sre_parse.py" \
 "sre_constants.py" "_sysconfigdata.py"
do
  cp -lp /usr/lib/python2.7/$py ./python2_jail/usr/bin/lib/python2.7/
done

# The following python modules are being included in order to run example files, but we're not sure if they should actually be included in the jail
for py in "numbers.py" "__future__.py" "decimal.py" "fractions.py" "random.py" "hashlib.py" "struct.py" "functools.py"
do
  cp -lp /usr/lib/python2.7/$py ./python2_jail/usr/bin/lib/python2.7/
done

cp -lp /usr/lib/python2.7/plat-x86_64-linux-gnu/_sysconfigdata_nd.py ./python2_jail/usr/bin/lib/python2.7/

cp -pr /usr/lib/python2.7/lib-dynload ./python2_jail/usr/bin/lib/python2.7/

# Included for debugging the jail script
cp /usr/bin/strace python2_jail/usr/bin/