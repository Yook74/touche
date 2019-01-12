from .submission import Submission
from .exceptions import *

import shutil
from os import path
from os import mkdir
import subprocess

CONFIG_PATH = 'py_lib/java_config.ini'


class JavaSubmission(Submission):
    def __init__(self, **kwargs):
        super().__init__(**kwargs, config_path=CONFIG_PATH)

    def strip_headers(self):
        self.stripped_headers = self.replace_in_source(r'import\s*(\w|[-_=.^%$#!*]|\s)*;', '')

    def pre_compile(self):
        self.check_bad_words()
        self.strip_headers()
        self.prefix_source(self.get_headers())

    def get_headers(self):
        """
        Converts the list of headers in self.config into a string that can be put at the beginning of the source file
        """
        out = ''
        for header in self.config['headers']:
            out += 'import %s;\n' % header

        return out

    def compile(self):
        """
        Compiles the source file using the compiler information specified in the config file
        :raises CompileError
        """
        self.error_path = path.join(self.submission_dir, 'compile-err.txt')
        compiler_path = path.join(self.config['java_path'], self.config['compiler_name'])

        with open(self.error_path, 'w') as error_file:
            args = [compiler_path, self.source_path] + self.config['compiler_flags']
            try:
                subprocess.run(args, stderr=error_file, check=True)

            except subprocess.CalledProcessError as err:
                raise CompileError(err.returncode)

    def get_bare_execute_cmd(self):
        jvm_path = path.join(self.config['java_path'], self.config['jvm_name'])
        return [jvm_path] + self.config['jvm_flags'] + [self.config['main_class_name']]
