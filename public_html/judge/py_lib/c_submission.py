from .submission import *

import shutil
from os import path
import subprocess

C_CONFIG_PATH = 'py_lib/c_config.ini'
CPP_CONFIG_PATH = 'py_lib/cpp_config.ini'


class CSubmission(Submission):
    """
    This handles both C and C++
    """
    def __init__(self, **kwargs):
        if kwargs['lang_name'] == 'CXX':
            config_path = CPP_CONFIG_PATH
        elif kwargs['lang_name'] == 'C':
            config_path = C_CONFIG_PATH
        else:
            raise ValueError("The CSubmission class only handles C and CXX (C++) submissions. "
                             "'%s' is not a valid lang_name" % kwargs['lang_name'])

        super().__init__(**kwargs, config_path=config_path)

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
        temp_path = path.join(self.submission_dir, 'temp.tmp')
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
        self.executable_path = path.join(self.submission_dir, 'executable')
        error_path = path.join(self.submission_dir, ERROR_FILE_NAME)
        with open(error_path, 'w') as error_file:

            args = [self.config['compiler']] + \
                   self.config['compiler_flags'] + \
                   [self.executable_path, self.source_path] + \
                   self.config['linker_flags']
            try:
                subprocess.run(args, stderr=error_file, check=True)

            except subprocess.CalledProcessError as err:
                self.results.report_pre_exec_error(ECOMPILE, ERROR_FILE_NAME, error_no=err.returncode)

