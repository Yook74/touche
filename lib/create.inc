<?php
    $page_bg_color  = "#cccccc";
    $body_bg_color  = "#ffffff";
    $title_bg_color = "#3480ba";
    $hd_bg_color1   = "#3480ba";
    $hd_bg_color2   = "#cccc99";
    $data_bg_color1 = "#dddddd";
    $data_bg_color2 = "#cccc99";

    $page_txt_color  = "#000000";
    $body_txt_color  = "#ffffff";
    $title_txt_color = "#ffffff";
    $hd_txt_color1   = "#ffffff";
    $hd_txt_color2   = "#000000";
    $data_txt_color1 = "#000000";
    $data_txt_color2 = "#000000";

    $create_user = "create";
    $create_pass = "contest";

    define("SUPPORTED_LANGUAGES", array("c", "cpp", "java", "python2", "python3"));
    define("END_DELAY",  60 * 60 * 5);
    define("FREEZE_DELAY", 60 * 60 * 4);

    /**
     * @param string $server_name usually $_SERVER[SERVER_NAME]
     * @param string $linux_user name of the hosting linux user
     * @param string $c_name_raw raw name of the contest
     * @return string base url of the contest
     */
    function get_contest_url($server_name, $linux_user, $c_name_raw){
        return "http://$server_name/~$linux_user/". preg_replace("/ /", "%20", $c_name_raw);
    }

    /**
    * Executes the given command on using system() and handles errors
    * @param string $command the command to execute
    * @param boolean $verbose will output the command using echo if set to true
    * @param boolean $exit_on_fail will call exit if the command has a nonzero exit code
    */
    function exec_and_check($command, $verbose = false,  $exit_on_fail = false){
        if ($verbose) echo "[$command] <br />";
        system($command, $exit_code);
        if($exit_code != 0){
            echo "$command gave exit code $exit_code <br />";
            if($exit_on_fail) exit;
        }
    }

    /**
    * Empties a contest by deleting all of the data it has accrued from submissions etc.
    * Does not remove any of the contest's code
    * @param string $private_contest_dir the directory that contains the contest's backend
    * @param boolean $silent true if you want to silence the status messages
    */
    function clear_directories($private_contest_dir, $silent = false) {
        if (!$silent) echo "<p>Clearing directories . . . <br />";

        $dirs_to_clear = array(
            "data",
            "judged",
            "queue",
            "test_compile",
        );

        foreach(SUPPORTED_LANGUAGES as $language){
            array_push($dirs_to_clear, $language . "_jail/home/contest");
        }

        foreach ($dirs_to_clear as $dir){
            $cmd = "rm -rf $private_contest_dir/$dir/*";
            exec_and_check($cmd);
        }
        if (!$silent) echo"Finished.</p>\n";
    }

    /**
    * Creates the /data and /judged directories in each jail
    * @param string $private_contest_dir the directory that contains the contest's backend
    * @param string $c_name_escaped the name of the contest with escaped spaces
    * @param boolean $silent true if you want to silence the status messages
    */
    function make_jail_directories($private_contest_dir, $c_name_escaped, $silent = false) {
        if (!$silent) echo "<p>Making Directories . . . <br />";

        $sub_dirs = array(
            "judged",
            "data"
        );

        foreach (SUPPORTED_LANGUAGES as $language){
            foreach($sub_dirs as $sub_dir){
                $cmd = "mkdir -p $private_contest_dir/$language" . "_jail/home/contest/$c_name_escaped/$sub_dir";
                exec_and_check($cmd, !$silent);
            }
        }
        if(!$silent) echo"Finished.</p>\n";
    }

    /**
    * Creates and populates the contest's private and public directories and returns their paths
    * Say that the contest is installed under user jeff's home directory
    * Touche should be set up so that there is a ~jeff/develop directory or symbolic link and a
    * ~jeff/public_html/develop directory or symbolic link.
    * This function copies the contents of those two directories into ~jeff/contestName and ~jeff/public_html/contestName.
    * @param string $c_name_escaped the name of the contest with escaped spaces
    * @param string $linux_user the name of the user that hosts this contest. In the example above it's "jeff".
    * @param boolean $silent true if you want to silence the status messages
    * @return an array that contains the paths to the public and private contest directories (in that order)
    */
    function copy_from_develop($c_name_escaped, $linux_user, $silent = false){
        if(!$silent) echo "<p>Creating contest folders (takes a while) . . . <br />";

        $private_contest_dir = "/home/$linux_user/$c_name_escaped";
        $public_contest_dir = "/home/$linux_user/public_html/$c_name_escaped";

        $cmd = "cp -pr ~$linux_user/develop/ $private_contest_dir/";
        exec_and_check($cmd, !$silent);

        $cmd = "cp -pr ~$linux_user/public_html/develop/ $public_contest_dir/";
        exec_and_check($cmd, !$silent);
        if(!$silent) echo "Finished. <p>\n";

        return array($public_contest_dir, $private_contest_dir);
    }

    /**
    * Creates the a new database for a new contest.
    * @param string $db_name the name of the database. Usually the contest name with underscores
    * @param string $sql_root_pass the root passqord for mysql. (defined in auth.inc)
    * @param boolean $silent true if you want to silence the status messages
    */
    function create_database($db_name, $sql_root_pass, $silent = true) {
        $cmd = "mysqladmin --password=$sql_root_pass -u root create $db_name";
        exec_and_check($cmd, !$silent);

        $cmd = "mysql --password=$sql_root_pass -u root $db_name < dbcreate.sql";
        exec_and_check($cmd, !$silent);
    }

    /**
    * Seeds an empty database for a new contest.
    * @param string $private_contest_dir the directory that contains the contest's backend
    * @param string $c_name_escaped the name of the contest with escaped spaces
    * @param string $contest_host the name of the hosting organization for this contest (eg. "Taylor University"
    * @param string $db_name the name of the database. Usually the contest name with underscores
    * @param string $db_host the name of the host for the database
    * @param string $db_user the database user (usually $sql_username defined in auth.inc)
    * @param string $db_pass the password for the database user
    */
    function seed_database($private_contest_dir, $c_name_escaped, $contest_host, $db_name, $db_host, $db_user, $db_pass) {
        $link = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
        if (!$link) {
            print "Sorry. Database connect failed. <br />";
            print "Error: " . mysqli_connect_error();
            exit;
        }
        $query_status = mysqli_query($link,
        "INSERT INTO CONTEST_CONFIG 
        (HOST, CONTEST_NAME, FREEZE_DELAY, CONTEST_END_DELAY, BASE_DIRECTORY, JUDGE_USER) VALUES 
        ('$contest_host', '$c_name_escaped', ". FREEZE_DELAY . ", " . END_DELAY . ", '$private_contest_dir', 'judge')");
        if (!$query_status){
            print "Sorry. Database request (INSERT) failed. <br />";
            exit;
        }

        mysqli_close($link);
    }

    /**
    * Replaces all the instances of each item in $change_from to the corresponding item
    * in $change_to within the file specified by $path.
    * @param string path path to a file to be edited
    * @param mixed change_from a singelton regular expression or an array of regexes that occur in the given file
    * @param mixed change_to same format and length as change_from except not regexes.
    */
    function replace_in_file($path, $change_from, $change_to){
        $handle = fopen($path, "r") OR die("Error reading file $path");
        $file_string = fread($handle, filesize($path));
        fclose($handle);

        $file_string = preg_replace($change_from, $change_to, $file_string);

        $handle = fopen($path, "w") OR die("Error writing file $path");
        fwrite($handle, $file_string);
        fclose($handle);
    }

    /**
    * Edits the database.inc file to reflect contest-specific information
    * @param string $public_contest_dir the directory that contains the contest's public files
    * @param string $db_name the name of the database. Usually the contest name with underscores
    * @param string $db_host the name of the host for the database
    * @param string $db_user the database user (usually $sql_username defined in auth.inc)
    * @param string $db_pass the password for the database user
    * @param boolean $silent true if you want to silence the status messages
    */
    function fill_in_database_inc($public_contest_dir, $db_name, $db_host, $db_user, $db_pass, $silent = false){
        $path = "$public_contest_dir/lib/database.inc";

        $change_from = array(
            "/YOUR.DB.HOST/",
            "/YOUR_USERNAME_HERE/",
            "/YOUR_PASSWORD_HERE/",
            "/CONTEST_DATABASE_HERE/"
        );

        $change_to = array(
            $db_host,
            $db_user,
            $db_pass,
            $db_name
        );

        replace_in_file($path, $change_from, $change_to);
    }

    /**
    * Edits the chroot_wrapper.c file to have a correct constant value
    * TODO this is a truly horrendus way to parameterize something
    * @param string $private_contest_dir the directory that contains the contest's public files
    * @param string $c_name_escaped the name of the contest with escaped spaces
    */
    function fill_in_chroot_wrapper($private_contest_dir, $c_name_escaped){
        $path = "$private_contest_dir/chroot_wrapper.c";

        replace_in_file($path, "/develop/", $c_name_escaped);
    }

    /**
    * Compiles chroot_wrapper.c to create chroot_wrapper.exe
    * @param string $private_contest_dir the directory that contains the contest's backend
    * @param boolean $silent true if you want to silence the status messages
    */
    function compile_chroot_wrapper($private_contest_dir, $silent = false){
        $path_no_extension = $private_contest_dir . "/chroot_wrapper";
        $cmd = "gcc -std=c11 -o $path_no_extension.exe $path_no_extension.c";
        exec_and_check($cmd, !$silent);

        $cmd = "sudo chown root:root $path_no_extension.exe";
        exec_and_check($cmd, !$silent);

        $cmd = "sudo chmod +xs $path_no_extension.exe";
        exec_and_check($cmd, !$silent);
    }

    /**
    * Sets the permission of the public contest directory.
    * Geisler does not know why this is necessary so I sure as heck don't
    * @param string $public_contest_dir the directory that contains the contest's public files
    * @param boolean $silent true if you want to silence the status messages
    */
    function set_contest_perms($public_contest_dir, $silent = false){
        $cmd = "sudo chmod -R go+rx $public_contest_dir";
        exec_and_check($cmd, !$silent);
    }

    /**
    * Sets the URL in the readme to the contest's URL
    * @param string $public_contest_dir the directory that contains the contest's public files
    * @param string $contest_url the full URL of the contest
    */
    function fill_in_readme($public_contest_dir, $contest_url){
        $path = "$public_contest_dir/readme/inst.html";

        replace_in_file($path, "/URLHERE/", $contest_url);
    }
?>
