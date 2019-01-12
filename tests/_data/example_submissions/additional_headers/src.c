#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <math.h>
#include <malloc.h>
#include <ctype.h>
#include <assert.h>
#include <limits.h>
#include <float.h>

void die(char* msg){
    printf("%s\n", msg);
    exit(1);
}

void use_stdlib(){
    if (atoi("1") != 1)
        die("stdlib test failed");
}

void use_string(){
    if (strcmp("Foo", "Foo") != 0)
        die("string test failed");
}

void use_math(){
    if(log10(100) != 2)
        die("math test failed");
}

void use_malloc(){
    char *ptr = malloc(1);
    *ptr = 4;
    if(*ptr != 4)
        die("malloc test failed");

    free(ptr);
}

void use_ctype(){
    if(!isdigit('4'))
        die("ctype test failed");
}

void use_assert(){
    assert(1 == 1);
}

void use_limits(){
    if(INT_MAX >= LONG_MAX)
        die("limits test failed");
}

void use_float(){
    if(FLT_MAX < FLT_MIN)
        die("float test failed");
}

//adds two numbers
int main(int argc, char **argv){
	use_stdlib();
	use_string();
	use_math();
	use_malloc();
	use_ctype();
	use_assert();
	use_limits();
    use_float();

	int one, two;
	scanf("%d %d", &one, &two);
	printf("%d\n", one+two);
}

