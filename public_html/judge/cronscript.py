import fcntl
import os

from py_lib.c_submission import CSubmission
from py_lib.cpp_submission import CppSubmission
from py_lib.java_submission import JavaSubmission
from py_lib.python_submission import PythonSubmission
from py_lib.db_driver import DBDriver
from py_lib.exceptions import UndefinedFileTypeError


EXTENSION_CLASS = {  # TODO .py2 extension? .C extension?
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
    :param db_conn: a connection to the database
    """
    for tupl in db_driver.get_language_info():
        lang_id = tupl[0]
        lang_name = tupl[1]
        NAME_CLASS[lang_name].lang_max_cpu_time = tupl[2]  # TODO not multiply by 1.1
        NAME_CLASS[lang_name].lang_chroot_dir = os.path.join(db_driver.dirs['base'], tupl[3])
        NAME_CLASS[lang_name].lang_replace_headers = bool(tupl[4])
        NAME_CLASS[lang_name].lang_check_bad_words = bool(tupl[5])
        NAME_CLASS[lang_name].lang_forbidden_words = db_driver.get_forbidden(lang_id)
        

def construct_submission(one_submission_info, dirs):
    """
    Determines the correct Submission class to use and constructs one
    :raises UndefinedFileTypeError
    """
    file_name = one_submission_info[5]
    extension = os.path.splitext(file_name)[1]
    extension = extension.lower()

    if extension in EXTENSION_CLASS:
        return EXTENSION_CLASS[extension](dirs, one_submission_info)  # Constructs a Submission object
    else:
        raise UndefinedFileTypeError(
            "%s is not a recognised file extension. Make sure you're submitting your source code" % extension)


def process_submission(one_submission_info, dirs):
    """
    Performs all the steps necessary to determine if a submission is correct.
    :param one_submission_info: A row from the QUEUED_SUBMISSIONS table
    :param dirs: the directory dictionary in DBDriver
    :raises UndefinedFileTypeError
    """
    submission = construct_submission(one_submission_info, dirs)
    submission.move_to_judged()
    submission.replace_headers()
    submission.check_bad_words()
    submission.compile()
    submission.move_to_jail()
    submission.execute()
    submission.move_from_jail()
    submission.judge_output()
    # TODO report success


def judge_submissions(submission_info, dirs):
    for row in submission_info:
        try:
            process_submission(row, dirs)
        except UndefinedFileTypeError:
            pass


def main():
    with DBDriver() as db:
        lock_file = open(os.path.join(db.dirs['base'], "lockfile.lock"), 'w')

        acquire_lock(lock_file)
        setup_classes(db)

        judge_submissions(db.get_submission_info(), db.dirs)

    release_lock(lock_file)


if __name__ == '__main__':
    main()
