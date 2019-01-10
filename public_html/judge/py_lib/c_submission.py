from .submission import Submission
from .exceptions import *

import shutil
from os import path
import subprocess

CONFIG_PATH = 'py_lib/c_config.ini'


class CSubmission(Submission):
    def __init__(self, **kwargs):
        super().__init__(**kwargs, config_path=CONFIG_PATH)

    def strip_headers(self):
        """
        Removes the headers from the source file and saves them for later
        """
        self.stripped_headers = self.replace_in_source(r'#include\s*["<](\w|[-_=.^%$#!*]|\s)*[">]', '')
        self.stripped_headers = '\n'.join(self.stripped_headers)

    def get_headers(self):
        """
        Converts the list of headers in self.config into a string that can be put at the beginning of the source file
        """
        out = ''
        for header in self.config['headers']:
            out += '#include <%s>\n' % header

        return out

    def run_preprocessor(self):
        """
        We run the preprocessor before checking for forbidden words because it's possible to use macros to
        obscure forbidden words. For example:
        # define func fo##rk
        func()
        results in a call to fork
        """
        temp_path = path.join(self.dirs['judged'], 'temp.tmp')
        temp_file = open(temp_path, 'w')

        args = [self.config['compiler']] + self.config['preprocess_flags'] + [self.source_path]
        subprocess.run(args, stdout=temp_file, check=True)

        temp_file.close()
        shutil.move(temp_path, self.source_path)

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

