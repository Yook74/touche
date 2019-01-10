from .submission import Submission
from .exceptions import *

import re
from os import path
import subprocess

CONFIG_PATH = 'py_lib/c_config.ini'


class CSubmission(Submission):
    def __init__(self, **kwargs):
        super().__init__(**kwargs, config_path=CONFIG_PATH)

    def replace_headers(self):
        """
        Replaces the #include statements in the source file with those specified by config['headers']
        """
        with open(self.source_path, 'r+') as source_file:
            file_string = source_file.read()
            file_string = re.sub(r"#include\s*<(\w|[-_=.^%$#!*]|\s)*>", "", file_string)  # remove #includes TODO strip "s too

            for library in self.config['headers']:
                file_string = ("#include <%s>\n" % library) + file_string

            source_file.seek(0)
            source_file.write(file_string)
            source_file.truncate()

    def compile(self):
        """
        Compiles the source file using the compiler information specified in the config file
        :raises CompileError
        """
        self.executable_path = path.join(self.dirs['judged'], self.base_name)
        self.error_path = path.join(self.dirs['judged'], self.base_name + '.err')
        with open(self.error_path, 'w') as error_file:

            args = [self.config['compiler']] + \
                   self.config['compiler_flags'] + \
                   [self.executable_path, self.source_path] + \
                   self.config['linker_flags']
            try:
                subprocess.run(args, stderr=error_file, check=True)

            except subprocess.CalledProcessError as err:
                raise CompileError(err.returncode)

