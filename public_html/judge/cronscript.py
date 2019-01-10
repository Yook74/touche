import fcntl
import os

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
                              source_name=one_submission_info[5],
                              max_cpu_time=lang_info[2],
                              jail_dir=lang_info[3],
                              replace_headers=bool(lang_info[4]),
                              check_bad_words=bool(lang_info[5]),
                              forbidden_words=db_driver.get_forbidden(lang_info[0]),
                              headers=db_driver.get_headers(lang_info[0]))


def process_submission(submission):
    """
    Performs all the steps necessary to determine if a submission is correct.
    :raises UndefinedFileTypeError
    """
    submission.move_to_judged()
    submission.replace_headers()
    submission.check_bad_words()
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
            db_driver.report_judgement(sub_id, 1, '')

        except ForbiddenWordError as err:
            db_driver.report_judgement(sub_id, 2, '')

        except CompileError as err:
            db_driver.report_judgement(sub_id, 3, '')

        except TimeExceededError as err:
            db_driver.report_judgement(sub_id, 4, '')

        except ExternalRuntimeError as err:
            db_driver.report_judgement(sub_id, 5, '')

        except IncorrectOutputError as err:
            db_driver.report_judgement(sub_id, 6, '')

        except FormatError as err:
            db_driver.report_judgement(sub_id, 7, '')
        # TODO handle other errors?
        else:  # Accepted
            db_driver.report_judgement(sub_id, 9, '')


def main():
    with DBDriver() as db:
        lock_file = open(os.path.join(db.dirs['base'], "lockfile.lock"), 'w')
        acquire_lock(lock_file)

        judge_submissions(db)

    release_lock(lock_file)


if __name__ == '__main__':
    main()
