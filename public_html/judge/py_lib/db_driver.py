import os
from mysql.connector.connection import MySQLConnection

QUEUE_DIR_NAME = 'queue'
JUDGED_DIR_NAME = 'judged'
DATA_DIR_NAME = 'data'


class DBDriver:
    config_path = 'lib/database.inc'

    def __init__(self):
        self.__connection = None
        self.dirs = None
        self.connect()
        self.get_dirs()

    def __enter__(self):
        return self

    def read_config(self):
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

        with open(self.config_path, 'r') as config_file:
            for line in config_file:
                for key in config_dict:
                    if key in line:
                        value = line.split('"')[1]
                        config_dict[key] = value

        return config_dict

    def connect(self):
        """
        Connects to the db using read_db_info()
        :return: a connection to the db. Remember to close it
        """
        connection_info = self.read_config()
        self.__connection = MySQLConnection(user=connection_info["db_user"],
                                            password=connection_info["db_pass"],
                                            host=connection_info["db_host"],
                                            database=connection_info["db_name"])

    def get_dirs(self):
        """
        Fetches and generates paths to the base, queue, judged, and data directories
        """
        curs = self.__connection.cursor()  # would use a with block but it's not supported :(
        curs.execute('''SELECT BASE_DIRECTORY FROM CONTEST_CONFIG''')
        base_dir = curs.fetchone()[0]
        self.dirs = {'base': base_dir,
                     'queue': os.path.join(base_dir, QUEUE_DIR_NAME),
                     'judged': os.path.join(base_dir, JUDGED_DIR_NAME),
                     'data': os.path.join(base_dir, DATA_DIR_NAME)}

        curs.close()

    def get_forbidden(self, lang_id):
        """
        Gets the forbidden words for the language with the given ID
        """
        curs = self.__connection.cursor()
        curs.execute('''SELECT WORD FROM FORBIDDEN_WORDS WHERE LANGUAGE_ID=%d''' % lang_id)
        out = [tupl[0] for tupl in curs.fetchall()]
        curs.close()
        return out

    def get_headers(self, lang_id):
        curs = self.__connection.cursor()
        curs.execute('''SELECT HEADER FROM HEADERS WHERE LANGUAGE_ID=%d''' % lang_id)
        out = [tupl[0] for tupl in curs.fetchall()]
        curs.close()
        return out

    def get_language_info(self):
        """
        :return: The contents of the LANGUAGE table
        """
        curs = self.__connection.cursor()
        curs.execute('''SELECT * FROM LANGUAGE''')
        out = curs.fetchall()
        curs.close()
        return out

    def get_submission_info(self):
        """
        :return: Information on all the queued submissions submitted before the contest ends
        """
        curs = self.__connection.cursor()
        curs.execute('''SELECT QUEUE_ID, TEAM_ID, PROBLEM_ID, TS, ATTEMPT, SOURCE_FILE 
                        FROM QUEUED_SUBMISSIONS, CONTEST_CONFIG 
                        WHERE TS < (START_TS + CONTEST_END_DELAY) 
                       ORDER BY TS''')

        out = curs.fetchall()
        curs.close()
        return out

    def report_pending(self, one_submission_info):
        """
        Reports that the given submission is being judged by moving the row from QUEUED_SUBMISSIONS to JUDGED_SUBMISSIONS
        :param one_submission_info: A row from the QUEUED_SUBMISSIONS table
        :return: the id of the inserted row
        """
        curs = self.__connection.cursor()
        curs.execute('''INSERT INTO JUDGED_SUBMISSIONS (TEAM_ID, PROBLEM_ID, TS, ATTEMPT, SOURCE_FILE) 
                        VALUES (%d, %d, %d, %d, '%s')''' % one_submission_info[1:])
        row_id = curs.lastrowid

        curs.execute('''DELETE FROM QUEUED_SUBMISSIONS WHERE QUEUE_ID = %d''' % one_submission_info[0])
        self.__connection.commit()
        curs.close()
        return row_id

    def report_judgement(self, judged_id, judgement_code, in_file, error_no=None):
        """
        Reports that the given submission has been judged and what the results of the judgement were
        :param judged_id: returned by report_pending
        :param judgement_code: a code that indicates which judgement was made
        :param in_file: TODO seems to be used for a lot of things
        :param error_no: the error number from a runtime or compile error
        """
        curs = self.__connection.cursor()
        if error_no is not None:
            curs.execute('''INSERT INTO AUTO_RESPONSES (JUDGED_ID, IN_FILE, AUTO_RESPONSE, ERROR_NO)
                            VALUES (%d, '%s', %d, %d)''' % (judged_id, in_file, judgement_code, error_no))
        else:
            curs.execute('''INSERT INTO AUTO_RESPONSES (JUDGED_ID, IN_FILE, AUTO_RESPONSE)
                            VALUES (%d, '%s', %d)''' % (judged_id, in_file, judgement_code))
        self.__connection.commit()
        curs.close()

    def __exit__(self, exc_type, exc_val, exc_tb):
        self.__del__()

    def __del__(self):
        self.__connection.close()
