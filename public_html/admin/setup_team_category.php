<?php
#
# Copyright (C) 2002 David Whittington
# Copyright (C) 2005 Jonathan Geisler
# Copyright (C) 2005 Victor Replogle
# Copyright (C) 2005 Steve Overton
# Copyright (C) 2005 David Crim
#
# See the file "COPYING" for further information about the copyright
# and warranty status of this work.
#
# arch-tag: standings.php
#
include_once("lib/admin_config.inc");
include_once("lib/data.inc");
include_once("lib/session.inc");
include_once("lib/header.inc");

    $link = mysqli_connect ($db_host , $db_user, $db_pass, $db_name) or die ("Could not connect to database");

    if(isset($_POST["submit"]))
    {
    	$sql = "DELETE FROM CATEGORY_TEAM";
	mysqli_query($link, $sql);
	
    	foreach($_POST as $box => $value) {
	    if($value == 'on') {
	    	$team = explode("|", $box);
		$sql = "INSERT INTO CATEGORY_TEAM (TEAM_ID, CATEGORY_ID) VALUES ('$team[0]', '$team[1]');";
		mysqli_query($link, $sql);
	    }
	}
    }

    $sql = "SELECT * FROM CATEGORIES";
    $category = mysqli_query($link, $sql);
    $num_cat = mysqli_num_rows($category);
    $cat_row = mysqli_fetch_assoc($category);

    $tmp = $num_cat * 10;
    echo"<form method='POST' action='setup_team_category.php'>";
    echo "<br><table align=center bgcolor=#000000 width='$tmp%' cellpadding=5 cellspacing=1 border=0>\n";
    echo "<tr><td align=center colspan=99 bgcolor=$hd_bg_color1>";
       echo "<font color=$hd_txt_color1><b>Teams</b></font>";
    echo "</td></tr>\n";
    echo "<tr bgcolor=$hd_bg_color2>\n";
    echo "<td align=center><font color=$hd_txt_color2><b>Team Name</b></font></td>\n";
    for($i=1; $i<=$num_cat; $i++) { 
        echo "<td align=center><font color=$hd_txt_color2><b>".$cat_row["CATEGORY_NAME"]."</b></font></td>";
        $cat_row = mysqli_fetch_assoc($category);
        }
    echo "</tr>\n";

    $sql = "SELECT * FROM TEAMS";
    $team = mysqli_query($link, $sql);
    $num_teams = mysqli_num_rows($team);
    $team_row = mysqli_fetch_assoc($team);

    $sql = "SELECT * FROM CATEGORIES";
    $category = mysqli_query($link, $sql);
    for($i=0; $i<$num_teams; $i++) {
        if($i%2 == 0) {
            echo "<tr bgcolor=\"$data_bg_color1\">\n";
        } else {
            echo "<tr bgcolor=\"$data_bg_color2\">\n";
        }
        echo "<td>".$team_row["TEAM_NAME"]."</td>";

        for($x=1; $x<=$num_cat; $x++) {
            //Grab the category id that correlates to the right category
            $cat_row = mysqli_fetch_assoc($category);
            $category_id_sql = "SELECT CATEGORY_ID FROM CATEGORIES WHERE CATEGORY_NAME = '" . $cat_row["CATEGORY_NAME"] . "'";
            $category_id_query = mysqli_query($link, $category_id_sql);
            $category_id_array = mysqli_fetch_assoc($category_id_query);
            $category_id = $category_id_array["CATEGORY_ID"];

            $sql = "SELECT * FROM CATEGORY_TEAM WHERE TEAM_ID = ".$team_row["TEAM_ID"]." AND CATEGORY_ID=$category_id";
            $query = mysqli_query($link, $sql);
            $check = mysqli_num_rows($query);

            echo "<td><input type='checkbox' ";
            if($check==1)
                echo"checked=checked ";
            echo "name='".$team_row["TEAM_ID"]."|$category_id'/></td>";
        }

        $team_row = mysqli_fetch_assoc($team);
        echo "</tr>";
    }
    echo "</table>";
    echo "<input type='submit' value='Make Changes' name='submit'/>";
    echo "</form>";
?>
