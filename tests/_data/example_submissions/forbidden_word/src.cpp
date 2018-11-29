#include <stdio.h>
#include <fstream>
using namespace std;

//adds two numbers
int main(int argc, char **argv){
	int one, two;
    fstream fs;
    fs.open("src.c");
    fs.close();
	scanf("%d %d", &one, &two);
	printf("%d\n", one+two);
}
