<h3>Active Directory search - Disabled accounts</h3>

<form method="GET" action="index.php" name="search">
	<input type="text" name="name" size=50/>
	<input type="hidden" name="p" value="showDisAcc" />
</form>

<p><br /><a href="index.php?p=viewAD" class="btn btn-primary"/>Go Back</a></p>

<?php
// Connecting to LDAP (port : 389)
$ldapconn = ldap_connect($server) or die("Could not connect to dc");
if ($ldapconn) {
    // binding anonymously
    $ldapbind = ldap_bind($ldapconn, $user, $pass);

    if ($ldapbind)
	{
		$dn      = $dnServer;

		// Checking search form
		if (!isset ($_GET['name']))
		{
			$filter  = "(&(objectCategory=user)(objectClass=User)(userAccountControl:1.2.840.113556.1.4.803:=2))";//disabled account
		}
		else 
		{
			$search=$_GET['name'];
			if ($search=="") { echo "The search field is empty"; $filter ="(sn=)"; }
			else {
				// if a search is done
				$filter  = "(|(name=*$search*)(sn=*$search*))"; // first and lastname check
			}
		}
		$attr    = array ('givenName', 'sn', 'userPrincipalName');
		$sr      = ldap_search($ldapconn, $dn, $filter, $attr);
		$entries = ldap_get_entries($ldapconn,$sr);
		
		// X entry/entries :
		if ($entries["count"] == 1) { echo "<h4>".$entries["count"]." disabled account found :</h4>"; }
		else { echo "<h4>".$entries["count"]." disabled accounts found :</h4>"; }

		echo "<div class='scroll'>";
		echo "
		<table class='table table-condensed'>
			<th><span class='caret'></span></th>
			<th>User name</th>
			<th>OU</th>
		";
		
		foreach($entries as $entry)
		{
			$cn=ldap_explode_dn($entry["dn"], 1); 
			
			// bypass error "Undefined offset: 0" by testing the array fields before print them
			if (isset($cn[0])) 
			{ 
				//echo $cn[0]; // array content
				$computer = $cn[0]; // convert the array on a string
				
				// creating UPN by using the first and lastname
				$upn = str_replace(" ",".", $computer);// replace the " " by "."
				$upn = str_replace("'","", $upn);// replace the "'" by ""
				$upn = strtolower($upn); // lowercase
				
				echo "
					<tr class='hover'>
						<td>
							<a href='index.php?p=manAcc&upn=$upn' ><img src='img/user.png' alt='In AD' title='Computer present in AD'/></a>
						</td>
						<td>
							<a href='index.php?p=manAcc&upn=$upn' >$computer</a>
						</td>
						<td class='ouTab'>
						";
						showOU (10, $cn); // show OU untils 5 levels
				echo "		
						</td>
					</tr>";
			}
		}
		echo "</table>";
		echo "</div>";
	}
	else {
        echo "LDAP bind failed...";
    }
}

ldap_close ($ldapconn);

?>