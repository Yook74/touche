import subprocess
from os import path
from os import stat as file_stat
from configparser import ConfigParser
from glob import glob

from .exceptions import *


class Submission:
    lang_max_cpu_time = None  # Maximum time allowed for the compiled solution to run
    lang_chroot_dir = None  # directory of this language's jail
    lang_replace_headers = None  # True if this language should replace headers in the submitted source file
    lang_check_bad_words = None  # True if this language checks for forbidden words in the submitted source file
    lang_forbidden_words = None  # The list of forbidden words for this language (or None)
    lang_headers = None  # The list of headers for this language (or None)

    def __init__(self, dirs, problem_id, source_name, config_path=None):
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

        self.in_paths = []  # paths to the .in files of the problem's test data
        self.compare_out_paths = []  # paths to the files outputted by the submitted solution given an in_file
        self.correct_out_paths = []  # paths to the correct output for this problem
        self.get_io_paths()

        self.config = None
        if config_path is not None:
            self.parse_config(config_path)

# Helpers

    def parse_config(self, config_path):
        """
        Parses the config file.
        I recommend you use colons in the config file but the equals sign is also an acceptable key value delimiter.
        See c_config.ini for an example.
        This expands the items in the [list] section into python lists and merges both sections into a single flat dict.
        :param config_path: path ending in .ini
        """
        self.config = {}

        parsed_config = ConfigParser()
        successful_files = parsed_config.read(config_path)

        if len(successful_files) != 1:
            raise IOError("Something went wrong when opening file %s" % path.abspath(config_path))

        for key in parsed_config['list']:
            self.config[key] = parsed_config['list'][key].split(' ')

        self.config.update(dict(parsed_config['singleton']))  # Adds the singleton items into the config

    def get_io_paths(self):
        """
        Populates the list of in_paths, correct_out_paths, and compare_out_paths
        """
        glob_path = self.dirs['data'] + '/' + str(self.problem_id) + '_*'
        self.in_paths = list(glob(glob_path + '.in'))
        self.in_paths.sort()

        self.correct_out_paths = list(glob(glob_path + '.out'))
        self.in_paths.sort()

        self.compare_out_paths = []
        for in_path in self.correct_out_paths:
            out_name = path.split(in_path)[-1]
            out_name = self.base_name + "_" + out_name
            out_path = path.join(self.dirs['judged'], out_name)

            self.compare_out_paths.append(out_path)

    def get_bare_execute_cmd(self):
        """
        :return: The command needed to run the compiled code without any piping etc
        """
        return self.executable_path

    def execute_one_test(self, input_path, output_path):
        """
        Executes the command from get_bare_execute_cmd with piping to the given input and output files
        :param input_path: path to one of the input files for a problem
        :param output_path: path to an empty file which will be written to by the executed program
        :raises TimeExceededError if lang_max_cpu_time is exceeded
        :raises ExternalRuntimeError if the executable produces a runtime error
        """
        input_file = open(input_path, 'r')
        output_file = open(output_path, 'w')

        try:
            subprocess.run(self.get_bare_execute_cmd(),
                           stdin=input_file,
                           stdout=output_file,
                           stderr=output_file,
                           timeout=self.lang_max_cpu_time,
                           check=True)  # raises CalledProcessError if it fails
        except subprocess.TimeoutExpired as err:
            raise TimeExceededError()

        except subprocess.CalledProcessError as err:
            raise ExternalRuntimeError()

        finally:
            input_file.close()
            output_file.close()

    @staticmethod
    def diff(correct_path, compare_path, diff_path, no_ws):
        """
        Uses the linux diff tool to determine if the files located by correct_path and compare_path are the same
        :param correct_path: The path to the known correct file
        :param compare_path: The path to the questionable file
        :param diff_path: The output of the diff will be placed here
        :param no_ws: True if the diff should ignore whitespace characters
        :return: True if compare_path and correct_path are the same and False otherwise

        Why are we using the linux diff tool? Because that's the way it's always been done
        """
        if no_ws:
            flags = ['-b', '-B']
        else:
            flags = ['-u']

        diff_file = open(diff_path, 'w')
        args = ['diff'] + flags + [correct_path, compare_path]
        subprocess.run(args, stdout=diff_file)
        diff_file.close()

        return file_stat(diff_path).st_size == 0

# API methods

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
        """
        Executes the compiled code for all of the test cases.
        If the compiled submission produces a runtime error, the other test cases will still be run,
        but the runtime error will be re-raised afterward
        """
        reported_error = None
        for in_path, compare_out_path in zip(self.in_paths, self.compare_out_paths):
            try:
                self.execute_one_test(in_path, compare_out_path)

            except ExternalRuntimeError as thrown:
                reported_error = thrown

        if reported_error is not None:
            raise reported_error

    def move_from_jail(self):
        pass

    def judge_output(self):
        """
        Determines if the solution is correct by comparing its output files to the correct output files
        :raises IncorrectOutputError if the output is definitely wrong
        :raises FormatError if the output is only different with whitespace characters
        If the output is determined to be incorrect, the other tests will be run before the error is raised
        """
        reported_error = None
        for correct_out_path, compare_out_path in zip(self.correct_out_paths, self.compare_out_paths):
            diff_path = path.splitext(compare_out_path)[0] + '.diff'
            no_ws_diff_path = diff_path + '.no_ws'

            if self.diff(correct_out_path, compare_out_path, diff_path, False):
                if self.diff(compare_out_path, compare_out_path, no_ws_diff_path, True):
                    reported_error = IncorrectOutputError()
                else:
                    if reported_error is None:
                        reported_error = FormatError()
            else:
                pass  # This was correct

        if reported_error is not None:
            raise reported_error
