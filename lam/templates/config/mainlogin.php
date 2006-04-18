<?php
/*
$Id$

  This code is part of LDAP Account Manager (http://www.sourceforge.net/projects/lam)
  Copyright (C) 2003 - 2006  Roland Gruber

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/


/**
* Login page to change the main preferences.
*
* @package configuration
* @author Roland Gruber
*/


/** Access to config functions */
include_once('../../lib/config.inc');
/** Used to print status messages */
include_once('../../lib/status.inc');

// start session
session_save_path("../../sess");
@session_start();

setlanguage();

// remove settings from session
if (isset($_SESSION["mainconf_password"])) unset($_SESSION["mainconf_password"]);

// check if user entered a password
if (isset($_POST['passwd'])) {
	$cfgMain = new CfgMain();
	if (isset($_POST['passwd']) && ($_POST['passwd'] == $cfgMain->password)) {
		$_SESSION["mainconf_password"] = $_POST['passwd'];
		metaRefresh("mainmanage.php");
		exit();
	}
	else {
		$message = _("The password is invalid! Please try again.");
	}
}


echo $_SESSION['header'];

?>

		<title>
			<?php
				echo _("Login");
			?>
		</title>
		<link rel="stylesheet" type="text/css" href="../../style/layout.css">
	</head>
	<body>
		<?php
			// set focus on password field
			echo "<script type=\"text/javascript\" language=\"javascript\">\n";
			echo "<!--\n";
			echo "window.onload = function() {\n";
				echo "loginField = document.getElementsByName('passwd')[0];\n";
				echo "loginField.focus();\n";
			echo "}\n";
			echo "//-->\n";
			echo "</script>\n";
		?>
		<p align="center"><a href="http://lam.sourceforge.net" target="_blank">
			<img src="../../graphics/banner.jpg" border=1 alt="LDAP Account Manager"></a>
		</p>
		<hr><br><br>
		<!-- form to change main options -->
		<form action="mainlogin.php" method="post">
		<table align="center" border="2" rules="none" bgcolor="white">
			<tr>
				<td style="border-style:none" rowspan="3" width="20"></td>
				<td style="border-style:none" height="20"></td>
				<td style="border-style:none" rowspan="3" width="20"></td>
			</tr>
			<tr>
				<td style="border-style:none" align="center"><b> <?php echo _("Please enter the master password to change the general preferences:"); ?> </b></td>
			</tr>
			<tr><td style="border-style:none" >&nbsp;</td></tr>
<?php
	// print message if login was incorrect or no config profiles are present
	if (isset($message)) {  // $message is set by confmain.php (requires conflogin.php then)
		echo "<tr>\n";
			echo "<td style=\"border-style:none\" rowspan=\"2\"></td>\n";
			echo "<td style=\"border-style:none\" align=\"center\"><b><font color=red>" . $message . "</font></b></td>\n";
			echo "<td style=\"border-style:none\" rowspan=\"2\"></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
			echo "<td style=\"border-style:none\" >&nbsp;</td>\n";
		echo "</tr>\n";
	}
?>
			<tr>
				<td style="border-style:none" rowspan="3" width="20"></td>
				<td style="border-style:none" align="center">
					<input type="password" name="passwd">
					<input type="submit" name="submit" value="<?php echo _("Ok"); ?>">
					&nbsp;<a href="../help.php?HelpNumber=236" target="lamhelp">
					<img src="../../graphics/help.png" alt="<?php echo _('Help'); ?>" title="<?php echo _('Help'); ?>">
					</a>
				</td>
				<td style="border-style:none" rowspan="3" width="20"></td>
			</tr>
			<tr>
				<td  style="border-style:none">&nbsp;</td>
			</tr>
			<tr>
				<td style="border-style:none" height="20"></td>
			</tr>
		</table>
		</form>

		<p><br><br><br><br><br></p>

		<!-- back to login page -->
		<p>
			<a href="../login.php"> <?php echo _("Back to Login"); ?> </a>
		</p>

	</body>
</html>
