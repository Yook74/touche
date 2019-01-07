class Submission:
    lang_max_cpu_time = None
    lang_chroot_dir = None
    lang_replace_headers = None
    lang_check_bad_words = None
    lang_forbidden_words = None

    def move_to_judged(self):
        pass

    def replace_headers(self):
        pass

    def check_bad_words(self):
        pass

    def compile(self):
        pass

    def move_to_jail(self):
        pass

    def execute(self):
        pass

    def move_from_jail(self):
        pass

    def judge_output(self):
        pass
