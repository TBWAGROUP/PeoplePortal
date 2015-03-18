<?php
	require ("bdd/connect.inc.php");
	require ("bdd/adConnect.inc.php");
	require ("functions.php");
	require ("groupsName.conf.php");

	
	
$queryEmailHR = mysql_query ("
							SELECT * FROM employees AS emp
							INNER JOIN employeeGroup AS empGroup ON empGroup.idE = emp.idE
							INNER JOIN groups AS groups ON groups.idGroup = empGroup.idGroup
							INNER JOIN contracts AS cont ON emp.idE = cont.idE
							WHERE groups.groupName = \"$pp_hr\"
																") or die (mysql_error());
																
$queryEmailProvisioningAll = mysql_query ("
										SELECT * FROM employees AS emp
										INNER JOIN employeeGroup AS empGroup ON empGroup.idE = emp.idE
										INNER JOIN groups AS groups ON groups.idGroup = empGroup.idGroup
										INNER JOIN contracts AS cont ON emp.idE = cont.idE
										WHERE groups.groupName = \"$pp_finance\" 
											OR groups.groupName = \"$pp_building\" 
											OR groups.groupName = \"$pp_facebook\" 
											OR groups.groupName = \"$pp_carfleet\" 
											OR groups.groupName = \"$pp_admin\"
										") or die (mysql_error());
										// WHERE groups.groupName LIKE '%PP %' AND (groups.groupName != \"$pp_hr\" OR groups.groupName != \"$pp_planning\")

echo "<h1>HR :</h1>";
while ($emailHR = mysql_fetch_array($queryEmailHR))
{
	$mailto = $emailHR["primaryEmail"]." (".$emailHR["firstname"]." ".$emailHR["lastname"].")";
	echo "hr mail sended to : ".$mailto."<br />";
}

echo "<h1>All provisioning :</h1>";
while ($emailProv = mysql_fetch_array($queryEmailProvisioningAll))
{
	$mailto = $emailProv["primaryEmail"]." (".$emailProv["firstname"]." ".$emailProv["lastname"].")";
	echo "Prov mail sended to : ".$mailto."<br />";
}


?>