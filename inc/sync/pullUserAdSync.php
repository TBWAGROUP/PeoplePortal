<?php
	if (!isset($_GET['p'])) {
		require($_SERVER['DOCUMENT_ROOT'] . "/bdd/connect.inc.php");
		require($_SERVER['DOCUMENT_ROOT'] . "/bdd/adConnect.inc.php");
	}
	$upn = $_GET['upn'];
	$objectsid = $_GET['objectsid'];
	$idCon = $_GET['idCon'];
	$idE = $_GET['idE'];

	$updated = 0;
	$inserted = 0;

	$groupQuery = mysql_query("SELECT * FROM employees WHERE objectsid=\"$objectsid\"") or die(mysql_error());
	$emp = mysql_fetch_array($groupQuery);

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

			$filter = "(objectsid=$objectsid)"; // only single, enabled users
			$sr = ldap_search($ldapconn, $dn, $filter);

			$entry = ldap_get_entries($ldapconn, $sr);
			$count = $entry["count"];
			echo $count . " entries found : <br />";
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

	echo "<h6 class='text-success' ><img src='img/ajax-loader.gif' /> Informations updated. Redirecting...</h6><meta http-equiv='refresh' content='1;url=index.php?p=emp&idE=$idE&idCon=$idCon&upn=$upn&objectsid=$objectsid'>";
?>
