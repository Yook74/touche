from .submission import Submission
from .exceptions import *

import re
from os import path
import subprocess

CONFIG_PATH = 'py_lib/c_config.ini'


class CSubmission(Submission):
    def __init__(self, dirs, problem_id, source_name):
        super().__init__(dirs, problem_id, source_name, CONFIG_PATH)

    def replace_headers(self):
        with open(self.source_path, 'r+') as source_file:
            file_string = source_file.read()
            re.sub(r"#include\s*<(\w|[-_=.^%$#!*]|\s)*>", "", file_string)  # remove #includes

            for library in self.lang_headers:
                file_string = ("#include <%s>\n" % library) + file_string

            source_file.write(file_string)  # overwrite old file

    def compile(self):
        self.executable_path = path.join(self.dirs['judged'], self.base_name)
        self.error_path = path.join(self.dirs['judged'], self.base_name + '.err')

        try:
            subprocess.run([self.config['compiler'],
                            self.config['compiler flags'],
                            self.executable_path,
                            self.source_path,
                            self.config['linker flags'],
                            "2>",
                            self.error_path])

        except subprocess.CalledProcessError as err:
            raise CompileError(err.returncode)
