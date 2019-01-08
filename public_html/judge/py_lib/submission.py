from subprocess import run
from os import path
from configparser import ConfigParser

from .exceptions import *


class Submission:
    lang_max_cpu_time = None
    lang_chroot_dir = None
    lang_replace_headers = None
    lang_check_bad_words = None
    lang_forbidden_words = None
    lang_headers = None

    def __int__(self, dirs, problem_id, source_name, config_path=None):
        self.dirs = dirs
        self.problem_id = problem_id

        self.source_path = path.join(dirs['queue'], source_name)
        self.base_name, self.source_extension = path.splitext(source_name)

        self.executable_path = None
        self.error_path = None

        self.config = None
        if config_path is not None:
            self.parse_config(config_path)

    def parse_config(self, config_path):
        self.config = ConfigParser()
        successful_files = self.config.read(config_path)

        if len(successful_files) != 1:
            raise IOError("Something went wrong when opening file %s" % path.abspath(config_path))

        self.config = self.config['config']  # these files only have one section

    def move_to_judged(self):
        new_source_path = path.join(self.dirs['judged'], self.base_name + self.source_extension)
        run(['mv', self.source_path, new_source_path])
        self.source_path = new_source_path

    def replace_headers(self):
        raise NotImplementedError()

    def check_bad_words(self):
        if not self.lang_check_bad_words:
            return

        with open(self.source_path, 'r') as handle:
            file_string = handle.read()

        for word in self.lang_forbidden_words:
            if word in file_string:
                raise ForbiddenWordError(word)

    def compile(self):
        raise NotImplementedError()

    def move_to_jail(self):
        pass

    def execute(self):
        raise NotImplementedError()

    def move_from_jail(self):
        pass

    def judge_output(self):
        raise NotImplementedError()
