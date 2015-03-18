<!--
This script
1. sets all users status to Archived if not found in AD
2a. loops through all enabled AD group NA All members to gather non-empty AD info fields, if empty retaining info from DB.employees using objectsID as key
2b. executes mysql updates with found AD info (per user)
2b1. inserting newly-found AD functions, departments and labels in corresponding DBs
2b2a. if user found in DB.employees, update DB.employees (trash to frozen if applicable) and DB.contracts
2b2b. if user not found in DB.employees, insert into DB.employees (frozen) and DB.contracts
3. checks group membership for all employees

Included by:
index.php (case syncEmp)
inc/sync/syncGlobalAD.php

Hrefs pointing here:

Requires:
/bdd/connect.inc.php
/bdd/adConnect.inc.php

Includes:
/inc/sync/userDB_RemoveSync.php
/inc/sync/mysqlUserUpOrAdd.inc.php
/inc/sync/empGroupSync.php

Form actions:

-->

<?php

	// Script function: get list of all active AD users member of NA All, then use their AD info to update PP DB



	if (!isset($_GET['p'])) {
		require($_SERVER['DOCUMENT_ROOT'] . "/bdd/connect.inc.php");
		require($_SERVER['DOCUMENT_ROOT'] . "/bdd/adConnect.inc.php");
	}

	// clean people db from unexisting AD account present in the pp db
	include($_SERVER['DOCUMENT_ROOT'] . "/inc/sync/syncUsersNotInADRemoveFromDB.php");

	$time_start_usersADtoDB = microtime(true);

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

			$filter = "(&(memberOf=CN=NA All,OU=Groups,$dn)(!(userAccountControl:1.2.840.113556.1.4.803:=2)))"; // only enabled users
			$sr = ldap_search($ldapconn, $dn, $filter);

			$entry = ldap_get_entries($ldapconn, $sr);
			$count = $entry["count"];
			echo $count . " entries found (enabled AD users, member of NA All): <br />";
			// loop through all ldap query results, each is an enabled AD user who is member of NA All
			for ($x = 0; $x < $count; $x++) {
				if (isset($entry[$x])) {
					$objectsid = bin_to_str_sid($entry[$x]["objectsid"][0]);
					$userDN = getDNUID($ldapconn, $objectsid, $dnServer);
					$queryUser = mysql_query("
													SELECT * FROM employees AS emp
														INNER JOIN contracts AS cont ON cont.idCon = emp.contract
														INNER JOIN departments AS dep ON cont.idDep = dep.idDep
														INNER JOIN labels AS lab ON lab.idLab = cont.idLab
														INNER JOIN functions AS func ON func.idFunc = cont.idFunc
													WHERE objectsid = \"$objectsid\" ") or die (mysql_error());
					$userSQL = mysql_fetch_array($queryUser);

					// if ldap returns a non-empty field, use that AD info, if not, keep DB info
					if (!empty($entry[$x]["mail"][0])) {
						$primaryEmail = $entry[$x]["mail"][0];
					} else {
						$primaryEmail = $userSQL["primaryEmail"];
					}
					if (!empty($entry[$x]["givenname"][0])) {
						$firstname = $entry[$x]["givenname"][0];
					} else {
						$firstname = $userSQL["firstname"];
					}
					if (!empty($entry[$x]["sn"][0])) {
						$lastname = $entry[$x]["sn"][0];
					} else {
						$lastname = $userSQL["lastname"];
					}
					$upn = genUpnCN($firstname . " " . $lastname);
					$sam = $entry[$x]["samaccountname"][0];
					if (!empty($entry[$x]["mobile"][0])) {
						$mobile = $entry[$x]["mobile"][0];
					} else {
						$mobile = $userSQL["mobile"];
					}
					if (!empty($entry[$x]["telephoneNumber"][0])) {
						$phoneNumber = $entry[$x]["telephoneNumber"][0];
					} else {
						$phoneNumber = $userSQL["phoneNumber"];
					}

					// Additionnal infos
					if (!empty($entry[$x]["description"][0])) {
						$description = $entry[$x]["description"][0];
					} else {
						$description = $userSQL["note"];
					}

					if (!empty($entry[$x]["title"][0])) {
						$function = $entry[$x]["title"][0];
					} else {
						$function = $userSQL["functionName"];
					}
					if (!empty($entry[$x]["department"][0])) {
						$department = $entry[$x]["department"][0];
					} else {
						$department = $userSQL["nameDepartment"];
					}
					if (!empty($entry[$x]["company"][0])) {
						$company = $entry[$x]["company"][0];
					} else {
						$company = $userSQL["companyCode"];
					}

					if (!empty($entry[$x]["accountexpires"][0])) {
						$disableAccountDate = accountExpiresToDate($entry[$x]["accountexpires"][0]);
					} else {
						$disableAccountDate = "";
					}
					if ($disableAccountDate = "25/01/1975") {
						$disableAccountDate = "";
					}

					$domainAdminGroupName = "Domain Admins"; // EN Win Serv Version
					// $domainAdminGroupName = "Admins du domaine"; // FR version
					$domainAdminGroupDN = "CN=$domainAdminGroupName, CN=Users, $dnServer";
					// we check if the association as made in AD
					if (!checkGroup($ldapconn, $userDN, $domainAdminGroupDN)) // if the user is on the group (checkgroup)
					{
						$itNetworkAdmin = 1;
					} else {
						$itNetworkAdmin = 0;
					}

					include($_SERVER['DOCUMENT_ROOT'] . "/inc/sync/mysqlUserUpOrAdd.inc.php");
				}
			}
		} else {
			echo "LDAP bind failed...";
		}
	}
	echo "<p>";
	echo $updated . " user(s) updated <br />";
	echo $inserted . " user(s) inserted <br /></strong>";
	echo "</p>";

	// Display Script End time
	$time_end_usersADtoDB = microtime(true);
	$execution_time = round(($time_end_usersADtoDB - $time_start_usersADtoDB), 2);
	echo '<h4><b>Total Execution Time of inserting/updating AD users in DB:</b> '.$execution_time.' seconds.</h4><br><br>';


	// empGroup sync (this user member of this group)
	include($_SERVER['DOCUMENT_ROOT'] . "/inc/sync/syncEmployeeGroupMembership.php");

?>
