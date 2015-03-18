<!--
This script does actual ldap login to verify AD access
Extra stuff happens for domain admins

Included by:
signin.inc.php

Hrefs pointing here:

Requires:
bdd/connect.inc.php
groupsName.conf.php

Includes:
bdd/adConnect.inc.php
functions.php
inc/ADcheckIfAdmin.inc.php
loginForm.inc.php

Form actions:

-->

<?php
include ($_SERVER['DOCUMENT_ROOT']."/bdd/adConnect.inc.php");
include ($_SERVER['DOCUMENT_ROOT']."/functions.php");
			require ($_SERVER['DOCUMENT_ROOT']."/bdd/connect.inc.php");


		function accessLog ($statutNumber)
		{
						// access Log - bad login / password
							$ip = $_SERVER['REMOTE_ADDR'];
							$dateTime = date("d/m/Y H:i");
							$dateTimeTS = date("Y-m-d H:i");
							mysql_query("INSERT INTO accessLog (ip, dateTime, dateTimeTS, statut, userTried) VALUES (\"$ip\", \"$dateTime\", \"$dateTimeTS\", \"$statutNumber\", \"$_POST[username]\")") or die (mysql_error());
						// access LOG
						if ($statutNumber != 0 ) {
							echo "<h4 class='text-danger'><img src='img/noOk.png' /> Login failed. Check your login/password</h4>";
							include ("loginForm.inc.php"); 
						}
		}
						
						
						
						
$username1 = $_POST["username"];
$username = $username1."@ad.tbwagroup.be";
$password = $_POST["password"];

// Connecting to LDAP (port : 389)
$ldapconn = ldap_connect($server) or die ("Connection to the server failed");

if ($ldapconn)
{
    // Connecting to AD with specified login
	$ldapBind = ldap_bind($ldapconn, $username,$password) or die (accessLog(3));
    if ($ldapBind) 
	{
		echo "<h4 class='text-success'><img src='img/ok.png' /> Correctly authenticated on the AD. Checking if you can access here...</h4>";
		
		// checking for admin rights, not realy needed because next include defines $admin to be always TRUE
		include('inc/ADcheckIfAdmin.inc.php');
		if ($admin) { 
			$_SESSION['user'] = $username1;
			$_SESSION['password'] = $password;
			// creating session with first and lastname
			$queryUser = mysql_query("SELECT * FROM employees WHERE upn = \"$username1\"") or die(mysql_error());
			if (mysql_num_rows($queryUser) == 1)
			{
				$_SESSION['securityAccess'] = array(NULL);
				$_SESSION['securityGroup']= array(NULL);
				
				
				accessLog(0);
				// access LOG
				
				
				while($user=mysql_fetch_array($queryUser))
				{
					$idE = $user['idE'];
					$_SESSION['firstname'] = $user['firstname'];
					$_SESSION['lastname'] = $user['lastname'];
					$_SESSION['idE'] = $idE;
					// access level definition for the user
						$sessIdE = $_SESSION['idE'];
						$queryEmpGroupAccess = mysql_query("SELECT * FROM employeeGroup As eg INNER JOIN groups AS groups ON eg.idGroup = groups.idGroup WHERE eg.idE = $sessIdE") or die (mysql_error()) ;
						
					require ("groupsName.conf.php");
						$test = 0;
						while ($empAccess = mysql_fetch_array($queryEmpGroupAccess))
						{
							// add the groups to the session user to adapt his access on PP
								if ($empAccess['groupName'] == $pp_admin)
								{
									array_push($_SESSION['securityGroup'], $pp_admin); 
								}
								if ($empAccess['groupName'] == $pp_hr)
								{
									array_push($_SESSION['securityGroup'], $pp_hr); 
								}	
								if ($empAccess['groupName'] == $pp_finance)
								{
									array_push($_SESSION['securityGroup'], $pp_finance); 
								}
								if ($empAccess['groupName'] == $pp_planning)
								{
									array_push($_SESSION['securityGroup'], $pp_planning); // default TBWA user
								}	
								if ($empAccess['groupName'] == $pp_facebook)
								{
									array_push($_SESSION['securityGroup'], $pp_facebook); // default TBWA user
								}	
								if ($empAccess['groupName'] == $pp_building)
								{
									array_push($_SESSION['securityGroup'], $pp_building); // default TBWA user
								}	
								if ($empAccess['groupName'] == $pp_carfleet)
								{
									array_push($_SESSION['securityGroup'], $pp_carfleet); // default TBWA user
								}
								if ($empAccess['groupName'] == $na_all)
								{
									array_push($_SESSION['securityGroup'], $na_all); // default TBWA user
								}
								$test ++;
						}
						if ($test == 0)
						{
							accessLog(4);
							session_destroy();
							echo "<meta http-equiv='refresh' content='0;url=index.php'></h4><img src='img/ajax-loader.gif' />";
						}
						
						$ppLastConnection = date ("Y-m-d h:i:s");
						mysql_query("UPDATE employees SET ppLastConnection = \"$ppLastConnection\" WHERE idE = $idE");
					

					echo "<h4 class='text-success'><img src='img/ok.png' /> Welcome on People Portal, redirecting...";
					if (memberOfNAOnly($na_all) )
					{
						echo "<meta http-equiv='refresh' content='0;url=index.php?p=empList'></h4><img src='img/ajax-loader.gif' />";
					}
					else {
						echo "<meta http-equiv='refresh' content='0;url=index.php'></h4><img src='img/ajax-loader.gif' />";
					}
				} 
			}
			else 
			{
				accessLog(1);
				// access LOG
				echo "<h4 class='text-danger'><img src='img/noOk.png' /> You were not found or duplicate accounts in the People Portal database. Contact your favorites IT. Tell them to 'Make a manual sync'.</h4>";
				session_destroy();
			}
		}
		else { 
			accessLog(3);
			echo "<h4 class='text-danger'><img src='img/noOk.png' /> You can't access here.</h4>";
			session_destroy();
		}
		
	}

    
}

ldap_close ($ldapconn);
					
?>