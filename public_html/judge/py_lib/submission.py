import subprocess
import shutil
import re
from os import path, chdir
from configparser import ConfigParser
from glob import glob

from .exceptions import *


class Submission:
    def __init__(self, **kwargs):
        """
        :param lang_name: C, CXX, JAVA, Python2, or Python3
        :param source_name: The name of the submitted source file
        :param submission_dir: The name of the directory containing the submission's file(s) (not an absolute path)
        :param dirs: DBDriver dirs dictionary
        :param problem_id: This identifies the problem and is used to name things
        :param max_cpu_time: Maximum time allowed for the compiled solution to run
        :param jail_dir: directory of this language's jail
        :param replace_headers: True if this language should replace headers in the submitted source file
        :param check_bad_words: True if this language checks for forbidden words in the submitted source file
        :param ignore_stderr: True if stderr should be ignored when executing the submission
        :param forbidden_words: The list of forbidden words for this language (or None)
        :param headers: The list of headers for this language (or None)
        :param config_path: a path to an .ini file that specifies configuration info for this submission (or None).
        """
        self.lang_name = kwargs['lang_name']
        self.dirs = kwargs['dirs']
        self.problem_id = kwargs['problem_id']
        self.submission_dir = path.join(self.dirs['queue'], kwargs['submission_dir'])

        self.source_path = path.join(self.submission_dir, kwargs['source_name'])
        self.source_extension = path.splitext(kwargs['source_name'])[1]

        self.config = {key: kwargs[key] for key in ['max_cpu_time', 'jail_dir', 'replace_headers',
                                                    'check_bad_words', 'ignore_stderr', 'forbidden_words', 'headers']}

        if 'config_path' in kwargs and kwargs['config_path'] is not None:
            self.parse_config(kwargs['config_path'])

        self.stripped_headers = None
        self.executable_path = None
        self.error_path = None

        self.in_paths = []  # paths to the .in files of the problem's test data
        self.compare_out_paths = []  # paths to the files outputted by the submitted solution given an in_file
        self.correct_out_paths = []  # paths to the correct output for this problem

# Helpers

    def replace_in_source(self, regex, repl):
        """
        Replaces all instances of the given regex with the repl string in the source file
        :return Everything that was deleted from the source file
        """
        with open(self.source_path, 'r+') as source_file:
            file_string = source_file.read()
            matches = re.findall(regex, file_string)
            file_string = re.sub(regex, repl, file_string)

            source_file.seek(0)
            source_file.write(file_string)
            source_file.truncate()

        return matches

    def prefix_source(self, prefix_str):
        """
        Writes the given prefix string to the beginning of the source file
        """
        with open(self.source_path, 'r+') as source_file:
            file_string = source_file.read()
            file_string = prefix_str + file_string

            source_file.seek(0)
            source_file.write(file_string)
            source_file.truncate()

    def parse_config(self, config_path):
        """
        Parses the config file.
        I recommend you use colons in the config file but the equals sign is also an acceptable key value delimiter.
        See c_config.ini for an example.
        This expands the items in the [list] section into python lists and merges both sections into a single flat dict.
        :param config_path: path ending in .ini
        """
        parsed_config = ConfigParser()
        successful_files = parsed_config.read(config_path)

        if len(successful_files) != 1:
            raise IOError("Something went wrong when opening file %s" % path.abspath(config_path))

        for key in parsed_config['list']:
            if parsed_config['list'][key] == '':
                self.config[key] = []
            else:
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
        for out_path in self.correct_out_paths:
            out_name = path.split(out_path)[-1]
            out_path = path.join(self.submission_dir, out_name)
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
        :raises TimeExceededError if max_cpu_time is exceeded
        :raises ExternalRuntimeError if the executable produces a runtime error
        """
        input_file = open(input_path, 'r')
        output_file = open(output_path, 'w')

        kwargs = {'args': self.get_bare_execute_cmd(),
                  'stdin': input_file,
                  'stdout': output_file,
                  'timeout': self.config['max_cpu_time'],
                  'check': True}

        if not self.config['ignore_stderr']:
            kwargs['stderr'] = output_file

        try:
            subprocess.run(**kwargs)

        except subprocess.TimeoutExpired as err:
            raise TimeExceededError()

        except subprocess.CalledProcessError as err:
            raise ExternalRuntimeError()

        finally:
            input_file.close()
            output_file.close()

    @staticmethod
    def diff(correct_path, compare_path, diff_path=None, no_ws=False):
        """
        Uses the linux diff tool to determine if the files located by correct_path and compare_path are the same
        :param correct_path: The path to the known correct file
        :param compare_path: The path to the questionable file
        :param diff_path: The output of the diff will be placed here
        :param no_ws: True if the diff should ignore whitespace characters
        :return: True if compare_path and correct_path are the same and False otherwise
        """
        if no_ws:
            flags = ['-b', '-B', '-q']
        else:
            flags = ['-u']

        args = ['diff'] + flags + [correct_path, compare_path]
        if diff_path is None:
            completed_process = subprocess.run(args, stdout=subprocess.DEVNULL)
        else:
            diff_file = open(diff_path, 'w')
            completed_process = subprocess.run(args, stdout=diff_file)
            diff_file.close()

        if completed_process.returncode == 0:
            return True
        elif completed_process.returncode == 1:
            return False
        else:
            raise subprocess.CalledProcessError(completed_process.returncode, args)

    def strip_headers(self):
        raise NotImplementedError()

    def get_headers(self):
        raise NotImplementedError()

    def insert_headers(self):
        """
        Either inserts the headers in self.config or replaces the previously stripped headers
        """
        if self.config['replace_headers']:
            self.prefix_source(self.get_headers())
        else:
            self.prefix_source(self.stripped_headers)

    def run_preprocessor(self):
        """
        Some languages need to run the preprocessor before they can be checked for forbidden words
        """
        pass

    def check_bad_words(self):
        """
        If check_bad_words is set, look through the source file for the words in forbidden_words
        :raises ForbiddenWordError
        """
        if not self.config['check_bad_words']:
            return

        self.run_preprocessor()

        with open(self.source_path, 'r') as handle:
            file_string = handle.read()

        for word in self.config['forbidden_words']:
            if word in file_string:
                raise ForbiddenWordError(word)

# API methods

    def move_to_judged(self):
        """
        Moves the submitted file into the judged directory
        """
        base_name = path.split(self.submission_dir)[-1]
        new_dir = path.join(self.dirs['judged'], base_name)
        shutil.move(self.submission_dir, new_dir)
        self.submission_dir = new_dir

        source_name = path.split(self.source_path)[-1]
        self.source_path = path.join(self.submission_dir, source_name)

        self.get_io_paths()

    def pre_compile(self):
        self.strip_headers()
        self.check_bad_words()
        self.insert_headers()

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

            except TimeExceededError as thrown:
                if reported_error is None:
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

            if not self.diff(correct_out_path, compare_out_path, diff_path):
                if not self.diff(correct_out_path, compare_out_path, no_ws=True):
                    reported_error = IncorrectOutputError()
                else:
                    if reported_error is None:
                        reported_error = FormatError()
            else:
                pass  # This was correct

        if reported_error is not None:
            raise reported_error
