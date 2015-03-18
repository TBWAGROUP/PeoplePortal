<?php

	if (!isset($_GET['p'])) {
		require($_SERVER['DOCUMENT_ROOT'] . "/bdd/connect.inc.php");
		require($_SERVER['DOCUMENT_ROOT'] . "/bdd/adConnect.inc.php");
	}

	$updated = 0;
	$inserted = 0;
	$dn = $dnServer;

	if (isset($_GET['idE'])) {
		$idE = mysql_real_escape_string($_GET['idE']);
	}
	if (isset($_GET['upn'])) {
		$upn = mysql_real_escape_string($_GET['upn']);
	}
	$objectsid = $_GET['objectsid'];

	// Groups NA & JR
	// Connecting to LDAP (port : 389)
	$ldapconn = ldap_connect($server) or die("Could not connect to dc");
	if ($ldapconn) {
		// binding anonymously
		$ldapbind = ldap_bind($ldapconn, $user, $pass);

		if ($ldapbind) {
			$userDN = getDNUID($ldapconn, $objectsid, $dn);
			$groupQuery = mysql_query("SELECT * FROM groups ORDER BY groupName") or die(mysql_error());
			echo "<b>DN: " . $userDN . "</b><br><br>";
			if ($userDN != "") {
				while ($group = mysql_fetch_array($groupQuery)) {
					$groupName = $group['groupName'];
					$idGroup = $group['idGroup'];
					$groupNameDN = "CN=$groupName,OU=Groups,$dn";

					// if member in the AD, add him to the group on the PP DB
					if (!checkGroup($ldapconn, $userDN, $groupNameDN)) // check if the user is on the group
					{
						$checkGroupPP = mysql_query("SELECT * FROM employeeGroup WHERE idE = $idE AND idGroup = $idGroup") or die (mysql_error());
						if (mysql_num_rows($checkGroupPP) == 0) {
							echo "GroupCheck: user " . $upn . " is member of AD " . $groupName . ", but not yet in DB.<br>";
							mysql_query("INSERT INTO employeeGroup (idE, idGroup) VALUES($idE, $idGroup)") or die (mysql_error());
							echo "User <b>" . $upn . "</b>'s membership of group <b>" . $groupName . "</b> succesfully <b>added</b> in DB.<br><br>";
							$inserted++;
						}
					} else {
						$checkGroupPP = mysql_query("SELECT * FROM employeeGroup WHERE idE = $idE AND idGroup = $idGroup") or die (mysql_error());
						if (mysql_num_rows($checkGroupPP) == 1) {
							echo "GroupCheck: user " . $upn . " is NOT member of AD " . $groupName . ", but is in DB.<br>";
							mysql_query("DELETE FROM employeeGroup WHERE idE=$idE AND idGroup = $idGroup") or die (mysql_error());
							echo "User <b>" . $upn . "</b>'s membership of group <b>" . $groupName ."</b> succesfully <b>deleted</b> from DB.<br><br>";
							$updated++;
						}
					}
				}
			} else {
				echo "<h4 class='text-danger'>Oups, user DN can't be determined! Check if the SAM is the same of the UPN on the AD entry of this user.</h4>";
			}
		} else {
			echo "LDAP bind failed...";
		}

		ldap_close($ldapconn);
	}
	if (($inserted > 0) || ($_GET['p'] == "groupSync")) {
		echo "<b>" . $inserted . " relation(s) group(s) inserted <br /></b>";
	}
	if (($updated > 0) || ($_GET['p'] == "groupSync")) {
		echo "<b>" . $updated . " relation(s) group(s) deleted <br /></b>";
	}

?>
