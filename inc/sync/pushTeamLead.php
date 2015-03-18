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
			$queryEmp = mysql_query("
											SELECT * FROM teamLeads AS tl
											INNER JOIN employees AS emp ON tl.employees_idE = emp.idE 
											WHERE contracts_idCon = $idCon AND tl.appType = 0") or die (mysql_error());
			while ($userCheck = mysql_fetch_array($queryEmp)) {
				$managerObjectsid = $userCheck['objectsid'];
				$userDN = getDNUID($ldapconn, $objectsid, $dnServer);
				$userDNmanagor = getDNUID($ldapconn, $managerObjectsid, $dnServer);
				$manager['manager'] = $userDNmanagor;

				$result = ldap_read($ldapconn, $userDN, "(manager=$userDNmanagor)");
				$entries = ldap_get_entries($ldapconn, $result);

				ldap_mod_replace($ldapconn, $userDN, $manager);
				echo "<h6 class='text-success'>" . $userDNmanagor . " <strong>added</strong> as manager of " . $upn . " on AD</h6>";
				$inserted++;
			}
		} else {
			echo "LDAP bind failed...";
		}
	}

	ldap_close($ldapconn);


	echo "<p>";
	echo $inserted . " manager association added in AD <br /></strong>";
	echo "</p>";
?>
