import fcntl
import os

from mysql.connector.connection import MySQLConnection


class Submission:
    max_cpu_time = None
    chroot_dir = None
    replace_headers = None
    check_bad_words = None


class JavaSubmission(Submission):
    pass


class PythonSubmission(Submission):
    pass


class CppSubmission(Submission):
    pass


class CSubmission(Submission):
    pass


QUEUE_DIR_NAME = 'queue'
JUDGED_DIR_NAME = 'judged'
DATA_DIR_NAME = 'data'

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


def read_db_info():
    """
    Reads lib/database.inc and extracts the database connection information
    This assumes the strings in that file are double-quoted
    but won't be bothered if there are special characters in the password or weird whitespace things.
    :return: a dictionary containing the connection information
    """
    config_dict = {"db_host": None,
                   "db_user": None,
                   "db_pass": None,
                   "db_name": None}

    with open('lib/database.inc', 'r') as config_file:
        for line in config_file:
            for key in config_dict:
                if key in line:
                    value = line.split('"')[1]
                    config_dict[key] = value

    return config_dict


def db_connect():
    """
    Connects to the db using read_db_info()
    :return: a connection to the db. Remember to close it
    """
    connection_info = read_db_info()
    return MySQLConnection(user=connection_info["db_user"],
                           password=connection_info["db_pass"],
                           host=connection_info["db_host"],
                           database=connection_info["db_name"])


def get_dirs(db_conn: MySQLConnection):
    """
    Fetches and generates paths to the base, queue, judged, and data directories
    :param db_conn: a connection to the database which will be used to fetch the base dir
    :return: dict containing the four paths
    """
    with db_conn.cursor() as curs:
        curs.execute("""SELECT BASE_DIRECTORY FROM CONTEST_CONFIG""")
        base_dir = curs.fetchone()[0]
        return {'base': base_dir,
                'queue': os.path.join(base_dir, QUEUE_DIR_NAME),
                'judged': os.path.join(base_dir, JUDGED_DIR_NAME),
                'data': os.path.join(base_dir, DATA_DIR_NAME)}


def setup_classes(db_conn: MySQLConnection, dirs):
    """
    The classes have some static attributes which need to be filled out with information in the database.
    This function grabs the database info and puts it into those static attributes
    :param db_conn: a connection to the database
    """
    curs = db_conn.cursor()
    curs.execute("""SELECT * FROM LANGUAGE""")
    for tupl in curs:
        lang_name = tupl[1]
        NAME_CLASS[lang_name].max_cpu_time = tupl[2]  # TODO not multiply by 1.1
        NAME_CLASS[lang_name].chroot_dir = os.path.join(dirs['base'], tupl[3])
        NAME_CLASS[lang_name].replace_headers = bool(tupl[4])
        NAME_CLASS[lang_name].check_bad_words = bool(tupl[5])

    curs.close()


def fetch_submissions(db_conn: MySQLConnection, dirs):
    """
    Constructs a Submission object for each queued submission
    :param db_conn: a connection to the database
    :return: a list of Submission objects
    """
    out_list = []

    curs = db_conn.cursor()
    curs.execute("""SELECT QUEUE_ID, TEAM_ID, PROBLEM_ID, TS, ATTEMPT, SOURCE_FILE 
                    FROM QUEUED_SUBMISSIONS, CONTEST_CONFIG 
                    WHERE TS < (START_TS + CONTEST_END_DELAY) 
                    ORDER BY TS""")

    for tupl in curs:
        extension = os.path.splitext(tupl[5])[1]
        extension = extension.lower()

        if extension in EXTENSION_CLASS:
            submission = EXTENSION_CLASS[extension](dirs, tupl)  # Constructs a Submission object
            out_list.append(submission)
        else:
            pass  # TODO undefined file type

    curs.close()
    return out_list


def main():
    db_conn = db_connect()
    dirs = get_dirs(db_conn)
    lock_file = open(os.path.join(dirs['base'], "lockfile.lock"), 'w')

    acquire_lock(lock_file)
    setup_classes(db_conn, dirs)

    for submission in fetch_submissions(db_conn, dirs):
        pass

    release_lock(lock_file)
    db_conn.close()
