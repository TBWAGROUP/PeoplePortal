<!--
This script adds a user to AD and sets some AD fields

Included by:
inc/userStartForm/provisioning/it.fields.inc.php

Hrefs pointing here:

Requires:

Includes:

Form actions:

-->

<?php


// Connecting to LDAP (port : 389)
$ldapconn = ldap_connect($server) or die("Could not connect to dc");
if ($ldapconn) {
    // binding anonymously
    $ldapbind = ldap_bind($ldapconn, $user, $pass);

    if ($ldapbind)
	{
		$upn = $contract["upn"];
		$upnFull = $contract["upn"]."@ad.tbwagroup.be";
		$dn      = "CN=$cn,$fullCn,OU=Employees,$dnServer";

		// add entry to AD
		$info["objectClass"] = "User";
		$info["ou"] = "Employees";
		
		$info["cn"] = $cn;
		$info["givenName"] = accentAD($contract["firstname"]);
		$info["sn"] = accentAD($contract["lastname"]);
		$info["displayName"] =  accentAD($contract["firstname"])." ". accentAD($contract["lastname"]);
		$info['sAMAccountName'] = genSAMAD($contract["upn"]);
		
		
		if ($contract["primaryEmail"] !="") { $info["mail"]=$contract["primaryEmail"]; } else { $info["mail"] =" "; }
		if ($contract["phoneNumber"] !="") { $info["telephoneNumber"] = $contract["phoneNumber"]; } else { $info["telephoneNumber"] = " "; }
		if ($contract["mobile"] !="") { $info["mobile"] = $contract["mobile"]; } else { $info["mobile"] = " "; }
				
		if ($contract["functionName"] !="") { $info["title"]=$contract["functionName"]; } else { $info["title"] =" "; }
		if ($contract["nameDepartment"] !="") { $info["department"]=$contract["nameDepartment"]; } else { $info["department"] =" "; }
		$info["company"] = $contract["companyCode"];
		
		/****/
		$info["streetAddress"] = "165 Kroonlaan / Ave de la Couronne";
		$info["l"] = "Brussels";
		$info["st"] = "Brussels";
		$info["postalCode"] = "1050";
		
		// Belgium ISO 3166 country code. Full list available here : http://userpage.chemie.fu-berlin.de/diverse/doc/ISO_3166.html
		$info["countryCode"] = 056;
		$info["c"] = "BE";
		$info["co"] = "BEL";

		// Define cleartext password
		// $clearTextPass = 'Tbwagroup!';
		//
		// Create Unicode password
		// $constructHashPass = "\"" . $clearTextPass . "\"";
		// $len = strlen($constructHashPass);
		// $hashPass = "";
		// for($i=0;$i<$len;$i++) {
		//	$hashPass .= "{$constructHashPass{$i}}\000";
		// }
		// $info["unicodepwd"] =  $hashPass;
		// OR REPLACE ABOVE LINES BY THIS SINGLE ONE $info['userPassword'] =  "{MD5}".base64_encode(pack("H*",md5($clearTextPass)));
		$info["UserAccountControl"] = 544; // activate account (544 no pass required, 512 pass required, but doesn't work)
		$info["userPrincipalName"] = $upnFull;
		
		//print_r($info);
		if ($sr = ldap_add($ldapconn, $dn, $info) )
		{ 
				// updating objectsid
				$filter  = "(userPrincipalName=$upnFull)"; // only enabled users
				$sr      = ldap_search($ldapconn, $dn, $filter);
				$entry = ldap_get_entries($ldapconn, $sr);
				$count = $entry["count"];
				for ($x=0; $x < $count; $x++)
				{
					if (isset($entry[$x])) 
					{
						$objectsid = bin_to_str_sid($entry[$x]["objectsid"][0]);
						// associate the uid to the last user added (idE DESC) with this UPN:
						mysql_query("UPDATE employees SET objectsid = \"$objectsid\" WHERE upn = \"$contract[upn]\" ORDER BY idE DESC") or die(mysql_error());
						echo "<br />User ".$upn." correctly added with SID : ".$objectsid;
					}
				}
				// objectsid UP TO DATE
				
			echo "<h5 class='text-success'><img src='img/compOk.png' /> User ".$upn." successfully created in Active Directory. </h3><br ><h4 class='text-warning'>Don't forget to reset the password or the account will not be active. <a class=\"btn btn-default btn-xs\" href=\"index.php\" >Ok</a></h5>";
			echo "<meta http-equiv='refresh' content='3;url=index.php?p=emp&idE=$idE&idCon=$idCon&upn=$upn&objectsid=$objectsid'></h4><img src='img/ajax-loader.gif' />";
			$correctlyAdded = TRUE;
			}
		}
		else { 
			echo "<h3 class='text-danger'>Error while creating the user ".$upn." in Active Directory. <a class=\"btn btn-default btn-xs\" href=\"index.php\" >Ok</a>;<br ></h3>"; 
			$correctlyAdded = FALSE;
		}
		
		
	}
	else {
        echo "LDAP bind failed...";
		$correctlyAdded = FALSE;
    }


ldap_close ($ldapconn);




?>
