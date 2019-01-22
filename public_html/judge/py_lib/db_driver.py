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

        return self.dirs
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
        """
        Gets the headers for the language with the given ID
        """
        curs = self.__connection.cursor()
        curs.execute('''SELECT HEADER FROM HEADERS WHERE LANGUAGE_ID=%d''' % lang_id)
        out = [tupl[0] for tupl in curs.fetchall()]
        curs.close()
        return out

    def get_language_info(self, language_name):
        """
        :return: A row of the LANGUAGE table specified by language_name
        """
        curs = self.__connection.cursor()
        curs.execute('''SELECT * FROM LANGUAGE WHERE LANGUAGE_NAME="%s"''' % language_name)
        out = curs.fetchone()
        curs.close()
        return out

    def get_queued_submissions(self, queue_id=None, test_compile=False):
        """
        :return: Information on all the queued submissions submitted before the contest ends
        :param queue_id If specified, only fetch submissions with that ID
        :param test_compile True if test compile submissions should only be fetched and false if they should be ignored
        """
        curs = self.__connection.cursor()
        where_clause = '''WHERE TS < (START_TS + CONTEST_END_DELAY) AND TEST_COMPILE = %s''' % test_compile

        if queue_id is not None:
            where_clause += ''' AND QUEUE_ID = %d''' % queue_id

        curs.execute('''SELECT QUEUE_ID, TEAM_ID, PROBLEM_ID, TS, ATTEMPT, SOURCE_NAME, TEST_COMPILE
                        FROM QUEUED_SUBMISSIONS, CONTEST_CONFIG
                        %s
                        ORDER BY TS''' % where_clause)

        out = curs.fetchall()
        curs.close()
        return out

    def get_ignore_stderr(self):
        """
        :return boolean: Whether IGNORE_STDERR is set
        """
        curs = self.__connection.cursor()
        curs.execute('''SELECT IGNORE_STDERR FROM CONTEST_CONFIG''')

        out = curs.fetchone()[0]
        curs.close()
        return bool(out)

    def report_pending(self, one_submission_info):
        """
        Reports that the given submission is being judged by moving the row from QUEUED_SUBMISSIONS to JUDGED_SUBMISSIONS
        :param one_submission_info: A row from the QUEUED_SUBMISSIONS table
        :return: the id of the inserted row
        """
        curs = self.__connection.cursor()

        one_submission_info = list(one_submission_info)  # to make it mutable
        for idx, elem in enumerate(one_submission_info):
            if elem is None:
                one_submission_info[idx] = 'NULL'  # SQL syntax

        curs.execute('''INSERT INTO JUDGED_SUBMISSIONS (TEAM_ID, PROBLEM_ID, TS, ATTEMPT, SOURCE_NAME, TEST_COMPILE) 
                        VALUES (%s, %s, %s, %s, '%s', %s)''' % tuple(one_submission_info[1:]))
        row_id = curs.lastrowid

        curs.execute('''DELETE FROM QUEUED_SUBMISSIONS WHERE QUEUE_ID = %d''' % one_submission_info[0])
        self.__connection.commit()
        curs.close()
        return row_id

    def report_auto_judgement(self, judged_id, judgement_code, input_file, output_file, error_no=None):
        """
        Inserts a row into the AUTO_JUDGMENT table
        :param judged_id: returned by report_pending
        :param judgement_code: a code that indicates which judgement was made
        :param input_file: The name of the file which served as input for this run of the program (may be None if error)
        :param output_file: The name of the file which was outputted by the program. May contain error text.
        :param error_no: the error number if applicable
        """
        curs = self.__connection.cursor()
        columns = ['JUDGED_ID', 'OUTPUT_FILE', 'RESPONSE_ID']
        values = [judged_id, output_file, judgement_code]
        formats = ['%d', "'%s'", '%d']

        if input_file is not None:
            columns.append('INPUT_FILE')
            values.append(input_file)
            formats.append("'%s'")

        if error_no is not None:
            columns.append('ERROR_NO')
            values.append(error_no)
            formats.append('%d')

        columns = '(%s)' % ', '.join(columns)
        formats = '(%s)' % ', '.join(formats)
        value_clause = formats % tuple(values)

        curs.execute('INSERT INTO AUTO_RESPONSES ' + columns + ' VALUES ' + value_clause)
        self.__connection.commit()
        curs.close()

    def get_response_codes(self):
        """
        :return: A dictionary which associates response keywords with their IDs
        """
        curs = self.__connection.cursor()
        curs.execute('''SELECT RESPONSE_ID, KEYWORD FROM RESPONSES''')
        out = {tupl[1]: tupl[0] for tupl in curs.fetchall()}
        curs.close()
        return out

    def __exit__(self, exc_type, exc_val, exc_tb):
        self.__del__()

    def __del__(self):
        self.__connection.close()
