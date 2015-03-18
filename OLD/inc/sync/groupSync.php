<?php
	if (!isset($_GET['p'])) { require ($_SERVER['DOCUMENT_ROOT']."/bdd/connect.inc.php");require ($_SERVER['DOCUMENT_ROOT']."/bdd/adConnect.inc.php"); }

		if (!isset($_GET['upn'])) {
		
	$updated=0;
	$inserted=0;
// Groups NA & JR
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

		$filterNA  = "(| (| (| (| (name=PP *) (name=NA All) (name=MD *) (name=JR *) ) ) ) )";
		
		$sr      = ldap_search($ldapconn, $dn, $filterNA);
		$entries = ldap_get_entries($ldapconn,$sr);
		foreach($entries as $entry)
		{
			$cn=ldap_explode_dn($entry["dn"], 1); 
			if (isset($cn[0]))
			{
				$name = $cn[0];
				echo $name.", ";
				//$name = genUpnCN ($name);
				//$ou = $cn[1];
				//print_r($entry);
				$query = mysql_query("SELECT * FROM groups WHERE groupName = \"$name\" ") or die(mysql_error());
				$queryNbr = mysql_num_rows($query);
				//echo $query['nbr'];
				if ($queryNbr == 0)
				{
					//echo $cn[0];
					//echo " insert <br />"; // debug
					mysql_query("INSERT INTO groups (groupName) VALUES (\"$cn[0]\")") or die(mysql_error());
					$inserted++;
				}	
			}
		}
	}
	else 
	{
		echo "LDAP bind failed...";
	}

ldap_close ($ldapconn);
}
	echo "<p>";
	echo $inserted." group(s) inserted <br /></strong>";
	echo "</p>";
	
	// delete group from PP DB not present in AD
	include ("groupsDB_RemoveSync.php");
	
	// don't include empGroup if the sync is launch from the global sync (already launched from adUserSync)
	if (isset($_GET['p']))
	{
		if ($_GET['p'] != "globalSync")
		{
			// empGroup sync (this user member of this group)
			include ($_SERVER['DOCUMENT_ROOT']."/inc/sync/syncEmployeeGroupMembership.php");
		}
	}
	
	
// *******************************************
// specific user sync	
}
else
{
	include ($_SERVER['DOCUMENT_ROOT']."/inc/sync/specificGroupSync.inc.php");
}
	
	
?>
