import fcntl
import traceback
import sys
from os import path
from os import mkdir

from py_lib.c_submission import CSubmission
from py_lib.java_submission import JavaSubmission
from py_lib.python_submission import PythonSubmission
from py_lib.db_driver import DBDriver
from py_lib.submission_results import *
from py_lib.submission import ERROR_FILE_NAME


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
        print('Failed to acquire file lock. Exiting.', file=sys.stderr)
        exit(1)


def release_lock(file_handle):
    """
    Releases the lock created in acquire_lock so that it can be used by other instances of this script
    :param file_handle: The same parameter you passed to acquire_lock
    """
    fcntl.lockf(file_handle, fcntl.LOCK_UN)


def report_error(judgement_code, one_submission_info, db_driver: DBDriver):
    """
    Reports an EFILETYPE or EUNKNOWN error to the database
    :param judgement_code: EFILETYPE or EUNKNOWN
    :param one_submission_info: a row from the QUEUED_SUBMISSIONS table
    :param db_driver: a DBDriver object
    """
    if judgement_code == EFILETYPE:
        message = 'Unknown file type'
    elif judgement_code == EUNKNOWN:
        message = 'Something went wrong, probably on our end. Check public_html/judge/errorLog.txt'
    else:
        raise ValueError('report_error cannot handle the error code %d' % judgement_code)

    results = SubmissionResults()
    judged_dir = db_driver.dirs['judged']
    submission_dir = '%d-%d-%d' % one_submission_info[1:4]
    submission_dir = path.join(judged_dir, submission_dir)
    if not path.isdir(submission_dir):
        mkdir(submission_dir)

    error_path = path.join(submission_dir, ERROR_FILE_NAME)
    results.report_pre_exec_error(judgement_code, ERROR_FILE_NAME, message, error_path)

    judgement_info = results.get_auto_judgements()[0]
    db_driver.report_auto_judgement(one_submission_info[0], **judgement_info)


def construct_submission(one_submission_info, db_driver: DBDriver):
    """
    Determines the correct Submission class to use and constructs one
    :raises UndefinedFileTypeError
    """
    source_name = one_submission_info[5]
    extension = os.path.splitext(source_name)[1]
    extension = extension.lower()

    if extension not in EXTENSION_NAME:
        return None

    submission_dir = '%d-%d-%d' % one_submission_info[1:4]
    lang_name = EXTENSION_NAME[extension]
    lang_info = db_driver.get_language_info(lang_name)
    class_to_construct = NAME_CLASS[lang_name]

    return class_to_construct(lang_name=lang_name,
                              submission_dir=submission_dir,
                              source_name=source_name,
                              dirs=db_driver.dirs,
                              problem_id=one_submission_info[2],
                              max_cpu_time=lang_info[2],
                              jail_dir=lang_info[3],
                              replace_headers=bool(lang_info[4]),
                              check_bad_words=bool(lang_info[5]),
                              ignore_stderr=db_driver.get_ignore_stderr(),
                              forbidden_words=db_driver.get_forbidden(lang_info[0]),
                              headers=db_driver.get_headers(lang_info[0]))


def judge_submissions(db_driver: DBDriver):
    """
    Creates one or more entries in the AUTO_RESPONSE table for every queued submission
    """
    for row in db_driver.get_submission_info():
        sub_id = db_driver.report_pending(row)

        try:
            submission = construct_submission(row, db_driver)

            if submission is None:
                report_error(EFILETYPE, row, db_driver)
                continue
            else:
                results = submission.get_results()

        except:
            report_error(EUNKNOWN, row, db_driver)
            traceback.print_exc()
            continue

        if results.get_overall_judgement_code() is None:
            report_error(EUNKNOWN, row, db_driver)
            print("Something goofed when getting the overall judgement code", file=sys.stderr)
            continue

        for judgement in results.get_auto_judgements():
                db_driver.report_auto_judgement(sub_id, **judgement)


def main():
    with DBDriver() as db:
        lock_file = open(os.path.join(db.dirs['base'], "lockfile.lock"), 'w')
        acquire_lock(lock_file)

        judge_submissions(db)

    release_lock(lock_file)


if __name__ == '__main__':
    main()
