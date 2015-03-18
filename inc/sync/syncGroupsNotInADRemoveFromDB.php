<?php

	$time_start_deletegroups = microtime(true);


	$updated = 0;
	$inserted = 0;


	// Connecting to LDAP (port : 389)
	$ldapconn = ldap_connect($server) or die("Could not connect to dc");
	if ($ldapconn) {
		// binding anonymously
		$ldapbind = ldap_bind($ldapconn, $user, $pass);

		if ($ldapbind) {
			//**********************************************************************************************
			//**********************************************************************************************
			// LDAP QUERIES

			$dn = $dnServer;

			// Update AD -> MYSQL
			$groupQuery = mysql_query("SELECT * FROM groups") or die(mysql_error());
			while ($emp = mysql_fetch_array($groupQuery)) {
				$idGroup = $emp['idGroup'];
				$groupName = $emp['groupName'];

				$filter = "(&(ObjectCategory=group)(name=" . $groupName . "))";
				$sr = ldap_search($ldapconn, $dn, $filter);

				$entries = ldap_get_entries($ldapconn, $sr);
				if ($entries["count"] == 0) {
					echo "AD group <b>" . $groupName . "</b> not found in AD, deleted from DB.<br>";
					mysql_query("DELETE FROM employeeGroup WHERE idGroup = \"$idGroup\"") or die(mysql_error());
					mysql_query("DELETE FROM functionGroups WHERE idGroup = \"$idGroup\"") or die(mysql_error());
					mysql_query("DELETE FROM groupsAccess WHERE idGroup = \"$idGroup\"") or die(mysql_error());
					mysql_query("DELETE FROM groups WHERE idGroup = \"$idGroup\"") or die(mysql_error());
					$inserted++;
				}
			}
		} else {
			echo "LDAP bind failed...";
		}

		ldap_close($ldapconn);
	} // END IF isset $_GET['upn'];
	echo "<p>";
	echo $inserted . " group(s) deleted <br /></strong>";
	echo "</p>";

	// Display Script End time
	$time_end_deletegroups = microtime(true);
	$execution_time = round(($time_end_deletegroups - $time_start_deletegroups), 2);
	echo '<b>Total Execution Time of deleting unfound AD groups from DB:</b> '.$execution_time.' seconds.<br><br>';

?>
