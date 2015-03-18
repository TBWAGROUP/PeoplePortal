
<?php
	if (!isset($_GET['p'])) { require ($_SERVER['DOCUMENT_ROOT']."/bdd/connect.inc.php"); require ($_SERVER['DOCUMENT_ROOT']."/bdd/adConnect.inc.php");}
	if (!isset($_GET['p'])) {  }
	if (!isset($_GET['p'])) { require ($_SERVER['DOCUMENT_ROOT']."/functions.php"); }
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
					$queryEmp = mysql_query("SELECT * FROM employees WHERE ppAccountStatut = 0") or die (mysql_error());
					while ($userCheck= mysql_fetch_array($queryEmp))
					{
						$upn = $userCheck['upn'];
						$idE = $userCheck['idE'];
						
						$userDN = getDN($ldapconn, $upn, $dn);
						$groupQuery = mysql_query("SELECT * FROM groups ORDER BY groupName") or die(mysql_error());
						// echo $upn."<br />";
						while($group=mysql_fetch_array($groupQuery)) 
						{
							$groupName = $group['groupName'];
							$idGroup = $group['idGroup'];
							$groupNameDN  = "CN=$groupName,OU=Groups,$dn";
							//echo "checking for ".$userDN;
							
							// if member in the AD, add him to the group on the PP DB
							if (!checkGroup($ldapconn, $userDN, $groupNameDN) ) // check if the user is on the group
							{
								$checkGroupPP = mysql_query("SELECT * FROM employeeGroup WHERE idE = $idE AND idGroup = $idGroup") or die (mysql_error());
								if (mysql_num_rows($checkGroupPP) == 0)
								{
									mysql_query("INSERT INTO employeeGroup (idE, idGroup) VALUES($idE, $idGroup)") or die (mysql_error());
									echo $upn." added in ".$groupName."<br />";
									$inserted++;
								}
							}
							else
							{
								$checkGroupPP = mysql_query("SELECT * FROM employeeGroup WHERE idE = $idE AND idGroup = $idGroup") or die (mysql_error());
								if (mysql_num_rows($checkGroupPP) == 1)
								{
									mysql_query("DELETE FROM employeeGroup WHERE idE=$idE AND idGroup = $idGroup") or die (mysql_error());
									echo $upn." deleted from ".$groupName."<br />";
									$updated++;
								}
							}
						}
					}
				}
				else 
				{
					echo "LDAP bind failed...";
				}
				
			
		}

		ldap_close ($ldapconn);


	
	echo "<p>";
	echo $inserted." user / group association <br /></strong>";
	echo "</p>";
?>
