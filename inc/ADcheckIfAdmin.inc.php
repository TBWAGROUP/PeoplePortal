<!--
This script does actual ldap login to verify AD access

Included by:
login.inc.php

Hrefs pointing here:

Requires:

Includes:

Form actions:

-->

<?php
			//**********************************************************************************************
			//**********************************************************************************************
			// LDAP QUERIES
			/**
			$dn      = $dnServer;
			
			// checking upn
			$filter  = "(&(userPrincipalName=$username)(memberOf=CN=Domain Admins,CN=Users, $dn))"; // check for admins right
			$sr      = ldap_search($ldapconn, $dn, $filter);
			$entries = ldap_get_entries($ldapconn,$sr);
			
			if ($entries["count"] == 1) 
			{ 
				$admin = TRUE;
			}
			else 
			{				
				$admin = FALSE;
			}
			**/
			
			
			
			// All AD users can connect. PP define the access level just after
			$admin = TRUE;
			
?>