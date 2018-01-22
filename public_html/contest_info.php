<?php
include_once("lib/header.php");
include_once("lib/authenticate.php");
if($userRole === null) {
    header("HTTP/1.1 401 Unauthorized");
    exit;
}
include_once("lib/database.inc");
$link = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
$method = $_SERVER['REQUEST_METHOD'];
$response = '';
if ($method === 'GET') {
    $query = mysqli_query($link, "SELECT * FROM CONTEST_CONFIG");
    $row = mysqli_fetch_assoc($query);
    $contest_info = array(
        'host' => $row['HOST'],
        'name' => $row['CONTEST_NAME'],
        'startDate' => $row['CONTEST_DATE'],
        'startTime' => $row['START_TIME'],
        'startTS' => $row['START_TS'],
        'freezeDelay' => $row['FREEZE_DELAY'],
        'contestEndDelay' => $row['CONTEST_END_DELAY'],
        'baseDirectory' => $row['BASE_DIRECTORY'],
        'ignoreStandardError' => $row['IGNORE_STDERR'],
        'hasStarted' => $row['HAS_STARTED'],
        'judgeUsername' => $userRole === 'admin' ? $row['JUDGE_USER'] : '',
        'judgePassword' => $userRole === 'admin' ? $row['JUDGE_PASS'] : '',
        'showTeams' => $row['TEAM_SHOW']
    )
}
if ($method === 'PUT') {
    if ($userRole !== 'admin') {
        header("HTTP/1.1 401 Unauthorized");
        exit;
    }
}
?>