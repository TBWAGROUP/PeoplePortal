<?php

/**
	ppAccountStatut = 0 : user present in AD (default)
	ppAccountStatut = 1 : user not found in AD and ready for suppression
	ppAccountStatut = 2 : user not found in AD but keep his PP account has "unactivated" (frozen) (not listed in the employees list)
	ppAccountStatut = 3 : user not found in AD but need to be listed in the employees list
	ppAccountStatut = 4 : archived user. Keep all his data but nothing is listed in employees list
**/



	$updated=0;
	$inserted=0;

	// Import company from AD
	
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
			$groupQuery = mysql_query("SELECT * FROM employees WHERE ppAccountStatut = 0 ORDER BY idE ASC") or die(mysql_error());
			while($emp=mysql_fetch_array($groupQuery))
			{
				$idE = $emp['idE'];
				$upn = $emp['upn'];
				
				$filter  = "(userPrincipalName=$upn@ad.tbwagroup.be)"; // only enabled users
				$sr      = ldap_search($ldapconn, $dn,$filter);	
		
				$entries = ldap_get_entries($ldapconn,$sr);
				if ($entries["count"] == 0) 
				{
					echo "<strong>".$upn."</strong>";
					echo " not found in the AD. Moved in trash<br />"; // debug
					mysql_query("UPDATE employees SET ppAccountStatut = 1 WHERE idE = \"$idE\"") or die(mysql_error());
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
	echo $inserted." user(s) moved in users trash <br /></strong>";
	echo "</p>";
?>
