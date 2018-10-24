<?php
    include_once("../../lib/create.inc");

    /**
    * Extracts information about the current contest from the working directory
    * Intended to be called by admin/misc.php May not work if not called there
    * @return array public base directory, private base directory, contest name with escaped spaces and database name
    */
    function get_curr_contest_info(){
        $public_dir = realpath(getcwd() . "/..");
        $name_escaped = substr($public_dir, strrpos($public_dir, '/') + 1);
        $db_name = preg_replace("/\\\\ /", "_", $name_escaped);
        $private_dir = realpath("$public_dir/../../$name_escaped");

        return array($public_dir, $private_dir, $name_escaped, $db_name);
    }

    /**
    * Creates and populates the contest's private and public directories and returns their paths
    * @param string $clone_name_escaped the name of the clone with escaped spaces
    * @param string $linux_user the name of the user that hosts this contest.
    * @param string $source_private_dir the private directory of the source contest
    * @param string $source_public_dir the public directory of the source contest
    * @param boolean $silent true if you want to silence the status messages
    * @return array that contains the paths to the public and private clone directories (in that order)
    */
    function clone_files($clone_name_escaped, $source_public_dir, $source_private_dir, $linux_user, $silent = false){
        if(!$silent) echo "<p>Creating clone folder. . . <br />";

        $clone_private_dir = "/home/$linux_user/$clone_name_escaped";
        $clone_public_dir = "/home/$linux_user/public_html/$clone_name_escaped";

        $cmd = "cp -pr $source_private_dir $clone_private_dir/";
        exec_and_check($cmd, !$silent);

        $cmd = "cp -pr $source_public_dir $clone_public_dir/";
        exec_and_check($cmd, !$silent);
        if(!$silent) echo "Finished. <p>\n";

        return array($clone_public_dir, $clone_private_dir);
    }

    /**
    * Clones the source database
    * @param string $source_db_name the name of the source database. Usually the contest name with underscores
    * @param string $clone_db_name the name of the clone database.
    * @param string $sql_root_pass the root password for mysql. (defined in auth.inc)
    * @param boolean $silent true if you want to silence the status messages
    */
    function clone_database($source_db_name, $clone_db_name, $sql_root_pass, $silent = true) {
        $command= "mysqldump --password=$sql_root_pass -u root $source_db_name > $clone_db_name.sql";
        exec_and_check($command, !$silent);

        $command = "mysqladmin --password=$sql_root_pass -u root create $clone_db_name";
        exec_and_check($command, !$silent);

        $command = "mysql --password=$sql_root_pass -u root $clone_db_name < $clone_db_name.sql";
        exec_and_check($command, !$silent);
    }

    /**
    * Seeds an empty database for a new contest.
    * @param string $clone_private_dir the directory that contains the clone's backend
    * @param string $clone_name_escaped the name of the clone with escaped spaces
    * @param string $db_name the name of the database. Usually the contest name with underscores
    * @param string $db_host the name of the host for the database
    * @param string $db_user the database user (usually $sql_username defined in auth.inc)
    * @param string $db_pass the password for the database user
    */
    function update_database($clone_private_dir, $clone_name_escaped, $db_name, $db_host, $db_user, $db_pass) {
        $link = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
        if (!$link) {
            print "Sorry. Database connect failed. <br />";
            print "Error: " . mysqli_connect_error();
            exit;
        }

        $query_result = mysqli_query($link,
            "UPDATE CONTEST_CONFIG SET CONTEST_NAME = \"$clone_name_escaped\", 
            CONTEST_DATE = '0001-01-01', 
            START_TIME='00:00:00', 
            FREEZE_DELAY=" . FREEZE_DELAY. ", 
            CONTEST_END_DELAY= " . END_DELAY . ", 
            BASE_DIRECTORY=\"$clone_private_dir\", 
            START_TS='0', 
            HAS_STARTED='0'");
        if (!$query_result) {
            print "Sorry.  Database request (UPDATE) failed.";
            exit;
        }

        $query_result = mysqli_query($link,
            "UPDATE SITE SET START_TIME='00:00:00', START_TS='0', HAS_STARTED='0'");
        if (!$query_result) {
            print "Sorry.  Database request (UPDATE) failed.";
            exit;
        }

        mysqli_close($link);
    }

?>