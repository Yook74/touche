import os

from .db_driver import DBDriver

# These are the possible responses. Their values are loaded in from the database
PENDING = None
EFILETYPE = None
EFORBIDDEN = None
ECOMPILE = None
ERUNTIME = None
EINCORRECT = None
EMAXOUTPUT = None
ETIMEOUT = None
EFORMAT = None
EUNKNOWN = None
CORRECT = None


def parse_error_codes():
    with DBDriver() as db:
        response_codes = db.get_response_codes()
        for keyword in response_codes:
            exec(keyword + ' = ' + str(response_codes[keyword]), globals())


parse_error_codes()


class SubmissionResults:
    """
    Provides accessor and mutator methods to make reporting the status of a Submission easy.
    The mutator methods are designed to work well with the Submission classes, while
        the accessor methods are designed to work well with the DBDriver class.

    Nothing complicated going on here, just a run-of-the-mill ADT
    """

    def __init__(self):
        self.__pre_exec_err = False

        # parallel lists
        self.__judgement_codes = []
        self.__input_file_names = []  # The name of the input file for each set of input/output files (if applicable)
        self.__output_file_names = []  # These files store error text or output from the submission
        self.__error_nos = []

    @staticmethod
    def write_msg(msg, path):
        """
        Writes the given message to a file at the given path
        """
        try:
            file = open(path, 'w')
            file.write(msg)
            file.close()
        except IOError as err:
            err.filename = os.path.abspath(path)
            raise err

    def get_overall_judgement_code(self):
        """
        The judgement codes are actually ordered by severity, and we want to report the most serious error.
        :return: the most severe error code or None if the object is empty
        """
        try:
            return min(self.__judgement_codes)
        except (TypeError, ValueError):
            return None

    def add_sub_judgment(self, judgement_code: int, input_file_name: str, output_file_name: str,
                         message: str=None, path: str=None, error_no: str=None):
        """
        Stores the results of running a singe input file through the submitted executable
        :param judgement_code: one of submission_result.E*
        :param input_file_name: The name of the input file for this test
        :param output_file_name: The name of the file outputted by the program. Either error text or actual output.
        :param message: The judgement message
        :param path: The absolute path to the message file.
        :param error_no: If applicable, the error number of the runtime error
        If this parameter is given, the error message will be written to the file
        """
        if self.__pre_exec_err:
            raise RuntimeError('A pre-exec error has already been reported; cannot call this function')

        self.__judgement_codes.append(judgement_code)
        self.__input_file_names.append(input_file_name)
        self.__output_file_names.append(output_file_name)
        self.__error_nos.append(error_no)

        if path is not None:
            if message is None:
                message = ''

            self.write_msg(message, path)

    def get_auto_judgements(self):
        """
        The results formatted in a list of dictionaries that can be easily inserted into the AUTO_RESPONSES table
        """
        out_list = []
        for code, input_file, output_file, error_no in zip(self.__judgement_codes, self.__input_file_names,
                                                           self.__output_file_names, self.__error_nos):

            out_list.append({'judgement_code': code,
                             'input_file': input_file,
                             'output_file': output_file,
                             'error_no': error_no})
        return out_list

    def report_pre_exec_error(self, error_code: int, file_name: str,
                              message: str=None, path: str=None, error_no: str=None):
        """
        A severe (non-runtime) error has occurred and judging will now stop
        If desired, the given error message will be written to the given file path
        :param error_code: one of submission_result.E*
        :param file_name: The name of the file that contains or will contain the error message. Passed through to the db
        :param message: The error message.
        :param path: The absolute path to the error message file.
        :param error_no: If applicable, the error number of the runtime error
        If this parameter is given, the error message will be written to the file
        """
        if self.__pre_exec_err:
            raise RuntimeError('A pre-exec error has already been reported!')
        self.__pre_exec_err = True

        self.__judgement_codes = [error_code]
        self.__input_file_names = [None]
        self.__output_file_names = [file_name]
        self.__error_nos = [error_no]

        if path is not None:
            if message is None:
                message = ''

            self.write_msg(message, path)

    def is_err(self):
        """
        :return: true if the submission is known to be incorrect in some way
        """
        code = self.get_overall_judgement_code()
        return code != CORRECT and code is not None

    def input_file_has_judgement(self, file_name: str):
        """
        :return: True if the given input file name already has an associated judgement
        """
        return file_name in self.__input_file_names
