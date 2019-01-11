import fcntl
import os
import re

from py_lib.c_submission import CSubmission
from py_lib.java_submission import JavaSubmission
from py_lib.python_submission import PythonSubmission
from py_lib.db_driver import DBDriver
from py_lib.exceptions import *


EXTENSION_NAME = {  # TODO document .py2 and put this information in the database
    '.c': 'C',
    '.cpp': 'CXX',
    '.cc': 'CXX',
    '.java': 'JAVA',
    '.py': 'Python3',
    '.py2': 'Python2'}

NAME_CLASS = {  # Associates the names used in the database with the Submission Classes
    'C': CSubmission,
    'CXX': CSubmission,
    'JAVA': JavaSubmission,
    'Python2': PythonSubmission,
    'Python3': PythonSubmission}

ERROR_CODE_PATH = 'lib/responses.inc'
error_codes = {}


def parse_error_codes():
    with open(ERROR_CODE_PATH, 'r') as code_file:
        file_string = code_file.read()

    defines = re.findall(r'define\w*\(\w*"[^"]*"\w*,[0-9]*\w*\)\w*;', file_string)
    for define in defines:
        split = define.split('"')
        key = split[1]
        value = split[2]
        value = re.findall(r'[0-9]+', value)[0]
        value = int(value)
        error_codes[key] = value


def acquire_lock(file_handle):
    """
    Keeps other instances of this script from running simultaneously.
    :param file_handle: The return value of a call to open. Should be writable
    """
    try:
        fcntl.lockf(file_handle, fcntl.LOCK_EX | fcntl.LOCK_NB)
    except IOError:
        exit(1)


def release_lock(file_handle):
    """
    Releases the lock created in acquire_lock so that it can be used by other instances of this script
    :param file_handle: The same parameter you passed to acquire_lock
    """
    fcntl.lockf(file_handle, fcntl.LOCK_UN)


def construct_submission(one_submission_info, db_driver: DBDriver):
    """
    Determines the correct Submission class to use and constructs one
    :raises UndefinedFileTypeError
    """
    file_name = one_submission_info[5]
    extension = os.path.splitext(file_name)[1]
    extension = extension.lower()

    if extension not in EXTENSION_NAME:
        raise UndefinedFileTypeError(extension)

    lang_name = EXTENSION_NAME[extension]
    lang_info = db_driver.get_language_info(lang_name)
    class_to_construct = NAME_CLASS[lang_name]

    return class_to_construct(lang_name=lang_name,
                              dirs=db_driver.dirs,
                              problem_id=one_submission_info[2],
                              source_name=file_name,
                              max_cpu_time=lang_info[2],
                              jail_dir=lang_info[3],
                              replace_headers=bool(lang_info[4]),
                              check_bad_words=bool(lang_info[5]),
                              ignore_stderr=db_driver.get_ignore_stderr(),
                              forbidden_words=db_driver.get_forbidden(lang_info[0]),
                              headers=db_driver.get_headers(lang_info[0]))


def process_submission(submission):
    """
    Performs all the steps necessary to determine if a submission is correct.
    """
    submission.move_to_judged()
    submission.pre_compile()
    submission.compile()
    submission.move_to_jail()
    submission.execute()
    submission.move_from_jail()
    submission.judge_output()


def judge_submissions(db_driver: DBDriver):
    """
    Assigns an autoresponse to every queued submission in the database
    """
    for row in db_driver.get_submission_info():
        sub_id = db_driver.report_pending(row)

        try:
            submission = construct_submission(row, db_driver)
            process_submission(submission)

        except UndefinedFileTypeError as err:
            db_driver.report_judgement(sub_id, error_codes['EFILETYPE'], '')

        except ForbiddenWordError as err:
            db_driver.report_judgement(sub_id, error_codes['EFORBIDDEN'], '')

        except CompileError as err:
            db_driver.report_judgement(sub_id, error_codes['ECOMPILE'], '')

        except TimeExceededError as err:
            db_driver.report_judgement(sub_id, error_codes['ERUNLENGTH'], '')

        except ExternalRuntimeError as err:
            db_driver.report_judgement(sub_id, error_codes['ERUNTIME'], '')

        except IncorrectOutputError as err:
            db_driver.report_judgement(sub_id, error_codes['EINCORRECT'], '')

        except FormatError as err:
            db_driver.report_judgement(sub_id, error_codes['EFORMAT'], '')
        # TODO handle other errors?
        else:  # Accepted
            db_driver.report_judgement(sub_id, error_codes['ECORRECT'], '')


def main():
    with DBDriver() as db:
        lock_file = open(os.path.join(db.dirs['base'], "lockfile.lock"), 'w')
        acquire_lock(lock_file)

        parse_error_codes()

        judge_submissions(db)

    release_lock(lock_file)


if __name__ == '__main__':
    main()
