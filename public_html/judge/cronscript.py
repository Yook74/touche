import fcntl
import os

from py_lib.c_submission import CSubmission
from py_lib.cpp_submission import CppSubmission
from py_lib.java_submission import JavaSubmission
from py_lib.python_submission import PythonSubmission
from py_lib.db_driver import DBDriver
from py_lib.exceptions import *


EXTENSION_CLASS = {  # TODO .py2 extension
    '.c': CSubmission,
    '.cpp': CppSubmission,
    '.cc': CppSubmission,
    '.java': JavaSubmission,
    '.py': PythonSubmission}

NAME_CLASS = {  # Associates the names used in the database with the Submission Classes
    'C': CSubmission,
    'CXX': CppSubmission,
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


def setup_classes(db_driver: DBDriver):
    """
    The classes have some static attributes which need to be filled out with information in the database.
    This function grabs the database info and puts it into those static attributes
    """
    for tupl in db_driver.get_language_info():
        lang_id = tupl[0]
        lang_name = tupl[1]
        NAME_CLASS[lang_name].lang_max_cpu_time = tupl[2]  # TODO not multiply by 1.1
        NAME_CLASS[lang_name].lang_chroot_dir = os.path.join(db_driver.dirs['base'], tupl[3])
        NAME_CLASS[lang_name].lang_replace_headers = bool(tupl[4])
        NAME_CLASS[lang_name].lang_check_bad_words = bool(tupl[5])
        NAME_CLASS[lang_name].lang_forbidden_words = db_driver.get_forbidden(lang_id)
        NAME_CLASS[lang_name].lang_headers = db_driver.get_headers(lang_id)


def construct_submission(one_submission_info, dirs):
    """
    Determines the correct Submission class to use and constructs one
    :raises UndefinedFileTypeError
    """
    file_name = one_submission_info[5]
    extension = os.path.splitext(file_name)[1]
    extension = extension.lower()

    if extension in EXTENSION_CLASS:
        # Constructs a Submission object
        return EXTENSION_CLASS[extension](dirs, one_submission_info[3], one_submission_info[5])
    else:
        raise UndefinedFileTypeError(extension)


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
    for row in db_driver.get_submission_info():
        sub_id = db_driver.report_pending(row)

        try:
            submission = construct_submission(row, db_driver.dirs)
            process_submission(submission)

        except UndefinedFileTypeError as err:
            db_driver.report_judgement(sub_id, 1, err.message)

        except ForbiddenWordError as err:
            db_driver.report_judgement(sub_id, 2, err.message)

        except CompileError as err:
            db_driver.report_judgement(sub_id, 3, err.message)

        except TimeExceededError as err:
            db_driver.report_judgement(sub_id, 4, err.message)

        except ExternalRuntimeError as err:
            db_driver.report_judgement(sub_id, 5, err.message)

        except IncorrectOutputError as err:
            db_driver.report_judgement(sub_id, 6, err.message)

        except FormatError as err:
            db_driver.report_judgement(sub_id, 7, err.message)
        # TODO handle other errors?
        else:  # Accepted
            db_driver.report_judgement(sub_id, 9, "Accepted")


def main():
    with DBDriver() as db:
        lock_file = open(os.path.join(db.dirs['base'], "lockfile.lock"), 'w')

        acquire_lock(lock_file)
        setup_classes(db)

        judge_submissions(db)

    release_lock(lock_file)


if __name__ == '__main__':
    main()
