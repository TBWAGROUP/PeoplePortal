<?php
	if (!isset($_GET['p'])) {
		require($_SERVER['DOCUMENT_ROOT'] . "/bdd/connect.inc.php");
		require($_SERVER['DOCUMENT_ROOT'] . "/bdd/adConnect.inc.php");
	}
	$objectsid = $_GET['objectsid'];

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

			$filter = "(objectsid=$objectsid)"; // only enabled users
			$sr = ldap_search($ldapconn, $dn, $filter);

			$entry = ldap_get_entries($ldapconn, $sr);
			$count = $entry["count"];
			echo $count . " entries found : <br />";
			for ($x = 0; $x <= $count; $x++) {
				$objectsid = bin_to_str_sid($entry["objectsid"][$x]);

				if (!empty($entry[$x]["mail"][0])) {
					$primaryEmail = $entry[$x]["mail"][0];
				} else {
					$primaryEmail = "";
				}
				if (!empty($entry[$x]["givenname"][0])) {
					$firstname = $entry[$x]["givenname"][0];
				} else {
					$firstname = "";
				}
				if (!empty($entry[$x]["sn"][0])) {
					$lastname = $entry[$x]["sn"][0];
				} else {
					$lastname = "";
				}
				$upn = genUpnCN($firstname . " " . $lastname);
				$sam = $entry[$x]["samaccountname"][0];
				echo "<strong>" . $upn . "</strong><br />";
				if (!empty($entry[$x]["streetAddress"][0])) {
					$address = $entry[$x]["streetAddress"][0];
				} else {
					$streetAddress = "";
				}
				if (!empty($entry[$x]["mobile"][0])) {
					$mobile = $entry[$x]["mobile"][0];
				} else {
					$mobile = "";
				}
				if (!empty($entry[$x]["telephoneNumber"][0])) {
					$phoneNumber = $entry[$x]["telephoneNumber"][0];
				} else {
					$phoneNumber = "";
				}

				// Additionnal infos
				if (!empty($entry[$x]["description"][0])) {
					$description = $entry[$x]["description"][0];
				} else {
					$description = "";
				}
				if (!empty($entry[$x]["mobile"][0])) {
					$mobile = $entry[$x]["mobile"][0];
				} else {
					$mobile = "";
				}

				if (!empty($entry[$x]["title"][0])) {
					$function = $entry[$x]["title"][0];
				} else {
					$function = "";
				}
				if (!empty($entry[$x]["department"][0])) {
					$department = $entry[$x]["department"][0];
				} else {
					$department = "";
				}
				if (!empty($entry[$x]["company"][0])) {
					$company = $entry[$x]["company"][0];
				} else {
					$company = "";
				}

				include($_SERVER['DOCUMENT_ROOT'] . "/inc/sync/mysqlUserUpOrAdd.inc.php");
				include($_SERVER['DOCUMENT_ROOT'] . "/inc/sync/specificGroupSync.inc.php");
				// echo "<hr />";

			}
		} else {
			echo "LDAP bind failed...";
		}

		ldap_close($ldapconn);
	} // ldapconn
	echo "<p>";
	echo $updated . " user(s) updated <br />";
	echo $inserted . " user(s) inserted <br /></strong>";
	echo "</p>";


	// clean people db from unexisting AD accout present in the pp db
	include($_SERVER['DOCUMENT_ROOT'] . "/inc/sync/syncUsersNotInADRemoveFromDB.php");

	// empGroup sync (this user member of this group)
	include($_SERVER['DOCUMENT_ROOT'] . "/inc/sync/syncEmployeeGroupMembership.php");

?>
