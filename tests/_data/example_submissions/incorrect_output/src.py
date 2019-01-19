try:
    line = input()
except EOFError:
    pass

one, two = line.split()
one = int(one)
two = int(two)
print(one + two, one + two)
