<?php

	$time_start_syncempgroups = microtime(true);

	$updated = 0;
	$inserted = 0;
	// Connecting to LDAP (port : 389)
	$ldapconn = ldap_connect($server) or die("Could not connect to dc");
	if ($ldapconn) {
		// binding anonymously
		$ldapbind = ldap_bind($ldapconn, $user, $pass);

		if ($ldapbind) {
			$queryEmp = mysql_query("SELECT * FROM employees WHERE ppAccountStatut < 4") or die (mysql_error());
			while ($userCheck = mysql_fetch_array($queryEmp)) {
				$upn = $userCheck['upn'];
				$idE = $userCheck['idE'];
				$objectsid = $userCheck['objectsid'];

				$userDN = getDNUID($ldapconn, $objectsid, $dn);
				$groupQuery = mysql_query("SELECT * FROM groups ORDER BY groupName") or die(mysql_error());
				while ($group = mysql_fetch_array($groupQuery)) {
					$groupName = $group['groupName'];
					$idGroup = $group['idGroup'];
					$groupNameDN = "CN=$groupName,OU=Groups,$dn";

					// if member in the AD, add him to the group on the PP DB
					if (!checkGroup($ldapconn, $userDN, $groupNameDN)) // check if the user is on the group
					{
						$checkGroupPP = mysql_query("SELECT * FROM employeeGroup WHERE idE = $idE AND idGroup = $idGroup") or die (mysql_error());
						if (mysql_num_rows($checkGroupPP) == 0) {
							mysql_query("INSERT INTO employeeGroup (idE, idGroup) VALUES($idE, $idGroup)") or die (mysql_error());
							echo "user : " . $upn . " added in " . $groupName . "<br />";
							$inserted++;
						}
					} else {
						$checkGroupPP = mysql_query("SELECT * FROM employeeGroup WHERE idE = $idE AND idGroup = $idGroup") or die (mysql_error());
						if (mysql_num_rows($checkGroupPP) == 1) {
							mysql_query("DELETE FROM employeeGroup WHERE idE=$idE AND idGroup = $idGroup") or die (mysql_error());
							echo $upn . " deleted from " . $groupName . "<br />";
							$updated++;
						}
					}
				}
			}
		} else {
			echo "LDAP bind failed...";
		}
	}

	ldap_close($ldapconn);


	echo "<p>";
	echo $inserted . " user / group association <br /></strong>";
	echo "</p>";

	// Display Script End time
	$time_end_syncempgroups = microtime(true);
	$execution_time_syncempgroups = round(($time_end_syncempgroups - $time_start_syncempgroups), 2);
	echo '<h4><b>Total Execution Time of syncing employee groups:</b> '.$execution_time_syncempgroups.' seconds.</h4><br><br>';


?>
