<?php




	$updated=0;
	$inserted=0;

	
	// Connecting to LDAP (port : 389)
	$ldapconn = ldap_connect($server) or die("Could not connect to dc");
	if ($ldapconn) 
	{
		// binding anonymously
		$ldapbind = ldap_bind($ldapconn, $user, $pass);

		if ($ldapbind)
		{
			//**********************************************************************************************
			//**********************************************************************************************
			// LDAP QUERIES
		
			$dn      = $dnServer;
			
			// Update AD -> MYSQL
			$groupQuery = mysql_query("SELECT * FROM groups") or die(mysql_error());
			while($emp=mysql_fetch_array($groupQuery))
			{
				$idGroup = $emp['idGroup'];
				$groupName = $emp['groupName'];
				
				$filter  = "(&(ObjectCategory=group)(name=".$groupName."))"; 
				$sr      = ldap_search($ldapconn, $dn,$filter);	
		
				$entries = ldap_get_entries($ldapconn,$sr);
				if ($entries["count"] == 0) 
				{
					echo "<strong>".$groupName."</strong>";
					echo " not found in the AD<br />"; // debug
					mysql_query("DELETE FROM employeeGroup WHERE idGroup = \"$idGroup\"") or die(mysql_error());
					mysql_query("DELETE FROM functionGroups WHERE idGroup = \"$idGroup\"") or die(mysql_error());
					mysql_query("DELETE FROM groupsAccess WHERE idGroup = \"$idGroup\"") or die(mysql_error());
					mysql_query("DELETE FROM groups WHERE idGroup = \"$idGroup\"") or die(mysql_error());
					$inserted++;					
				}
			}
		}
		else {
			echo "LDAP bind failed...";
		}

	ldap_close ($ldapconn);

} // END IF isset $_GET['upn'];
	echo "<p>";
	echo $inserted." group(s) deleted <br /></strong>";
	echo "</p>";
?>
