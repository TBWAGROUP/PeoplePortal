<?php
	$updated = 0;
	$inserted = 0;
	$dn = $dnServer;

	if (isset($_GET['idE'])) {
		$idE = mysql_real_escape_string($_GET['idE']);
	}
	if (isset($_GET['upn'])) {
		$upn = mysql_real_escape_string($_GET['upn']);
	}

	//mysql_query("DELETE FROM employeeGroup WHERE idE = $idE") or die (mysql_error());

	// Groups NA & JR
	// Connecting to LDAP (port : 389)
	$ldapconn = ldap_connect($server) or die("Could not connect to dc");
	if ($ldapconn) {
		// binding anonymously
		$ldapbind = ldap_bind($ldapconn, $user, $pass);

		if ($ldapbind) {
			$userDN = getDN($ldapconn, $upn, $dn);
			$groupQuery = mysql_query("SELECT * FROM groups ORDER BY groupName") or die(mysql_error());

			while ($group = mysql_fetch_array($groupQuery)) {
				$groupName = $group['groupName'];
				$idGroup = $group['idGroup'];
				$groupNameDN = "CN=$groupName,OU=Groups,$dn";

				// if member in the AD, add him to the group on the PP DB
				$checkGroupPP = mysql_query("SELECT * FROM employeeGroup WHERE idE = $idE AND idGroup = $idGroup") or die (mysql_error());
				if (mysql_num_rows($checkGroupPP) == 0) {
					if (!checkGroup($ldapconn, $userDN, $groupNameDN)) // check if the user is on the group
					{
						ldap_mod_add($ldapconn, $userDN, $groupName);
						echo "User : " . $upn . " added in " . $groupName . "<br />";
						$inserted++;
					}
				} else {
					$checkGroupPP = mysql_query("SELECT * FROM employeeGroup WHERE idE = $idE AND idGroup = $idGroup") or die (mysql_error());
					if (mysql_num_rows($checkGroupPP) == 1) {
						mysql_query("DELETE FROM employeeGroup WHERE idE=$idE AND idGroup = $idGroup") or die (mysql_error());
						echo "User : " . $upn . " deleted from " . $groupName . "<br />";
						$updated++;
					}
				}
			}
		} else {
			echo "LDAP bind failed...";
		}

		ldap_close($ldapconn);
	}
	if (($inserted > 0) || ($_GET['p'] == "groupSync")) {
		echo "<br />" . $inserted . " relation(s) group(s) inserted <br /></strong>";
	}
	if (($updated > 0) || ($_GET['p'] == "groupSync")) {
		echo "<br />" . $updated . " relation(s) group(s) deleted <br /></strong>";
	}

?>
