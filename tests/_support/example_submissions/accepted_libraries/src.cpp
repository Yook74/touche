#include <cassert>
#include <cstdio>
#include <cstdlib>
#include <cstring>
#include <cmath>
#include <climits>
#include <cctype>
#include <iostream>
#include <sstream>
#include <iomanip>
#include <string>
#include <new>
#include <stdexcept>
#include <list>
#include <queue>
#include <stack>
#include <vector>
#include <map>
#include <iterator>
#include <bitset>
#include <algorithm>
#include <set>

using namespace std;

void die(const char* msg){
    printf("%s\n", msg);
    fflush(stdout);
    exit(1);
}

void use_stdlib(){
    if (atoi("1") != 1)
        die("stdlib test failed");
}

void use_cstring(){
    if (strcmp("Foo", "Foo") != 0)
        die("string test failed");
}

void use_math(){
    if(log10(100) != 2)
        die("math test failed");
}

void use_malloc(){
    char *ptr = (char*) malloc(1);
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

void use_iostream(){
    if(cout.eof())
        die("iostream test failed");
}

void use_sstream(){
    std::stringbuf buffer;
    std::string str ("Foo");
    buffer.str(str);
    if (str.compare(buffer.str()) != 0)
        die("sstream test failed");
}

void use_iomanip(){
    cout << std::setiosflags(std::ios::showbase);
    cout << std::resetiosflags(std::ios::showbase);
}

void use_string(){
    std::string str("Foo");
    if (str.length() != 3)
        die("string test failed");
}

void use_new(){
    string* pointer = new string("Foo");
    if (pointer->length() != 3)
        die("string test failed");

    delete pointer;
}

void use_stdexcept(){
    bool caught = false;
    try{
        throw std::logic_error("Oh no a thing");
    }
    catch (logic_error err){
        caught = true;
    }
    if (!caught)
        die("stdexcept test failed");
}

void use_list(){
    list<int> lst;
    lst.push_back(5);
    lst.push_back(3);

    if(lst.front() != 5)
        die("list test failed");
}

void use_queue(){
    queue<int> q;
    q.push(5);
    q.push(3);

    if(q.front() != 5)
        die("queue test failed");
}

void use_stack(){
    stack<int> stk = stack<int>();
    stk.push(5);
    stk.push(3);

    if(stk.top() != 3)
        die("stack test failed");
}

void use_vector(){
    vector<int> vec;
    vec.push_back(5);
    vec.push_back(3);

    if(vec.at(0) != 5)
        die("vector test failed");
}

void use_map(){
    map<char, int> mp;
    mp.insert(std::pair<char,int>('a',100));
    mp.insert(std::pair<char,int>('b',200));

    if(mp['a'] != 100)
        die("map test failed");
}

void use_iterator(){
  std::vector<int> fullVec;
  std::vector<int> newVec;
  vector<int>::iterator ptr;

  fullVec.push_back(0);
  fullVec.push_back(10);
  fullVec.push_back(20);

  for (ptr = fullVec.begin(); ptr < fullVec.end(); ptr++)
    newVec.push_back(*ptr);

  if (newVec[2] != 20)
    die("iterator test failed");
}

void use_bitset(){
    bitset<16> bits(0x0f0f);
    if (bits[7] != 0)
        die("bitset test failed");
}

void use_algorithm(){
    int arr[] = {2, 1, 0};
    vector<int> vec (arr, arr+3);

    std::sort (vec.begin(), vec.begin()+3);

    if(vec[0] != 0)
        die("algorithm test failed");
}

void use_set(){
    set<int> ints;
    ints.insert(1);
    ints.insert(2);
    ints.insert(3);

    if(ints.size() != 3)
        die("set test failed");
}


//adds two numbers
int main(int argc, char **argv){
	use_stdlib();
	use_cstring();
	use_math();
	use_malloc();
	use_ctype();
	use_assert();
	use_limits();
	use_iostream();
	use_sstream();
	use_iomanip();
	use_string();
	use_new();
	use_stdexcept();
	use_list();
	use_queue();
	use_stack();
	use_vector();
	use_map();
	use_iterator();
	use_bitset();
	use_algorithm();
	use_set();

	int one, two;
	scanf("%d %d", &one, &two);
	printf("%d\n", one+two);
}
