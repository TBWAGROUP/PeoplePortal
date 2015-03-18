<!--
This script lists new users for maconomy info, for PP finance people

Included by:

Hrefs pointing here:

Requires:

Includes:

Form actions:

-->

<?php
// display all users who need an access in maconomy and not created yet
$queryEmpValidation = mysql_query("
				SELECT * FROM contracts AS cont
				LEFT JOIN employees AS emp ON emp.idE = cont.idE
				INNER JOIN labels AS lab ON lab.idLab = cont.idLab
					INNER JOIN functions AS fun ON fun.idFunc = cont.idFunc
					LEFT JOIN teamLeads AS lead ON lead.contracts_idCon = cont.idCon
				WHERE cont.createdInMaconomy = 0 AND cont.maconomy = 1 AND (lead.appType = 0 OR lead.appType IS NULL)
					ORDER BY cont.startDateTS, cont.empType, fun.functionName, emp.firstname
				") or die(mysql_error());
				
				


echo "<h3 class='text-success'>Waiting for creation in Maconomy</h3>";



echo "<table class='table table-condensed'>";
echo "<tr class='active'>";
		echo "<th>Timesheet</th>";
		echo "<th>Blocking</th>";
		echo "<th>Other</th>";
	echo "<th>Name</th>";
	echo "<th>Type</th>";
	echo "<th>Function</th>";
	echo "<th>Requested by</th>";
	echo "<th>Manager</th>";
	echo "<th>Request date</th>";
	echo "<th>Start date</th>";
echo "</tr>";
$toActivate = 0;
			while($empValidation=mysql_fetch_array($queryEmpValidation))
			{
				$queryGroupId = mysql_query("SELECT idGroup FROM groups WHERE groupName = \"$pp_finance\"") or die (mysql_error());
				$idGroup = array_shift(mysql_fetch_array($queryGroupId));
				$queryGroupStatut = mysql_query("SELECT * FROM approvals WHERE idGroup = $idGroup AND idCon = $empValidation[idCon] ") or die (mysql_error());
				$checkApprov = mysql_num_rows($queryGroupStatut );
				if ($checkApprov == 0) 
					$statut = "noOk";
				else 
				{
					$checkStatut = mysql_fetch_array($queryGroupStatut);
					if ($checkStatut)					
						$statut = "ok";
					else
						$statut = "noOk";
				}
				
				if ($empValidation['timesheetblocking'] == TRUE ) { $setBlocking = "ok"; } else {  $setBlocking = "noOk"; }
				if ($empValidation['timesheets'] == TRUE ) { $timesheet = "ok"; } else {  $timesheet = "noOk"; }
				if ($empValidation['maconomy'] == TRUE ) { $maco = "ok"; } else {  $maco = "noOk"; }
				if ( 
						($empValidation['financeJobCost']) || ($empValidation['financeAccountsPayable']) || ($empValidation['financeAccountReceivable']) 
						|| ($empValidation['financeFixedAssets']) || ($empValidation['financePurchaseOrders']) || ($empValidation['financeInvoicing']) 
						|| ($empValidation['financeGeneralLedger']) || ($empValidation['financeHR']) 
					) { $other = "ok"; } else {  $other = "notNeeded"; }
				
				
				$idCon = $empValidation['idCon'];
				$queryPark = mysql_query("SELECT * FROM parking WHERE contracts_idCon = $idCon") or die (mysql_error());
				$parking = mysql_fetch_array($queryPark);

				// find the requestor infomrations
				$requestorIdE = $empValidation['requestor'];
				$queryRequestor = mysql_query("SELECT * FROM employees WHERE idE = \"$requestorIdE\"") or die(mysql_error());
				$requestor = mysql_fetch_array($queryRequestor);

				// find the manager infomrations
				$managerIdE = $empValidation['employees_idE'];
				$queryManager = mysql_query("SELECT * FROM employees WHERE idE = \"$managerIdE\"") or die(mysql_error());
				$manager = mysql_fetch_array($queryManager);

				echo "<tr class='hover'>";
					echo "<td><img src='img/".$timesheet."S.png' /></td>";
					echo "<td><img src='img/".$setBlocking."S.png' /></td>";
					echo "<td><img src='img/".$other."S.png' /></td>";
					echo "<td><a href='index.php?p=emp&tab=3&idE=".$empValidation['idE']."&idCon=".$empValidation['idCon']."&activation&upn=".$empValidation['upn']."&objectsid=".$empValidation['objectsid']."&tab=2' >".$empValidation['firstname']." ".$empValidation['lastname']."</a></td>";
				echo "<td>" . $empValidation['empType'] . "</td>";
				echo "<td>" . $empValidation['functionName'] . "</td>";
				echo "<td>" . $requestor['firstname'] . " " . $requestor['lastname'] . "</td>";
				echo "<td>" . $manager['firstname'] . " " . $manager['lastname'] . "</td>";
				echo "<td>" . $empValidation['ppConAddDate'] . "</td>";
				echo "<td>" . $empValidation['startDate'] . "</td>";
				echo "</tr>";
			}
echo "</table>";
?>
