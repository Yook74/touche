<?php
include_once("lib/config.inc");
include_once("lib/judge.inc");
include_once("lib/header.inc");
include_once("lib/database.inc");
judge_header(0, $link);

if(!$_GET){
    echo "No submission was selected";
    exit();
}


$submission_info = one_row_query("SELECT * FROM JUDGED_SUBMISSIONS WHERE JUDGED_ID = '$_GET[judged_id]'");
$submission_name = "$submission_info[TEAM_ID]-$submission_info[PROBLEM_ID]-$submission_info[TS]";
$submission_dir = "$base_dir/judged/$submission_name";

$problem_info = one_row_query("SELECT * FROM PROBLEMS WHERE PROBLEM_ID = '$_GET[problem]'");

execute_query("UPDATE JUDGED_SUBMISSIONS SET VIEWED = 1 WHERE JUDGED_ID = '$_GET[judged_id]'");

echo "<table align=center bgcolor=#ffffff cellpadding=0 cellspacing=0 border=0><tr><td>\n";
echo "<table width=100% cellpadding=5 cellspacing=1 border=0>\n";
echo "<tr><td bgcolor=$hd_bg_color1 align=center colspan=2>\n";
echo "<font color=$hd_txt_color1><b>\n";
echo "Judging Submission\n";
echo "</b></td></tr></table>\n";
echo "<table width=100% border=0>\n";
echo "<tr><td bgcolor=$data_bg_color1>Submission ID:</td>\n";
echo "<td bgcolor=$data_bg_color1>$_GET[judged_id]</td></tr>\n";
echo "<tr><td bgcolor=$data_bg_color1>Team:</td>\n";
echo "<td bgcolor=$data_bg_color1>$_GET[team_id]</td></tr>\n";
echo "<tr><td bgcolor=$data_bg_color1>Problem:</td>\n";
echo "<td bgcolor=$data_bg_color1>$submission_info[PROBLEM_ID]</td></tr>\n";
echo "<tr><td bgcolor=$data_bg_color1>Attempt:</td>\n";
echo "<td bgcolor=$data_bg_color1>$_GET[attempt]</td></tr>\n";
echo "<tr><td bgcolor=$data_bg_color1>Source:</td>\n";

$source_name = $submission_info['SOURCE_NAME'];
echo "<td  bgcolor=$data_bg_color1>
  <a href='judge_output.php?problem=$problem_info[PROBLEM_ID]&sub_source=$submission_name/$source_name&format=2' target='blank'>
  $source_name </a></td></tr>";

echo "<table width = 100% border=0 cellpadding=5><tr>";
echo "<td bgcolor=$hd_bg_color2><center><b>";
echo "<font color=$hd_txt_color2>Problem Notes:</font>";
echo "</b></center></td></tr></table>\n";
echo "<table><tr><td><textarea rows=4 cols=62 readonly>";
echo $problem_info['PROBLEM_NOTE'] . "</textarea></table>";

$response_types = get_response_types();
$response_keywords = get_response_keywords();
$auto_response_query = execute_query("SELECT * FROM AUTO_RESPONSES WHERE JUDGED_ID = $submission_info[JUDGED_ID]");

if (mysqli_num_rows($auto_response_query) == 0) {
    $overall_response_id = $response_keywords['PENDING'];
} else {
    $overall_response_id = $response_keywords['CORRECT']; # Innocent until proven guilty
}

while($auto_response_info = mysqli_fetch_assoc($auto_response_query)){
    if($auto_response_info['RESPONSE_ID'] < $overall_response_id) { # Proven guilty
        $overall_response_id = $auto_response_info['RESPONSE_ID'];
    }

    $output_file_text = read_entire_file("$submission_dir/$auto_response_info[OUTPUT_FILE]");

    $judgement_text = $response_types[$auto_response_info['RESPONSE_ID']]['DISPLAY_TEXT'];
    $judgement_color = $response_types[$auto_response_info['RESPONSE_ID']]['COLOR'];

    echo "<table border=0 width=100% cellpadding=5>\n";
    echo "<tr cellpadding=5 bgcolor=$hd_bg_color2>\n";
    echo "<td align=center colspan=2>\n";
    echo "<font color=$hd_txt_color2>\n";

    switch($auto_response_info['RESPONSE_ID']){
        case $response_keywords['EFORBIDDEN']:
            echo "<b>Forbidden Word in Source</b>";
            echo "</td></tr>\n";
            echo "<tr><td><textarea name=error_field rows=15 cols=62 readonly>$output_file_text</textarea>";
            echo "</td></tr></table>\n";

            break;

        case $response_keywords['ECOMPILE']:
            echo "<b>Compile Error</b>";
            echo "</td></tr>\n";
            echo "<tr><td><textarea name=error_field rows=15 cols=62 readonly>$output_file_text</textarea>";
            echo "</td></tr></table>\n";

            break;

        case $response_keywords['EFILETYPE']:
            echo "<b>Undefined File Type</b>";
            echo "</td></tr></table>\n";
            echo "<table border=0 width=100%>\n";
            echo "<tr><td bgcolor=$data_bg_color1>";
            echo "<font color=$data_txt_color4>";
            echo "File Name: $source_name</font></td></tr></table>\n";

            break;

        case $response_keywords['EINCORRECT']:
        case $response_keywords['EFORMAT']:
        case $response_keywords['ETIMEOUT']:
        case $response_keywords['ERUNTIME']:
        case $response_keywords['CORRECT']:
            $program_output_path = "$submission_dir/$auto_response_info[OUTPUT_FILE]";
            $diff_path = $program_output_path . ".diff";

            switch($auto_response_info['RESPONSE_ID']){
                case $response_keywords['EINCORRECT']:
                case $response_keywords['EFORMAT']:
                    $title = "Standard Diff Failed";
                    $color = $data_txt_color4;
                    $display_path = $diff_path;
                    break;

                case $response_keywords['ETIMEOUT']:
                    $title = "Timed Out";
                    $color = $data_txt_color4;
                    $display_path = $program_output_path;
                    break;

                case $response_keywords['ERUNTIME']:
                    $title = "Process Exited with Code $auto_response_info[ERROR_NO]";
                    $color = $data_txt_color4;
                    $display_path = $program_output_path;
                    break;

                case $response_keywords['CORRECT']:
                    $title = "Correct Solution";
                    $color = $data_txt_color3;
                    $display_path = $program_output_path;
                    break;
            }


            $correct_out_name = preg_replace("/.in$/", '.out', $auto_response_info['INPUT_FILE']);
            echo "<b>Data Set: $correct_out_name</b>";
            echo "</td></tr></table>\n";
            echo "<table border=0 width=100%>\n";
            echo "<tr><td bgcolor=$data_bg_color1>\n";
            echo "<font color=$color>";
            echo "$title</font></b></td></tr>\n";

            if ($auto_response_info['RESPONSE_ID'] == $response_keywords['EFORMAT']) {
                echo "<tr><td bgcolor=$data_bg_color1>\n";
                echo "<font color=\"black\">";
                echo "No-whitespace diff Succeeded</font></b></td></tr>\n";
            }

            if ($auto_response_info['RESPONSE_ID'] != $response_keywords['CORRECT']) {
                echo "<tr><td><textarea rows=15 cols=62 readonly>";
                echo read_entire_file($display_path);
                echo "</textarea></td></tr>";
                echo "<tr><td bgcolor=$data_bg_color1>";
                echo "<font color=$data_txt_color4>";
                echo "<b>$judgement_text</b>";
            }
            if ($auto_response_info['RESPONSE_ID'] != $response_keywords['ERUNTIME'] &&
                $auto_response_info['RESPONSE_ID'] != $response_keywords['ETIMEOUT'])
            {
                echo "<tr><td bgcolor=$data_bg_color1>";
                $url =  "judge_output.php?problem=$problem_info[PROBLEM_ID]";
                $url .= "&judge_source=$correct_out_name";
                $url .= "&sub_source=$submission_name/$auto_response_info[OUTPUT_FILE]&format=1";
                echo "<a name=$correct_out_name href=$url target='blank'>Output Files</a>";
            }

            echo "</td></tr></table>";
            break;

        default:
            echo "<b>Error</b>";
            echo "</td></tr>\n";
            echo "<tr><td><textarea name=error_field rows=15 cols=62 readonly>$output_file_text</textarea>";
            echo "</td></tr></table>\n";
            break;
    }
}

$judgement_text = $response_types[$overall_response_id]['DISPLAY_TEXT'];
$judgement_color = $response_types[$overall_response_id]['COLOR'];

echo "<table width=100% cellpadding=5 border=0>\n";
echo "<tr><td align=center bgcolor=$hd_bg_color2>\n";
echo "<font color=$hd_txt_color2><b>";
echo "Overall Result of the Attempt</b></font></td></tr>\n";
echo "</table><table border=0 width=100%>\n";
echo "<tr><td bgcolor=$data_bg_color1>Suggested Result: ";

echo "<font color=$judgement_color><b>";
echo $judgement_text;
echo "</b></font></td></tr>";
echo "</b></font></td></tr>";
echo "<form method='POST' name='testing' action='$_GET[page]'>\n";
echo "<tr><td bgcolor=$data_bg_color1>Final Result: ";
echo "<select name='result'>";

foreach ($response_types as $ID => $info){
    echo "<option value=" . $ID;
    if($overall_response_id == $ID) {
        echo ' selected="selected" ';
    }
    echo ">" . $info['DISPLAY_TEXT'] . "</option>";
}
echo "</select>";
echo "</td></tr></table>";
    //this now goes after each data set
    /*if($auto_response_id != 1){
    echo "<table><tr><td><textarea rows=15 cols=62 readonly>";
        echo $error_output;
        if($auto_response_id == 8) {
            echo "Runtime errorno: put number in db";
        }
        echo "</textarea></td></tr></table>\n";
    }*/

    echo "<table border=0 width=100%>\n";
echo "<tr><td><center>";
echo "<input type='hidden' name='judged_id' value=" . $_GET['judged_id'] . ">";
echo "<input type='submit' name='submit' value='Submit Results'>";
echo "</td></tr></center></form></table>\n";

# Read the entire file into a string
function read_entire_file($path) {
    if(file_exists($path))
        return file_get_contents($path);

    echo "<br> Error opening file $path";
    die(1);
}

function execute_query(string $sql){
    $result = mysqli_query($GLOBALS['link'], $sql);
    if (!$result){
        echo "<br>Error in SQL command: $sql";
        echo mysqli_error($GLOBALS['link']);
        die(1);
    }
    return $result;
}

function one_row_query(string $sql){
    $result = execute_query($sql);
    return mysqli_fetch_assoc($result);
}

function get_response_types(){
    $result = execute_query('SELECT * FROM RESPONSES');
    $response_types = array();
    while ($row = mysqli_fetch_assoc($result)){
        $response_types[$row['RESPONSE_ID']] =
            array('KEYWORD' => $row['KEYWORD'],
                'DISPLAY_TEXT' => $row['DISPLAY_TEXT'],
                'COLOR' => $row['COLOR']);
    }

    return $response_types;
}

function get_response_keywords(){
    $result = execute_query('SELECT * FROM RESPONSES');
    $response_keywords = array();
    while ($row = mysqli_fetch_assoc($result))
        $response_keywords[$row['KEYWORD']] = $row['RESPONSE_ID'];

    return $response_keywords;
}
?>
