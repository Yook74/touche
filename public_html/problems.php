<?php
include_once("lib/header.php");
if($USE_JSON) {
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
        $problems = [];
        $problems_query = mysqli_query($link, "SELECT * FROM PROBLEMS");
        while ($row = mysqli_fetch_assoc($problems_query)) {
            $problem = array(
                'id' => $row['PROBLEM_ID'],
                'name' => $row['PROBLEM_NAME'],
                'loc' => $row['PROBLEM_LOC'],
                'note' => $row['PROBLEM_NOTE'],
                'hasHTML' => file_exists("problems/".$row['PROBLEM_LOC']."/".$row['PROBLEM_NAME'].".html"),
                'hasPDF' => file_exists("problems/".$row['PROBLEM_LOC']."/".$row['PROBLEM_NAME'].".pdf")
            );
            array_push($problems, $problem);
        }
        $response = $problems;
    }
    echo json_encode($response);
} else {
#
# Copyright (C) 2003 David Whittington
# Copyright (C) 2003 Jonathan Geisler
# Copyright (C) 2005 Steve Overton
#
# See the file "COPYING" for further information about the copyright
# and warranty status of this work.
#
# arch-tag: judge/problems.php
#
    include_once("lib/header.inc");
    include_once("lib/config.inc");
    include_once("lib/data.inc");
    include_once("lib/session.inc");

echo "<br><table align=center bgcolor=#000000 width=50%
    cellpadding=0 cellspacing=0 border=0><tr><td>\n";
echo "<table align=center width=100% cellpadding=5 cellspacing=1 border=0>\n";
echo "<tr><td colspan=5 align=center bgcolor=$hd_bg_color1>\n";
echo "<font color=$hd_txt_color1><b>Problems Listing</b></font></td></tr>\n";


#echo "<table width=400 align=center>\n";
echo "<tr><td bgcolor=$hd_bg_color2>Problem Name</td>";
echo "<td bgcolor=$hd_bg_color2 align=center>HTML</td>";
#echo "<td bgcolor=$hd_bg_color2 align=center>PS</td>";
echo "<td bgcolor=$hd_bg_color2 align=center>PDF</td></tr>";
#echo "<tr><td bgcolor=$data_bg_color1>All problems</td>";
#echo "<td bgcolor=$data_bg_color1 align=center>";
#echo "<a href='$problem_url/problems.html'>HTML</td>";
//echo "<td bgcolor=$data_bg_color1 align=center>";
//echo "<a href='$problem_url/problems.ps'>PS</td>";
#echo "<td bgcolor=$data_bg_color1 align=center>";
#echo "<a href='$problem_url/problems.pdf'>PDF</td>";
#echo "</tr>";

$problem_counter = 1;
foreach ($problems as $problem) {
    echo "<tr><td bgcolor=$data_bg_color1>$problem_counter - $problem[name]</td>";
//    echo "<tr><td bgcolor=$data_bg_color1>$problem[id] - $problem[name]</td>";
    echo "<td bgcolor=$data_bg_color1 align=center>";
    echo "<a href='$problem_url/$problem[loc]/$problem[name].html'>HTML</a></td>";
  //  echo "<td bgcolor=$data_bg_color1 align=center>";
  //  echo "<a href='$problem_url/$problem[loc]/problem.ps'>PS</a></td>";
    echo "<td bgcolor=$data_bg_color1 align=center>";
    echo "<a href='$problem_url/$problem[loc]/$problem[name].pdf'>PDF</a></td>";
    echo "</tr>";
    $problem_counter++;
}
echo "</table>\n";

    include("lib/footer.inc");
}
?>

