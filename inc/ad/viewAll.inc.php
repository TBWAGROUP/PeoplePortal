<h3><img src="img/windows2012S.png" /> Active Directory live search</h3>

<form method="GET" action="index.php" name="search">
	<input type="text" name="name" size=50 value="<?php if (isset ($_GET['name'])) { echo $_GET['name']; } ?>" autofocus />
	<input type="hidden" name="p" value="viewAD" />
</form>



<?php
// Checking search form
if (!isset ($_GET['name']))
{
	echo "<br /><p class='text-info'>Enter a firstame, lastname or both in the search form to begin the user search</p>";
	echo "<p><br /><a href='index.php?p=showDisAcc' class='btn btn-primary'/>Disabled accounts</a></p>";

}
else 
{
	echo "<p><br /><a href='index.php?p=showDisAcc' class='btn btn-primary'/>Disabled accounts</a></p>";

	// Connecting to LDAP (port : 389)
	$ldapconn = ldap_connect($server) or die("Could not connect to dc");
	if ($ldapconn) {
		// binding anonymously
		$ldapbind = ldap_bind($ldapconn, $user, $pass);

		if ($ldapbind)
		{
			$dn      = $dnServer;

				$search=$_GET['name'];
				if ($search=="") { echo "The search field is empty"; $filter ="(sn=)"; }
				else {
					// if a search is done
					$filter  = "(|(name=*$search*)(sn=*$search*))"; // first and lastname check
				}
			
			$attr    = array ('objectsid', 'givenName', 'sn', 'userAccountControl');
			$sr      = ldap_search($ldapconn, $dn, $filter, $attr);
			$entries = ldap_get_entries($ldapconn,$sr);
			
			// X entry/entries :
			if ($entries["count"] == 1) { echo "<h4>".$entries["count"]." entry :</h4>"; }
			else { echo "<h4>".$entries["count"]." entries :</h4>"; }
			
			echo "<div class='scroll'>";
			echo "
			<table class='table table-condensed'>
				<th width='50px'><span class='caret'></span></th>
				<th width='400px'>User name</th>
				<th>OU</th>
				<th>UID</th>
			";
			
			
			foreach($entries as $entry)
			{
				$cn=ldap_explode_dn($entry["dn"], 1); 
				
				// bypass error "Undefined offset: 0" by testing the array fields before print them
				if (isset($cn[0])) 
				{ 
					//echo $cn[0]; // array content
					$computer = $cn[0]; // convert the array on a string
					$uid = $entry['objectsid'][0];
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
						showOU (10, $cn); // show OU untils 10 levels
					echo "
							</td>
							<td>
								".bin_to_str_sid($uid)."
							</td>
						</tr>
					";
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

} //END IF else isset ($_GET['name'])
?>