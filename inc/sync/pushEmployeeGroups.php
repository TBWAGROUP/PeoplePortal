<!--
This script pushes AD group membership of a single user, searched by objectsid

Included by:
/inc/ppTools/userGroupsManager.inc.php (existing user changes)
/inc/userStartForm/provisioning/it.fields.php (new user addition)

Hrefs pointing here:

Requires:

Includes:

Form actions:

-->

<?php

	$updated = 0;
	$inserted = 0;
	// Connecting to LDAP (port : 389)
	$ldapconn = ldap_connect($server) or die("Could not connect to dc");
	if ($ldapconn) {
		// binding anonymously
		$ldapbind = ldap_bind($ldapconn, $user, $pass);

		if ($ldapbind) {
			$dn = $dnServer;
			$queryEmp = mysql_query("SELECT * FROM employees WHERE objectsid = \"$objectsid\"") or die (mysql_error());
			while ($userCheck = mysql_fetch_array($queryEmp)) {

				$objectsid = $userCheck['objectsid'];
				$userDN = getDNUID($ldapconn, $objectsid, $dnServer);
				$groupMember['member'] = $userDN;
				$groupQuery = mysql_query("SELECT * FROM groups ORDER BY groupName") or die(mysql_error());
				while ($group = mysql_fetch_array($groupQuery)) {
					$groupName = $group['groupName'];
					$idGroup = $group['idGroup'];
					$groupNameDN = "CN=$groupName,OU=Groups,$dnServer";
					$checkGroupPP = mysql_query("SELECT * FROM employeeGroup WHERE idE = $idE AND idGroup = $idGroup") or die (mysql_error());
					if (mysql_num_rows($checkGroupPP) > 0) {
						// we check if the association as made in AD
						if (checkGroup($ldapconn, $userDN, $groupNameDN)) // if the user is not on the group (checkgroup)
						{
							// if not, we had the user to the group on the AD
							if (ldap_mod_add($ldapconn, $groupNameDN, $groupMember)) {
								echo "<h6 class='text-success'>" . $upn . " <strong>added</strong> in " . $groupName . " on AD</h6>";
							} else {
								echo "error - not added in group : " . $groupName . "<br />";
							}
							$inserted++;
						}
					} else {
						if (!checkGroup($ldapconn, $userDN, $groupNameDN)) // if the user is on the group (!checkgroup)
						{
							if (ldap_mod_del($ldapconn, $groupNameDN, $groupMember)) {
								echo "<h6 class='text-danger'>" . $upn . " <strong>deleted</strong> from " . $groupName . " on AD</h6>";
							} else {
								echo "error - not deleted from group : " . $groupName . "<br />";
							}
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
	echo $inserted . " user / group association in AD <br /></strong>";
	echo "</p>";
?>
