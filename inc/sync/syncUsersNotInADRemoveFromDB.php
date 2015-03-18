<!--
This script sets user status to status 4 (Archived) in DB.employees if not found in AD by LDAP search

Included by:
inc/sync/specificUserAdSync.php
inc/sync/syncUsersFromADtoDB.php

Hrefs pointing here:

Requires:

Includes:

Form actions:

-->

<?php

	/**
	 * ppAccountStatut = 0 : Active     - user present in AD and listed in employees list
	 * ppAccountStatut = 1 : Trash      - user not found in AD, not listed in employees list, ready for suppression
	 * ppAccountStatut = 2 : Frozen     - user newly found in AD, not listed in employees list, waiting assignment
	 * ppAccountStatut = 3 : Standalone - user present in AD, not listed in employees list
	 * ppAccountStatut = 4 : Archived   - user removed from AD, not listed in employees list
	 * ppAccountStatut = 5 : USF        - new user still in flow
	 **/

	$time_start_deleteusers = microtime(true);

	$updated = 0;
	$inserted = 0;

	// Import company from AD

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
			$groupQuery = mysql_query("SELECT * FROM employees WHERE ppAccountStatut < 4 ORDER BY idE ASC") or die(mysql_error());
			if (mysql_num_rows($groupQuery) > 0) {
				echo "Total users in DB employees with ppAccountStatut less than 4: " . mysql_num_rows($groupQuery);
				echo "<br>";

				// loop through all DB employees who are not "Archived" using ojectsID to search ldap
				while ($emp = mysql_fetch_array($groupQuery)) {

					$idE = $emp['idE'];
					$upn = $emp['upn'];
					$objectsid = $emp['objectsid'];

					$filter = "(objectsid=$objectsid)"; // find users by objectsID
					$sr = ldap_search($ldapconn, $dn, $filter);

					$entries = ldap_get_entries($ldapconn, $sr);
					if ($entries["count"] == 0) {

						$time_start_ldap = microtime(true);

						echo "<strong>" . $upn . "</strong>";
						echo " not found in AD. Contract status changed to archived (4). Employee status changed to archived (4). ObjectsID set to NULL. Removed teamlead status (0). Group memberships deleted. Teamlead memberships deleted.<br />"; // debug
						mysql_query("UPDATE contracts SET validationStage = '4' WHERE idCon = \"$idCon\"") or die(mysql_error());
						mysql_query("UPDATE employees SET ppAccountStatut = '4' WHERE idE = \"$idE\"") or die(mysql_error());
						mysql_query("UPDATE employees SET objectsid = NULL WHERE idE = \"$idE\"") or die(mysql_error());
						mysql_query("UPDATE employees SET teamLead = '0' WHERE idE = \"$idE\"") or die(mysql_error());
						mysql_query("DELETE FROM employeeGroup WHERE idE = \"$idE\"") or die(mysql_error());
						mysql_query("DELETE FROM teamLeads WHERE employees_idE = \"$idE\"") or die(mysql_error());

						$inserted++;

						// Display Script End time
						$time_end_ldap = microtime(true);
						$execution_time_ldap = round(($time_end_ldap - $time_start_ldap), 2);
						echo '<b>Execution Time of deleting unfound AD user from DB:</b> '.$execution_time_ldap.' seconds.<br><br>';

					}
				}
			}
		} else {
			echo "LDAP bind failed...";
		}

		ldap_close($ldapconn);
	} // END IF isset $_GET['upn'];
	echo "<p>";
	echo $inserted . " user(s) moved to Archive (ppAccountStatut updated to 4) <br /></strong>";
	echo "</p>";

	// Display Script End time
	$time_end_deleteusers = microtime(true);
	$execution_time_deleteusers = round(($time_end_deleteusers - $time_start_deleteusers), 2);
	echo '<h4><b>Total Execution Time of deleting unfound AD users from DB:</b> '.$execution_time_deleteusers.' seconds.</h4><br><br>';


?>
