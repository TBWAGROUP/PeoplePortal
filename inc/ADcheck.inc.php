<!--
This script checks if a user (upn actually) already exists on AD

Included by:
index.php
inc/userStartForm/provisioning/it.fields.php

Hrefs pointing here:

Requires:

Includes:

Form actions:

-->

<?php

		$sam = $contract["upn"];
		$upnFull = $contract["upn"]."@ad.tbwagroup.be";
		
/**
LDAP OVER SSL for changing password
NOT WORK (need to configure the certificate on the server)
**/
$dn      = "CN=$cn,$fullCn";

	// Connecting to LDAP (port : 389)
$ldapconn = ldap_connect($server) or die("Could not connect to dc");
if ($ldapconn) {
    // binding anonymously
    $ldapbind = ldap_bind($ldapconn, $user, $pass);

    if ($ldapbind)
	{
			//**********************************************************************************************
			//**********************************************************************************************
			// LDAP QUERIES
		
			$dn      = $dnServer;

			// checking upn
			// $filter  = "(CN=$cn,$fullCn)"; // first and lastname check)"; // first and lastname check
			$filter  = "(userPrincipalName=$upnFull)"; // first and lastname check
			$sr      = ldap_search($ldapconn, $dn, $filter);
			$entries = ldap_get_entries($ldapconn,$sr);
			
			// checking if the upn is on AD
			if ($entries["count"] == 0) 
			{ 
				$addingAD = TRUE;
			}
			else 
			{				
				echo "<h5 class='text-info'><img src='img/compOk.png' /> User found in Active Directory, IT information correctly updated on People Portal <a class=\"btn btn-default btn-xs\" href=\"index.php\" >Ok</a></h5>";
				$addingAD = FALSE;
				$correctlyAdded = TRUE;
			}
		}
		else {
			echo "LDAP bind failed...";
			$addingAD = FALSE;
			$correctlyAdded = FALSE;
		}
	}

	ldap_close ($ldapconn);

?>
