import subprocess
import shutil
import re
import os
from os import path
from configparser import ConfigParser
from glob import glob

from .submission_results import *

ERROR_FILE_NAME = 'error.txt'
CHROOT_WRAPPER_NAME = 'chroot_wrapper.exe'
MAX_OUTPUT_SIZE = 1000 * 1000  # Number of bytes which can be written by a submission (1M)


class Submission:
    """
    Represents a single source file submitted to the contest

    This class is meant to be a parent class for other classes that can add language-specific functionality.
    It turns out, however, that most of the functionality is language-independent (which is why this is ~400 lines)
    """
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
        self.jail_dir = None  # Should be set by child class

        self.in_paths = []  # paths to the .in files of the problem's test data
        self.compare_out_paths = []  # paths to the files outputted by the submitted solution given an in_file
        self.correct_out_paths = []  # paths to the correct output for this problem

        self.results = SubmissionResults()

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
        self.correct_out_paths.sort()

        self.compare_out_paths = []
        for out_path in self.correct_out_paths:
            out_name = path.split(out_path)[-1]
            out_path = path.join(self.submission_dir, out_name)
            self.compare_out_paths.append(out_path)

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
                err_path = path.join(self.submission_dir, ERROR_FILE_NAME)
                self.results.report_pre_exec_error(EFORBIDDEN, ERROR_FILE_NAME,
                                                   'Forbidden word in source: %s' % word, err_path)
                return

    def pre_compile(self):
        self.strip_headers()
        self.check_bad_words()
        self.insert_headers()

    def compile(self):
        raise NotImplementedError()

    def get_bare_execute_cmd(self):
        """
        :return: The command needed to run the compiled code without any piping etc
        """
        return self.executable_path

    def get_chroot_options(self):
        """
        Chroot_wrapper takes an options argument which can be 0, 1, or 2.
        0 does nothing special
        1 mounts the proc filesystem which is necessary for java
        2 mounts the urandom filesystem which is necessary for python
        """
        options = 0
        if 'mount_proc_fs' in self.config and self.config['mount_proc_fs'] == 'True':
            options = 1
        if 'mount_urandom' in self.config and self.config['mount_urandom'] == 'True':
            if options == 1:
                raise ValueError("Cannot mount both urandom and proc fs. Check the language's .ini file")
            options = 2

        return options

    def get_chroot_command(self, input_path, output_path):
        """
        Strings together a call to chroot_wrapper for the given input and output files
        :return: a list that can be handed to subprocess.run
        """
        if self.config['ignore_stderr']:
            stderr = output_path + ".err"  # /dev/null was not an option and this file will be ignored
        else:
            stderr = output_path

        log_name = path.split(self.submission_dir)[-1] + "-" + path.split(input_path)[-1]

        return [path.join(self.dirs['base'], CHROOT_WRAPPER_NAME),
                str(self.get_chroot_options()),
                self.jail_dir,
                self.get_bare_execute_cmd(),
                input_path,
                output_path,
                stderr,
                log_name,
                str(MAX_OUTPUT_SIZE)]

    def execute(self):
        """
        Executes the command given by get_bare_execute_command once per input/output file combination.
        If something goes wrong, a EMAXOUTPUT, ERUNTIME, or ETIMEOUT error will be reported

        We are using subprocess.Popen here instead of subprocess.run because we need to send a SIGTERM signal to
            the child instead of a SIGKILL so that chroot_wrapper can kill it's child process
        """
        for input_path, output_path in zip(self.in_paths, self.compare_out_paths):
            input_name = path.split(input_path)[-1]
            output_name = path.split(output_path)[-1]

            with subprocess.Popen(self.get_chroot_command(input_path, output_path)) as child_process:
                try:
                    child_process.wait(timeout=self.config['max_cpu_time'])

                except subprocess.TimeoutExpired:
                    child_process.terminate()
                    child_process.wait()
                    self.results.add_sub_judgment(ETIMEOUT, input_name, output_name,
                                                  'The program took too long to execute', output_path)
                    continue

                if child_process.returncode != 0:
                    if child_process.returncode == -25:  # a negative number indicates a signal
                        self.results.add_sub_judgment(EMAXOUTPUT, input_name, output_name)
                    else:
                        self.results.add_sub_judgment(ERUNTIME, input_name, output_name, error_no=child_process.returncode)

    def move_to_judged(self):
        """
        Moves the submitted file into the judged directory and populates several self.*_path variables
        """
        base_name = path.split(self.submission_dir)[-1]
        new_dir = path.join(self.dirs['judged'], base_name)
        shutil.move(self.submission_dir, new_dir)
        self.submission_dir = new_dir

        source_name = path.split(self.source_path)[-1]
        self.source_path = path.join(self.submission_dir, source_name)

        self.get_io_paths()

    def insert_jail_path(self, old_path):
        """
        Adds the jail path to the beginning of the given path.
        The given path may be absolute or relative
        """
        if path.isabs(old_path):
            old_path = old_path[1:]

        return path.join(self.jail_dir, old_path)

    def move_to_jail(self):
        shutil.copytree(self.submission_dir, self.insert_jail_path(self.submission_dir))
        for input_path in self.in_paths:
            shutil.copy(input_path, self.insert_jail_path(input_path), follow_symlinks=True)

    def move_from_jail(self):
        for output_path in self.compare_out_paths:
            shutil.copy(self.insert_jail_path(output_path), output_path, follow_symlinks=True)
        shutil.rmtree(self.insert_jail_path(self.submission_dir[1:]))

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

    def validate_path_lists(self):
        if not (len(self.in_paths) == len(self.compare_out_paths) == len(self.correct_out_paths) != 0):
            raise RuntimeError("Something is wrong with the list of input and output files:\n "
                               "in_paths: %s\n"
                               "compare_out_paths: %s\n"
                               "correct_out_paths: %s" %
                               (repr(self.in_paths), repr(self.compare_out_paths), repr(self.correct_out_paths)))

    def judge_output(self):
        """
        Determines if the solution is correct by comparing its output files to the correct output files
        If the output is determined to be incorrect, the other tests will still be run
        """
        self.validate_path_lists()

        for in_path, correct_out_path, compare_out_path in \
                zip(self.in_paths, self.correct_out_paths, self.compare_out_paths):

            in_name = path.split(in_path)[-1]
            compare_out_name = path.split(compare_out_path)[-1]

            if self.results.input_file_has_judgement(in_name):
                continue

            # This path is not stored in the database but is instead inferred from the output file name
            diff_path = compare_out_path + '.diff'

            if not self.diff(correct_out_path, compare_out_path, diff_path):
                if not self.diff(correct_out_path, compare_out_path, no_ws=True):
                    self.results.add_sub_judgment(EINCORRECT, in_name, compare_out_name)
                else:
                    self.results.add_sub_judgment(EFORMAT, in_name, compare_out_name)
            else:
                self.results.add_sub_judgment(CORRECT, in_name, compare_out_name)

    def get_results(self):
        """
        This is the only function the user should need to call; it does everything necessary to judge the submission
        :return: A SubmissionResults object which describes the results of attempting to process the submission
        """
        self.move_to_judged()
        self.pre_compile()
        if self.results.is_err():
            return self.results

        self.compile()
        if self.results.is_err():
            return self.results

        self.move_to_jail()
        self.execute()
        self.move_from_jail()
        self.judge_output()
        return self.results
