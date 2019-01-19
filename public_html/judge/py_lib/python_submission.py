from .submission import *

from os import path

PYTHON2_CONFIG_PATH = 'py_lib/python2_config.ini'
PYTHON3_CONFIG_PATH = 'py_lib/python3_config.ini'


class PythonSubmission(Submission):
    """
    This handles both Python 2 and 3 submissions
    """
    def __init__(self, **kwargs):
        if kwargs['lang_name'] == 'Python2':
            config_path = PYTHON2_CONFIG_PATH
        elif kwargs['lang_name'] == 'Python3':
            config_path = PYTHON3_CONFIG_PATH
        else:
            raise ValueError("The PythonSubmission class only handles Python2 and Python3 submissions. "
                             "'%s' is not a valid lang_name" % kwargs['lang_name'])

        super().__init__(**kwargs, config_path=config_path)
        self.jail_dir = path.join(self.dirs['base'], self.config['jail_name'])

    def strip_headers(self):
        self.stripped_headers = self.replace_in_source(r'(^\w*import.+$)', '')
        self.stripped_headers = self.replace_in_source(r'(^\w*from.+import.+$)', '')
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
            out += 'import %s\n' % header

        return out

    def compile(self):
        pass  # what's a compiler?

    def get_bare_execute_cmd(self):
        return self.config['interpreter_path'] + " " + self.source_path
