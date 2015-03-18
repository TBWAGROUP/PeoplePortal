<?php
	if (!isset($_GET['p'])) { require ($_SERVER['DOCUMENT_ROOT']."/bdd/connect.inc.php");  require ($_SERVER['DOCUMENT_ROOT']."/bdd/adConnect.inc.php"); }
	$upn = $_GET['upn'];

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
			
				$filter  = "(userPrincipalName=$upn@ad.tbwagroup.be)"; // only enabled users
				$sr      = ldap_search($ldapconn, $dn,$filter);	
		
				$entry = ldap_get_entries($ldapconn, $sr);
				$count = $entry["count"];
				echo $count. " entries found : <br />";
				for ($x=0; $x <= $count; $x++)
				{
					if (!empty($entry[$x]["mail"][0])) { $primaryEmail = $entry[$x]["mail"][0]; }
					if (!empty($entry[$x]["givenname"][0])) { $firstname = $entry[$x]["givenname"][0]; }
					if (!empty($entry[$x]["sn"][0])) {$lastname = $entry[$x]["sn"][0]; $upn =  genUPN($firstname,$lastname); }
					if (!empty($entry[$x]["streetAddress"][0])) { $address = $entry[$x]["streetAddress"][0]; }
					if (!empty($entry[$x]["mobile"][0])) { $mobile = $entry[$x]["mobile"][0]; } else { $mobile = ""; }
					if (!empty($entry[$x]["telephoneNumber"][0])) { $phoneNumber = $entry[$x]["telephoneNumber"][0]; }
					$ppAccountStatut = 2; // frozen account
					// echo $firstname;
					// echo $upn;
					// echo "<br />";
					$query = mysql_query("SELECT * FROM employees WHERE upn = \"$upn\" ") or die(mysql_error());
					$emp = mysql_fetch_array ($query);
					
					$queryNbr = mysql_num_rows($query);
					
					if (mysql_num_rows($query) == 1)
					{
						if ($emp['ppAccountStatut'] == 1) // if a trashed user is found, move him to frozen user
						{
							mysql_query("UPDATE employees SET mobile = \"$mobile\", ppAccountStatut = 2 WHERE upn = \"$upn\"") or die(mysql_error());
							$updated++;
						}
						else // else, dont chaneg his statut but only his informations
						{
							mysql_query("UPDATE employees SET mobile = \"$mobile\" WHERE upn = \"$upn\"") or die(mysql_error());
							$updated++;
						}
					}
					else
					{
						$ppAddDate = date("Y-m-d H:i:s");
						echo "<strong>".$upn." inserted</strong><br />";
						echo " inserted and added in frozen list<br />"; // debug
						mysql_query("INSERT INTO employees (firstname, lastname, upn, mobile, ppAccountStatut, ppAddDate) VALUES (\"$firstname\", \"$lastname\", \"$upn\", \"$mobile\", \"$ppAccountStatut\", \"$ppAddDate\")") or die(mysql_error());
							$queryIdE = mysql_query("SELECT idE FROM employees WHERE idE =  LAST_INSERT_ID()") or die(mysql_error());
							$idE = array_shift(mysql_fetch_array($queryIdE));
							// echo $idE;
						mysql_query("INSERT INTO contracts (idE, idFunc, idDep, idLab, validationStage) VALUES (\"$idE\", 74, 18, 22,\"0\")") or die(mysql_error());
							$queryIdcon = mysql_query("SELECT idCon FROM contracts WHERE idCon =  LAST_INSERT_ID()") or die(mysql_error());
							$idCon = array_shift(mysql_fetch_array($queryIdcon));
						mysql_query("UPDATE employees SET contract = \"$idCon\" WHERE idE = \"$idE\"") or die(mysql_error());
						$inserted++;
					}	
				}
			}
		
		else {
			echo "LDAP bind failed...";
		}

		ldap_close ($ldapconn);

	} // ldapconn
	echo "<p>";
	echo $updated." user(s) updated <br />";
	echo $inserted." user(s) inserted <br /></strong>";
	echo "</p>";
	
	
	
	// clean people db from unexisting AD accout present in the pp db
	include ($_SERVER['DOCUMENT_ROOT']."/inc/sync/syncUsersNotInADRemoveFromDB.php");
	
	// empGroup sync (this user member of this group)
	include ($_SERVER['DOCUMENT_ROOT']."/inc/sync/syncEmployeeGroupMembership.php");
	
?>
