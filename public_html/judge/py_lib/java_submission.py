from .submission import *

from os import path
import subprocess

CONFIG_PATH = 'py_lib/java_config.ini'


class JavaSubmission(Submission):
    def __init__(self, **kwargs):
        super().__init__(**kwargs, config_path=CONFIG_PATH)

        self.jail_dir = path.join(self.dirs['base'], self.config['jail_name'])

    def strip_headers(self):
        self.stripped_headers = self.replace_in_source(r'(import\s+[^;]+;)', '')
        self.stripped_headers = '\n'.join(self.stripped_headers)
        self.stripped_headers += '\n'

    def pre_compile(self):
        self.check_bad_words()
        self.strip_headers()
        self.insert_headers()

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
        compiler_path = path.join(self.config['java_path'], self.config['compiler_name'])
        error_path = path.join(self.submission_dir, ERROR_FILE_NAME)

        with open(error_path, 'w') as error_file:
            args = [compiler_path, self.source_path] + self.config['compiler_flags']
            try:
                subprocess.run(args, stderr=error_file, check=True)

            except subprocess.CalledProcessError as err:
                self.results.report_pre_exec_error(ECOMPILE, ERROR_FILE_NAME, error_no=err.returncode)

    def get_bare_execute_cmd(self):
        java_chdir = "-Duser.dir=%s" % self.submission_dir  # This makes java run the Main class in the submission dir
        jvm_path = path.join(self.config['java_path'], self.config['jvm_name'])
        args = [jvm_path] + self.config['jvm_flags'] + [java_chdir, self.config['main_class_name']]
        return " ".join(args)
