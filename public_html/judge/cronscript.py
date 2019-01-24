"""
Checks, compiles, executes, and judges source files submitted by teams to the contest.
This particular file calls a variety of files found in the py_lib directory.
This file's main job is to get those files to work together properly, where most of the actual functionality is elsewhere.
The intention is that this file will mainly be called by cron. See master-crontab.cron

py_lib/submission.py defines a Submission class which acts as a parent for the CSubmmission, JavaSubmission, and PythonSubmission files.
These files know how to check, compile, execute, and judge source files, but they are completely ignorant of the database.

py_lib/db_driver.py defines a DBDriver class which acts as a layer between this script and the database.

py_lib/submission_results defines a SubmissionResults which the Submission classes use to report the status and results
    of a user's submitted source file.
The methods of a SubmissionResults object allow the Submission class to store results in a way that makes sense to it,
    and allow a DBDriver object to retrieve those data in a way that makes sense to it.

Written by Andrew Blomenberg in collaboration with Jonathan Geisler in Jan 2019
"""

import fcntl
import traceback
import sys
import argparse
import shutil
from os import path
from os import mkdir

from py_lib.c_submission import CSubmission
from py_lib.java_submission import JavaSubmission
from py_lib.python_submission import PythonSubmission
from py_lib.db_driver import DBDriver
from py_lib.submission_results import *
from py_lib.submission import ERROR_FILE_NAME


EXTENSION_NAME = {  # TODO put this information in the database
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

verbose = False
def print_status(message: str):
    if verbose:
        print(message)


def acquire_lock(file_handle, blocking):
    """
    Keeps other instances of this script from running simultaneously.
    :param file_handle: The return value of a call to open. Should be writable
    :param blocking: True if the file lock should be waited for, False will exit on failing to acquire the lock
    """
    print_status("Acquiring file lock")
    flags = fcntl.LOCK_EX

    if not blocking:
        flags |= fcntl.LOCK_NB
    try:
        fcntl.lockf(file_handle, flags)
    except IOError:
        print('Failed to acquire file lock. Exiting.', file=sys.stderr)
        exit(1)


def release_lock(file_handle):
    """
    Releases the lock created in acquire_lock so that it can be used by other instances of this script
    :param file_handle: The same parameter you passed to acquire_lock
    """
    print_status("Releasing file lock")
    fcntl.lockf(file_handle, fcntl.LOCK_UN)


def move_to_queue(judged_row, dirs):
    """
    Moves the source file for judged_row from the judged directory specified by dirs to the queue directory
    Needed for the rejudge feature
    :param judged_row: one row from the JUDGED_SUBMISSIONS table
    :param dirs: the dirs dictionary from DBDriver
    """
    dir_name = get_submission_name(*judged_row[:3])
    source_name = judged_row[4]

    old_source_dir = path.join(dirs['judged'], dir_name)
    new_source_dir = path.join(dirs['queue'], dir_name)
    old_source_path = path.join(old_source_dir, source_name + '.orig')
    new_source_path = path.join(new_source_dir, source_name)

    os.mkdir(new_source_dir)
    shutil.move(old_source_path, new_source_path)
    shutil.rmtree(old_source_dir)


def get_submission_name(team_id, problem_id, timestamp):
    if problem_id is None:
        return '%d-T-%d' % (team_id, timestamp)
    else:
        return '%d-%d-%d' % (team_id, problem_id, timestamp)


def report_error(judgement_code, one_submission_info, judged_id, db_driver: DBDriver):
    """
    Reports an EFILETYPE or EUNKNOWN error to the database
    :param judgement_code: EFILETYPE or EUNKNOWN
    :param one_submission_info: a row from the QUEUED_SUBMISSIONS table
    :param judged_id: The ID of this submission in the JUDGED_SUBMISSIONS table
    :param db_driver: a DBDriver object
    """
    results = SubmissionResults()
    judged_dir = db_driver.dirs['judged']
    queue_dir = db_driver.dirs['queue']
    submission_name = get_submission_name(*one_submission_info[1:4])

    if judgement_code == EFILETYPE:
        submission_dir = path.join(queue_dir, submission_name)
        new_dir = path.join(judged_dir, submission_name)
        shutil.move(submission_dir, new_dir)
        submission_dir = new_dir

        message = 'Unknown file type'
    elif judgement_code == EUNKNOWN:
        submission_dir = path.join(judged_dir, submission_name)
        mkdir(submission_dir)

        message = 'Something went wrong, probably on our end. Check public_html/judge/errorLog.txt'
    else:
        raise ValueError('report_error cannot handle the error code %d' % judgement_code)

    source_path = path.join(submission_dir, one_submission_info[5])
    shutil.copy(source_path, source_path + ".orig")
    error_path = path.join(submission_dir, ERROR_FILE_NAME)
    results.report_pre_exec_error(judgement_code, ERROR_FILE_NAME, message, error_path)

    judgement_info = results.get_auto_judgements()[0]
    db_driver.report_auto_judgement(judged_id, **judgement_info)


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

    submission_dir = get_submission_name(*one_submission_info[1:4])
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


def process_submissions(db_driver: DBDriver, submissions, test_compile=False):
    """
    Creates one or more entries in the AUTO_RESPONSE table for every row in the given list of submissions
    :param db_driver: a connection to the database
    :param submissions: a list of rows from the QUEUED_SUBMISSIONS table
    :param bool test_compile: true if a test compile is desired
    :return: a list containing the IDs of the rows this function inserted into JUDGED_SUBMISSIONS
    """
    id_list = []
    for row in submissions:
        print_status('Reporting submission %s as pending' % get_submission_name(*row[1:4]))
        sub_id = db_driver.report_pending(row)
        id_list.append(sub_id)

        try:
            submission = construct_submission(row, db_driver)

            if submission is None:
                print_status('Undefined File Type')
                report_error(EFILETYPE, row, sub_id, db_driver)
                continue

            else:
                print_status('Judging submission %s' % get_submission_name(*row[1:4]))
                results = submission.get_results(test_compile=test_compile)

        except:
            report_error(EUNKNOWN, row, sub_id, db_driver)
            traceback.print_exc()
            continue

        if results.get_overall_judgement_code() is None:
            report_error(EUNKNOWN, row, sub_id, db_driver)
            print("Something goofed when getting the overall judgement code", file=sys.stderr)
            continue

        for judgement in results.get_auto_judgements():
            print_status("Reporting judgement with code %d" % judgement['judgement_code'])
            db_driver.report_auto_judgement(sub_id, **judgement)

    return id_list


def judge_queued(db_driver: DBDriver, no_human=False):
    """
    Judges all the submissions which are queued for judging
    :param no_human: True if the human judge should be taken out of the loop
    """
    id_list = process_submissions(db_driver, db_driver.get_queued_submissions())

    if no_human:
        for sub_id in id_list:
            db_driver.make_judgement(sub_id)


def test_compile(queue_id, db_driver: DBDriver):
    """
    Performs a test compile on the submission indicated by queue_id
    :param queue_id: the id of one of the rows in the QUEUED_SUBMISSIONS table
    """
    submissions = db_driver.get_queued_submissions(queue_id=queue_id, test_compile=True)
    process_submissions(db_driver, submissions, test_compile=True)


def requeue(db_driver: DBDriver):
    """
    Removes all the entries in the JUDGED_SUBMISSIONS and AUTO_RESPONSES tables
    and queues the removed submissions up to be judged again
    """
    print_status("Re-queuing files")
    for judged_submission in db_driver.empty_judged():
        move_to_queue(judged_submission, db_driver.dirs)
        db_driver.enqueue_submission(*judged_submission)


def parse_args():
    parser = argparse.ArgumentParser()
    parser.add_argument('-v', '--verbose', action='store_true', help='Print status messages to stdout')
    parser.add_argument('-f', '--no-human', action='store_true', help='Take the human judge out of the judging loop')

    modes = parser.add_mutually_exclusive_group()
    modes.add_argument('-c', '--test-compile', type=str, metavar='Queue ID',
                       help="Take the row specified by the given ID out if the QUEUED_SUBMISSIONS table, compile it "
                             "(if applicable) and put the result of the compile in the JUDGED_SUBMISSIONS table")
    modes.add_argument('--rejudge', action='store_true', help='Recalculate all previous judgements')

    args = parser.parse_args()
    global verbose
    verbose = args.verbose
    return args


def main():
    args = parse_args()

    print_status("Connecting to database")
    with DBDriver() as db:
        lock_file = open(os.path.join(db.dirs['base'], "lockfile.lock"), 'w')

        if args.test_compile is not None:
            test_compile(int(args.test_compile), db)

        elif args.rejudge:
            acquire_lock(lock_file, blocking=True)

            requeue(db)
            judge_queued(db, no_human=args.no_human)

            release_lock(lock_file)
        else:
            acquire_lock(lock_file, blocking=False)

            judge_queued(db, no_human=args.no_human)

            release_lock(lock_file)


if __name__ == '__main__':
    main()
