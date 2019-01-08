import subprocess
from os import path
from os import stat as file_stat
from configparser import ConfigParser
from glob import glob

from .exceptions import *


class Submission:
    lang_max_cpu_time = None
    lang_chroot_dir = None
    lang_replace_headers = None
    lang_check_bad_words = None
    lang_forbidden_words = None
    lang_headers = None

    def __int__(self, dirs, problem_id, source_name, config_path=None):
        """
        :param dirs: DBDriver dirs dictionary
        :param problem_id: This identifies the problem and is used to name things
        :param source_name: The name of the submitted source file
        :param config_path: a path to an .ini file that specifies configuration info for this submission.
        """
        self.dirs = dirs
        self.problem_id = problem_id

        self.source_path = path.join(dirs['queue'], source_name)
        self.base_name, self.source_extension = path.splitext(source_name)

        self.executable_path = None
        self.error_path = None

        self.in_paths = []
        self.compare_out_paths = []
        self.correct_out_paths = []
        self.get_io_paths()

        self.config = None
        if config_path is not None:
            self.parse_config(config_path)

    def parse_config(self, config_path):
        """
        Parses the config file.
        For now, only the keys and values in the [config] section of the specified file will be used.
        I recommend you use colons in the config file but the equals sign is also an acceptable key value delimiter
        :param config_path: path ending in .ini
        """
        self.config = ConfigParser()
        successful_files = self.config.read(config_path)

        if len(successful_files) != 1:
            raise IOError("Something went wrong when opening file %s" % path.abspath(config_path))

        self.config = self.config['config']  # these files only have one section

    def get_bare_execute_cmd(self):
        """
        :return: The command needed to run the compiled code without any piping etc
        """
        return self.executable_path

    def execute_one_test(self, input_path, output_path):
        try:
            subprocess.run([self.get_bare_execute_cmd(),
                            "<",
                            input_path,
                            ">",
                            output_path,
                            "2>&1"],
                           timeout=self.lang_max_cpu_time,
                           check=True)  # raises CalledProcessError if it fails
        except subprocess.TimeoutExpired as err:
            raise TimeExceededError()

        except subprocess.CalledProcessError as err:
            raise ExternalRuntimeError()

    def get_io_paths(self):
        """
        Populates the list of in_paths, correct_out_paths, and compare_out_paths
        """
        glob_path = self.dirs['data'] + '/' + str(self.problem_id) + '_*'
        self.in_paths = glob(glob_path + '.in')
        self.correct_out_paths = glob(glob_path + '.out')

        self.compare_out_paths = []
        for in_path in self.correct_out_paths:
            out_name = path.split(in_path)[-1]
            out_name = self.base_name + "_" + out_name
            out_path = path.join(self.dirs['judged'], out_name)

            self.compare_out_paths.append(out_path)

    def move_to_judged(self):
        """
        Moves the submitted file into the judged directory
        """
        new_source_path = path.join(self.dirs['judged'], self.base_name + self.source_extension)
        subprocess.run(['mv', self.source_path, new_source_path], check=True)
        self.source_path = new_source_path

    def replace_headers(self):
        raise NotImplementedError()

    def check_bad_words(self):
        """
        If lang_check_bad_words is set, look through the source file for the words in lang_forbidden_words
        :raises ForbiddenWordError
        TODO handle #define thingy
        """
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
        reported_error = None
        for in_path, compare_out_path in zip(self.in_paths, self.compare_out_paths):
            try:
                self.execute_one_test(in_path, compare_out_path)

            except (IncorrectOutputError, FormatError) as thrown:
                if not isinstance(reported_error, ExternalRuntimeError):
                    reported_error = thrown

            except ExternalRuntimeError as thrown:
                reported_error = thrown

        if reported_error is not None:
            raise reported_error

    def move_from_jail(self):
        pass

    def judge_output(self):
        reported_error = None
        for correct_out_path, compare_out_path in zip(self.correct_out_paths, self.compare_out_paths):
            diff_path = correct_out_path + '.diff'
            no_ws_diff_path = diff_path + 'no_ws'

            subprocess.run(['diff', '-u', correct_out_path, compare_out_path, '>', diff_path])

            if file_stat(diff_path).st_size != 0:
                subprocess.run(['diff', '-b', '-B', correct_out_path, compare_out_path, '>', no_ws_diff_path])
                if file_stat(no_ws_diff_path).st_size != 0:
                    reported_error = IncorrectOutputError()
                else:
                    if reported_error is not None:
                        reported_error = FormatError()
            else:
                pass  # This was correct

        if reported_error is not None:
            raise reported_error
