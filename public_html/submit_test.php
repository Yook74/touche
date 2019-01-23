<?php
# TEST COMPILE
# Copyright (C) 2002, 2003 David Whittington
# Copyright (C) 2005 Jonathan Geisler
# Copyright (C) 2005 Victor Replogle
# Copyright (C) 2005 Steve Overton
#
# See the file "COPYING" for further information about the copyright
# and warranty status of this work.
#
# arch-tag: submissions.php

include_once("lib/config.inc");
include_once("lib/session.inc");
include_once("lib/data.inc");

define("ERR_NO_FILE", 1);
define("CLEAN_COMPILE", 2);
define("ERR_FILE_TYPE", 3);
define("ERR_FILE_MOVE", 4);
define("COMPILE_ERR", 5);
define("FORBIDDEN_WORD", 6);

// check to see if a file is actually being submitted
if ($_FILES['source_file']['name'] == false) {
    report_status(ERR_NO_FILE);
}

list($file_name, $queue_id, $team_id, $ts) = enqueue_submission();

# Get the path of the directory which will store the submitted source code as well as the compiler output
$submission_dir = "$base_dir/queue/$team_id-T-$ts";
mkdir($submission_dir);
$source_path = "$submission_dir/$file_name";

move_submission($source_path);
execute_cronscript($queue_id);

$submission_dir = "$base_dir/judged/$team_id-T-$ts";
interpret_response($submission_dir, $ts);

/**
 * Insert the submitted file into the QUEUED_SUBMISSIONS table and return information about the submission
 */
function enqueue_submission()
{
    $team_id = $GLOBALS['team_id'];
    $link = $GLOBALS['link'];

    $file_name = $_FILES['source_file']['name'];
    $ts = time();
    $sql = "INSERT INTO QUEUED_SUBMISSIONS (TEAM_ID, TS, SOURCE_NAME, TEST_COMPILE) ";
    $sql .= "VALUES ('$team_id', '$ts', '$file_name', True) ";
    execute_query($sql);
    $queue_id = mysqli_insert_id($link);
    return array($file_name, $queue_id, $team_id, $ts);
}

/**
 * Move the submitted source file the specified path
 */
function move_submission($source_path)
{
    if (!move_uploaded_file($_FILES['source_file']['tmp_name'], $source_path)) {
        report_status(ERR_FILE_MOVE);
    }
    chmod($source_path, 0644);
}

/**
 * execute the cronscript with a flag that tells it to only compile our submission
 */
function execute_cronscript($queue_id)
{
    chdir('judge');
    system("python3 cronscript.py --test-compile $queue_id 2> errorLog.txt");
    chdir('..');
}

/**
 * Get the response from the cronscript and report it to the testcompile page
 */
function interpret_response($submission_dir, $ts)
{
    $reponses = $GLOBALS['responses'];

    $result = execute_query("SELECT JUDGED_ID FROM JUDGED_SUBMISSIONS WHERE TEAM_ID = $GLOBALS[team_id] AND TS = $ts");
    $judged_id = mysqli_fetch_array($result)['JUDGED_ID'];

    $result = execute_query("SELECT * FROM AUTO_RESPONSES WHERE JUDGED_ID = '$judged_id'");
    $auto_response_row = mysqli_fetch_assoc($result);

    switch ($reponses[$auto_response_row['RESPONSE_ID']]['KEYWORD']) {
        case 'EFILETYPE':
            report_status(ERR_FILE_TYPE);
            break;
        case 'CORRECT':
            report_status(CLEAN_COMPILE);
            break;
        case 'ECOMPILE':
            $status = COMPILE_ERR;
        case 'EFORBIDDEN':
            if (!$status)
                $status = FORBIDDEN_WORD;

            $err_file = "$submission_dir/$auto_response_row[OUTPUT_FILE]";
            $_SESSION['compile_errors'] = read_entire_file($err_file);
            report_status($status);
            break;
        default:
            echo "Unknown response from cronscript: $auto_response_row[RESPONSE_ID]";
            die(1);
    }
}

/**
 * return to the testcompile page with the given status
 * @param int $status_code should be one of the constants defined at the top of this file
 */
function report_status($status_code){
    header("location: testcompile.php?state=$status_code");
    exit(0);
}

/**
 * Read the specified file into a string or report an error and die
 */
function read_entire_file($path) {
    if(file_exists($path))
        return file_get_contents($path);

    echo "<br> Error opening file $path";
    die(1);
}

function execute_query($sql){
    $link = $GLOBALS['link'];
    $result = mysqli_query($link, $sql);
    if (!$result) {
        echo "Error in sql: $sql";
        echo mysqli_error($link);
        die(1);
    }
    return $result;
}
?>
