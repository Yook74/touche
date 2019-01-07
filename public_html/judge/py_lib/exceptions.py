class UndefinedFileTypeError(Exception):
    def __init__(self, extension):
        self.message = '"%s" is not a recognized file type. Make sure you are submitting your source code.' % extension

    def __str__(self):
        return self.message


class ForbiddenWordError(Exception):
    def __init__(self, word):
        self.message = 'The string "%s" appears in your code and it was rejected for security reasons. ' \
                       'Please remove or refactor it the string.' % word

    def __str__(self):
        return self.message


class CompileError(Exception):
    def __init__(self, error):
        self.message = 'Compile error: %s' % error

    def __str__(self):
        return self.message


class TimeExceededError(Exception):
    def __init__(self):
        self.message = "Your program's runtime was longer than the time allotted to it. Check for infinite loops"

    def __str__(self):
        return self.message


class RuntimeError(Exception):
    def __init__(self):
        self.message = "Your program produced a runtime error"

    def __str__(self):
        return self.message


class IncorrectOutputError(Exception):
    def __init__(self):
        self.message = "Your program's output was incorrect"

    def __str__(self):
        return self.message


class FormatError(Exception):
    def __init__(self):
        self.message = "Your program's output was correct except that it was formatted incorrectly"

    def __str__(self):
        return self.message