import numbers
import math
import cmath
import decimal
import fractions
import random
import itertools
import functools
import operator


def my_assert(test: bool, name: str):
    if not test:
        print(name + " test failed")
        exit(1)


def test_numbers():
    my_assert(isinstance(2, numbers.Real), "Real number")
    my_assert(isinstance(1j, numbers.Complex), "Complex number")


def test_math():
    my_assert(math.fabs(-1) == 1, "fabs")
    my_assert(math.ceil(0.4) == 1, "ceil")


def test_cmath():
    my_assert(cmath.log(100, 10) == 2, "log")
    my_assert(not cmath.isnan(4), "isnan")


def test_decimal():
    my_assert(decimal.Decimal(100).log10() == 2, "Decimal log")
    dec = decimal.Decimal(math.pi)
    trunc = decimal.Decimal('3.14')
    my_assert(dec.quantize(decimal.Decimal('0.01')) == trunc, "Decimal quantize")


def test_fractions():
    frac = fractions.Fraction(1, 8)
    my_assert(frac == fractions.Fraction(0.125), "fractions")


def test_random():
    # https://dilbert.com/strip/2001-10-25
    my_assert(random.randint(50, 100) != random.randint(0, 49), "random")


def test_itertools():
    lst = [val for val in itertools.repeat(5, 3)]
    my_assert(lst[2] == 5, "repeat")


def test_functools():
    val = functools.reduce(lambda x, y: x+y, [1, 2, 3, 4, 5])
    my_assert(val == 15, "functools")


def test_operator():
    my_assert(operator.add(2, 2) == 4, "add operator")
    my_assert(operator.ipow(3, 2) == 9, "ipow operator")


test_numbers()
test_math()
test_cmath()
test_decimal()
test_fractions()
test_random()
test_itertools()
test_functools()
test_operator()

line = input()
one, two = line.split()
one = int(one)
two = int(two)
print(one + two)
