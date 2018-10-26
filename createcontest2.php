<?php

include_once("lib/session.inc");
include_once("lib/create.inc");
include_once("lib/auth.inc");
if($_POST['B1'] == "Submit") {
   $c_name_raw = $_POST['contest_name'];
   $db_host = $_POST['dbhost'];
   $db_pass = $_POST['dbpassword'];
   $contest_host = $_POST['contest_host'];
}
?>
<html>
<body bgcolor="<?=$page_bg_color?>" link="0000cc" alink="000066" vlink="0000cc">
<table width="90%" align="center" cellpadding="1" cellspacing="0" border="0" bgcolor="#000000">
        <tr><td>
                <table width="100%" cellpadding="5" cellspacing="0" border="0">
                        <tr bgcolor="<?=$title_bg_color?>">
                                <td>
                                <!-- Beautification hack. 2006-09-25 -sb -->

                                <font color="#ffffff">
                                <b>Creating Contest</b>  <small></small>
                                </font>
                                </td>
                                <td align="right">
                                         <font color="#ffffff">
                                         <b>ADMIN</b>
                                         </font>
                                </td>
                        </tr>
                        <tr>
                                <td bgcolor="#ffffff" colspan="2">
				<center><b>
<?php
$db_name = preg_replace("/ /", "_", $c_name_raw);
$c_name_escaped = preg_replace("/ /", "\ ", $c_name_raw);
$linux_user = get_current_user();
$contest_url = get_contest_url($_SERVER[SERVER_NAME], ~$linux_user, $c_name_raw);

echo "<p>As $linux_user . . .</p>\n";
list($public_contest_dir, $private_contest_dir) = copy_from_develop($c_name_escaped, $linux_user);

clear_directories($private_contest_dir, $linux_user);
make_jail_directories($private_contest_dir, $c_name_escaped, $linux_user);

echo "<p>Creating Database . . . <br />";
create_database($db_name, $sql_root_pass);
seed_database($private_contest_dir, $c_name_escaped, $contest_host, $db_name, $db_host, $sql_username, $db_pass);
echo "Finished.</p>";


echo "<p>Editing Settings . . . <br />";
fill_in_database_inc($public_contest_dir, $db_name, $db_host, $sql_username, $db_pass);
fill_in_chroot_wrapper($private_contest_dir, $c_name_escaped);
compile_chroot_wrapper($private_contest_dir);
set_contest_perms($public_contest_dir);
echo "Finished.</p>";

//fill_in_readme($public_contest_dir, $contest_url);
echo "<p>To finish setting up the contest go to: <a href='$contest_url/admin'>Administration setup</a></p>";
?>
</center></b></td></tr>
</body>
</html>
