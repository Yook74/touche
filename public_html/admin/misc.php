<?php
#
# See the file "COPYING" for further information about the copyright
# and warranty status of this work.
#
# arch-tag: admin/misc.php
#
        include("lib/admin_config.inc");
        include("lib/data.inc");
        include("lib/session.inc");
        include_once("lib/clone.inc");
        include_once("../../lib/auth.inc");

if ($_POST) {
    $ext_hour = $_POST['ext_hour'];
    $ext_minute = $_POST['ext_minute'];
    $ext_second = $_POST['ext_second'];
    $extension =  $ext_hour*3600 + $ext_minute*60 + $ext_second;

    $sql = mysqli_query($link, "SELECT * FROM CONTEST_CONFIG");
    $row = mysqli_fetch_assoc($sql);
	$contest_np = $row['CONTEST_NAME'];
	$freeze_delay = $row['FREEZE_DELAY'];
	$end_delay = $row['CONTEST_END_DELAY'];

    $freeze_delay += $extension;
	$end_delay += $extension;

	if(isSet($_POST['B1'])) {
	    if($extension) {
	        $sql = "UPDATE CONTEST_CONFIG ";
		    $sql .= "SET FREEZE_DELAY = '$freeze_delay',";
		    $sql .= "    CONTEST_END_DELAY = '$end_delay' ";
		    $sql .= "WHERE CONTEST_NAME = '$contest_name'";
		    $good = mysqli_query($link, $sql);
		if(!$good) {
			echo "Error: ". mysqli_error($link);
		}
		else {
			echo "Contest Extended Successfully.";
		}
    }
}
elseif(isSet($_POST['B2'])) {
#$delete = mysqli_query($link, "UPDATE CONTEST_CONFIG SET FREEZE_DELAY = '0', CONTEST_END_DELAY = '0', START_TS = '0', HAS_STARTED = '0' WHERE CONTEST_NAME = '$contest_name'");
$delete = mysqli_query($link, "UPDATE CONTEST_CONFIG SET START_TS = '0', HAS_STARTED = '0' WHERE CONTEST_NAME = '$contest_name'");
if(!$delete) {
   echo "Error! could not clear the info!!!";
}
$delete = mysqli_query($link, "DELETE FROM CLARIFICATION_REQUESTS");
if(!$delete) {
   echo "Error! could not clear the info!!!";
}
$delete = mysqli_query($link, "DELETE FROM JUDGED_SUBMISSIONS");
if(!$delete) {
   echo "Error! could not clear the info!!!";
}
$delete = mysqli_query($link, "DELETE FROM QUEUED_SUBMISSIONS");
if(!$delete) {
   echo "Error! could not clear the info!!!";
}
$delete = mysqli_query($link, "UPDATE SITE SET START_TS = '0', HAS_STARTED = '0'");
if(!$delete) {
   echo "Error! could not clear the info!!!";
} else {
   echo "Contest Cleared Successfully!";
  }
}
elseif(isSet($_POST['B3'])) {
    $clone_name_raw = $_POST['clone_name'];
    $clone_db_name = preg_replace("/ /", "_", $clone_name_raw);
    $clone_name_escaped = preg_replace("/ /", "\ ", $clone_name_raw);
    $linux_user = get_current_user();

    list($source_public_dir, $source_private_dir, $source_name_escaped, $source_db_name) = get_curr_contest_info();
    list($clone_public_dir, $clone_private_dir) =
        clone_files($clone_name_escaped, $source_public_dir, $source_private_dir, $linux_user);

    clear_directories($clone_private_dir, $linux_user, false);
    make_jail_directories($clone_private_dir, $clone_name_escaped, $linux_user);

    echo "<p>Creating Database . . . <br />";
    clone_database($source_db_name, $clone_db_name, $sql_root_pass);
    update_database($clone_private_dir, $clone_name_escaped, $clone_db_name, $db_host, $db_user, $db_pass);
    echo "Finished.</p>";

    echo "<p>Editing Settings . . . <br />";
    replace_in_file($clone_public_dir . "/lib/database.inc",
        "/$db_name/", $clone_db_name);
    replace_in_file($clone_private_dir . "/chroot_wrapper.c",
        "/$source_name_escaped/", $clone_name_escaped);
    compile_chroot_wrapper($clone_private_dir);
    echo "Finished.</p>";

    echo "<p>To finish setting up the contest go to: <a href=" .
        get_contest_url($_SERVER[SERVER_NAME], $linux_user, $clone_name_raw) . "/admin>Administration setup</a></p>";
}
elseif($_POST['B4']) {
	$sql = mysqli_query($link, "SELECT * FROM TEAMS ORDER BY TEAM_ID");
	if(!$sql) {
		print "Error! could not find any team information";
		exit;
	}
	else {
		$path = "../../../$db_name/judged/";
		$data_path = "../../../$db_name/data/";
                if($_POST['admin_email']) {
                        $cmd = "tar -cf - $path*.cpp $path*.c $path*.java $data_path* | gzip -c > $path";
                        $cmd .= $command .= "$contest_name.tar.gz";
                        system($cmd, $result);
                        if(!$result) {
                                        $email = $_POST['admin_email'];
                                        $cmd = "echo | mutt -s \"Programming Contest Files\" -a $path";
                                        $cmd .= "$contest_name.tar.gz $email < email_body.txt";
                                        system($cmd, $result);
                                        if(!$result) {
                                                echo "Files sent to Administrator<br>";
                                        }
                                        else {
                                                echo "File could not be sent to Administrator!<br>";
                                        }
                        }
                        else {
                                echo "Could not gather contest files for administrator!<br>";
                        }
                }
		#$path = "../../../develop/judged/";
		$num_teams = mysqli_num_rows($sql);
		while($row = mysqli_fetch_assoc($sql)) {
			$team_id = $row['TEAM_ID'];
			$command = "tar -cf - $path$team_id-*.cpp $path$team_id-*.c $path$team_id-*.java $data_path* | gzip -c > $path";
			$command .= "Team$team_id.tar.gz";
			#echo "$command<br>";
			system($command, $result);
			if(!$result) {
				#print "Files Zipped!";
				#email to teams
				if($row['EMAIL']) {
					$email = $row['EMAIL'];
					$cmd = "echo | mutt -s \"Programming Contest Files\" -a $path";
					$cmd .= "Team$team_id.tar.gz $email < email_body.txt";
					system($cmd, $result);
					if(!$result) {
						$team_name_send = $row['TEAM_NAME'];
						echo "Files sent to Team $team_name_send<br>";
					}
					else {
						$team_name_send = $row['TEAM_NAME'];
						echo "File could not be sent to Team $team_name_send!<br>";
					}
				}
			}
			else {
				$team_name_send = $row['TEAM_NAME'];
				echo "Could not gather team files for Team $team_name_send !<br>";
			}
		}
	}
}

}
/*******************************************************
End of POST section
*******************************************************/
	include("lib/header.inc");
	        $link = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
        if(!$link){
                print "Sorry.  Database connect failed.  Check your internet connection.";
                exit;
        }

        $sql = mysqli_query($link, "SELECT * FROM CONTEST_CONFIG");
        if (!$sql) {
                print "Could not tell if a contest has been created.  bailing out.";
                exit;
                #die or break
        }
        if (mysqli_num_rows($sql) > 0) {
        //a contest is already set up!
                $contest=true;
                $row = mysqli_fetch_assoc($sql);
                echo "<center>\n";

                # Print out any errors
                if(isset($error)) {
                    echo "<br>";
                    foreach($error as $er) {
                        echo "<b><font color=#ff0000>$er</font></b>";
                    }
                }

                echo "</center>";
                echo "<p>";
                echo "<table align=center bgcolor=#ffffff cellpadding=0 cellspacing=0 border=0<tr><td>";
                echo "<table width=100% cellpadding=5 cellspacing=1 border=0>\n";
                echo "  <tr bgcolor=\"$hd_bg_color1\">\n";
                echo "<form method=POST action=misc.php>\n";
                echo "          <td align=\"center\" colspan=\"2\"><font color=\"$hd_txt_color1\"><b>Misc Contest Actions</b></font></td>\n";
                echo "  </tr>";
                echo "  <tr bgcolor=\"$hd_bg_color2\">";
                echo "          <td colspan=\"2\">Extend the Contest</td>";
                echo "  </tr>";
                $host = $row['HOST'];
                $contest_name = $row['CONTEST_NAME'];
                //calculating the number of seconds since January 1 1970 at midnight
                //for our particular freeze/contest end values in seconds
                $freeze_hour = gmdate('H', $contest_freeze_time);
                $freeze_minute = gmdate('i', $contest_freeze_time);
                $freeze_second = gmdate('s', $contest_freeze_time);
                $end_hour = gmdate('H', $contest_end_time);
                $end_minute = gmdate('i', $contest_end_time);
                $end_second = gmdate('s', $contest_end_time);
                $ext_hour = gmdate('H', $contest_ext_time);
                $ext_minute = gmdate('i', $contest_ext_time);
                $ext_second = gmdate('s', $contest_ext_time);
        }
	else {
		$ext_hour = "00";
		$ext_minute = "00";
		$ext_second = "00";
	}
        echo "          <tr bgcolor=\"$data_bg_color1\">";
        echo "                  <td>Extend Contest By (HH:mm:ss)</td> ";
        echo "                  <td><input type=\"text\" name=\"ext_hour\" size=\"2\"";
        echo "                          maxlength=2 value=\"$ext_hour\"></input>:";
        echo "                  <input type=\"text\" name=\"ext_minute\" size=\"2\"";
        echo "                          maxlength=2 value=\"$ext_minute\"></input>:";
        echo "                  <input type=\"text\" name=\"ext_second\" size=\"2\"";
        echo "                          maxlength=2 value=\"$ext_second\"></input></td> ";
        echo "          </tr>";
        echo "          <tr bgcolor=\"$data_bg_color1\">";
        echo "                  <td></td> ";
        echo "                  <td><input type=\"submit\" value=\"Extend Contest\" name=\"B1\"></input></td> ";
        echo "          </tr>";


        echo "          <tr bgcolor=\"$hd_bg_color2\">";
        echo "                  <td colspan=2>Clear the Contest</td>";
        echo "          </tr>";
        echo "          <tr bgcolor=\"$data_bg_color1\">";
        echo "                  <td>Problems, Teams, Categories, etc. will be kept.</td>";
        echo "                  <td><input type=\"submit\" value=\"Clear Contest\" name=\"B2\"</input></td>";
	echo "		</tr>";

        echo "          <tr bgcolor=\"$hd_bg_color2\">";
        echo "                  <td colspan=2>Clone the Contest.</td>";
        echo "          </tr>";
        echo "          <tr bgcolor=\"$data_bg_color1\">";
        echo "                  <td>Name of the Clone:</td>";
        echo "                  <td><input type=\"text\" name=\"clone_name\" size=\"17\"></input></td>";
        echo "          </tr>";
        echo "          <tr bgcolor=\"$data_bg_color1\">";
        echo "                  <td></td> ";
        echo "                  <td><input type=\"submit\" value=\"Clone Contest\" name=\"B3\"></input></td> ";
        echo "          </tr>";
	echo "          <tr bgcolor=\"$hd_bg_color2\">";
        echo "                  <td colspan=2>Send files to teams</td>";
        echo "          </tr>";
        echo "          <tr bgcolor=\"$data_bg_color1\">";
        echo "                  <td>Admin Email (Send all contest files to):</td>";
        echo "                  <td><input type=\"text\" name=\"admin_email\" size=\"17\"></input></td>";
        echo "          </tr>";
        echo "          <tr bgcolor=\"$data_bg_color1\">";
        echo "                  <td>Zip each teams files and send files</td> ";
        echo "                  <td><input type=\"submit\" value=\"Send Zip Files\" name=\"B4\"></input></td> ";
        echo "          </tr>";
        echo "  </form>";

        if(!mysqli_num_rows( mysqli_query($link, "SHOW TABLES LIKE 'JUDGED_SUBMISSIONS_COPY'"))){
                echo "  <form action='rejudge.php' method='POST'>\n";
                echo "          <tr bgcolor=\"$hd_bg_color2\">";
                echo "                  <td colspan=2>recalculate responses</td>";
                echo "          </tr>";
                echo "          <tr bgcolor=\"$data_bg_color1\">";
                echo "                  <td>calculate new auto responses for each submission</td> ";
                echo "                  <td><input type=\"submit\" value='recalculate responses' onClick='return confirmSubmit()'></td> ";
                echo "          </tr>";

                echo "  </form></table>";
        }
        else{
                echo "          <tr bgcolor=\"$hd_bg_color2\">";
                echo "                  <td colspan=2>recalculate responses</td>";
                echo "          </tr>";
                echo "          <tr bgcolor=\"$data_bg_color1\">";
                echo "                  <td>It has Already been recalculated</td> ";
                echo "                  <td><a href='review.php'>review new judgements</a></td> ";
                echo "          </tr>";
                echo " </table>";

        }

echo " <script LANGUAGE='JavaScript'>
                        <!--
                        // Nannette Thacker http://www.shiningstar.net
                        function confirmSubmit()
                        {
                                var agree=confirm('Warning!  This process takes a considerable amount, and change the database and file system so that current standings will be lost!!');
                                if (agree)
                                        return true ;
                                else
                                        return false ;
                                }
                        // -->
                </script> ";

                include("lib/footer.inc");
?>	
