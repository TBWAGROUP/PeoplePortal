<!--
This script may be not in use, no included by found.

Included by:

Hrefs pointing here:

Requires:

Includes:

Form actions:

-->

<?php

if (!isset($_GET['upn']) || ($_GET['upn'])=="") {	echo "<h1 class='text-danger'>Invalid UPN.</h1>"; }
else  {
	$upn = $_GET['upn'];
	$id = $_GET['id'];
	
	echo "<h3>$upn@ad.tbwagroup.be</h3>";

	// Connecting to LDAP (port : 389)
	$ldapconn = ldap_connect("10.173.0.1") or die("Could not connect to dc");
	if ($ldapconn) {
		// binding anonymously
		$ldapbind = ldap_bind($ldapconn, $user, $pass);


		if ($ldapbind)
		{
			//**********************************************************************************************
			//**********************************************************************************************
			// LDAP QUERIES
		
			$dn      = "dc=ad,dc=tbwagroup,dc=be";
			
			
			// checking upn
			$filter  = "(userPrincipalName=$upn@ad.tbwagroup.be)"; // first and lastname check
			$sr      = ldap_search($ldapconn, $dn, $filter);
			$entries = ldap_get_entries($ldapconn,$sr);
			
			
			// checking if the upn is on AD
			if ($entries["count"] == 0) 
			{ 
				echo "<h1 class='text-success'><img src='img/ok.png' /> User not found in AD</h1>"; 
				echo "<hr />";
				echo "<p><a class='btn btn-success' href='index.php?p=showEmp&id=$id&addToAD=1'><img src='img/editWht.png' /> Add this user on the AD</a></p>"; 
				echo "<p><a class='btn btn-info' href='index.php?p=showEmp&id=$id'><img src='img/noOkWht.png' /> Go back</a></p>"; 
			}
			else 
			{				
				echo "<h1 class='text-danger'><img src='img/noOk.png' /> This user was found in the AD.</h1>";
				echo "<hr />";
				echo "
				<table class='table table-condensed'>
				";
				
				foreach($entries as $entry)
				{
					$cn=ldap_explode_dn($entry["dn"], 1); 
					
					// bypass error "Undefined offset: 0" by testing the array fields before print them
					if (isset($cn[0])) 
					{ 
						//echo $cn[0]; // array content
						$computer = $cn[0]; // convert the array on a string
						echo "
							<tr class='hover'>
								<td>
									<a href='index.php?p=showEmp&id=$id' >$computer</a>
								</td>
								<td class='ouTab'>";
									showOU (10, $cn);
						echo "</td>
							</tr>";
					}
				}
				echo "</table>";
				echo "<br /><br /><br /><br /><a class='btn btn-primary' href='index.php?p=showEmp&id=$id&addToAD=1'><img src='img/editWht.png' /> Even add this user on the AD</a></p>"; 
				echo "<p><a class='btn btn-info' href='index.php?p=showEmp&id=$id'><img src='img/noOkWht.png' /> Go back</a>"; 

			}
		}
		else {
			echo "LDAP bind failed...";
		}
	}

	ldap_close ($ldapconn);

} // END IF isset $_GET['upn'];
?>