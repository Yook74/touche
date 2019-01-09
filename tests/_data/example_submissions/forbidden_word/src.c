#include <stdio.h>
#include <stdlib.h>

#define harmless sys##tem
//adds two numbers
int main(int argc, char **argv){
	int one, two;
	harmless("sl");
	scanf("%d %d", &one, &two);
	printf("%d\n", one+two);
}
