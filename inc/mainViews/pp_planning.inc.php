<!--
This script shows new users for google calendar, not very useful at this time.

Included by:

Hrefs pointing here:

Requires:

Includes:
inc/showUsfProcess.inc.php

Form actions:

-->

<?php


/*
	$queryEmpValidation = mysql_query("
				SELECT * FROM contracts AS cont
				LEFT JOIN employees AS emp
				ON emp.idE = cont.idE
				INNER JOIN labels AS lab ON lab.idLab = cont.idLab
				WHERE cont.validationStage = 2
				ORDER BY startDateTS ASC
				") or die(mysql_error());
 */

	$queryEmpValidation = mysql_query("
SELECT * FROM contracts AS cont
				LEFT JOIN employees AS emp ON emp.idE = cont.idE
				INNER JOIN labels AS lab ON lab.idLab = cont.idLab
				WHERE (ppAccountStatut = 0 OR ppAccountStatut = 2) AND (endDateTS > CURRENT_DATE OR endDateTS = 0 OR endDateTS IS NULL) AND ( cont.empType = 'intern' OR cont.empType = 'freelance' OR cont.empType = 'employee temporary' )
				ORDER BY endDateTS asc, firstname asc, lastname ASC
") or die(mysql_error());


echo "<h3 class='text-success'>Planning info for current Interns and Freelancers</h3>";


echo "<table class='table table-condensed'>";
echo "<tr class='active'>";
		echo "<th>gCalendar</th>";
		echo "<th>Name</th>";
		echo "<th>Requested by</th>";
		echo "<th>Request date</th>";
		echo "<th>Start date</th>";
		echo "<th>End date</th>";
	echo "<th>Type</th>";
	echo "<th>Label</th>";
echo "</tr>";
$toActivate = 0;
			while($empValidation=mysql_fetch_array($queryEmpValidation))
			{
				$queryGroupId = mysql_query("SELECT idGroup FROM groups WHERE groupName = \"$pp_planning\"") or die (mysql_error());
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
				
				// if ($empValidation['setBlocking'] == TRUE ) { $setBlocking = "Yes"; } else {  $setBlocking = "No"; }
				
				
				$idCon = $empValidation['idCon'];
				$queryPark = mysql_query("SELECT * FROM parking WHERE contracts_idCon = $idCon") or die (mysql_error());
				$parking = mysql_fetch_array($queryPark);
				
				
				echo "<tr class='hover'>";
					echo "<td><img src='img/".$statut."S.png' /></td>";
					echo "<td><a href='index.php?p=emp&tab=3&idE=".$empValidation['idE']."&idCon=".$empValidation['idCon']."&activation&upn=".$empValidation['upn']."&objectsid=".$empValidation['objectsid']."&tab=2' >".$empValidation['firstname']." ".$empValidation['lastname']."</a></td>";
					
					// find the requestor infomrations
					$requestorIdE = $empValidation['requestor'];
					$queryRequestor = mysql_query("SELECT * FROM employees WHERE idE = \"$requestorIdE\"") or die(mysql_error());
					$requestor = mysql_fetch_array($queryRequestor);
					echo "<td>".$requestor['firstname']." ".$requestor['lastname']."</td>";
					echo "<td>".$empValidation['ppConAddDate']."</td>";
					echo "<td>".$empValidation['startDate']."</td>";
					echo "<td>".$empValidation['endDate']."</td>";
					echo "<td>".$empValidation['empType']."</td>";
				echo "<td>".$empValidation['labelName']."</td>";
				echo "</tr>";
			}
echo "</table>";
?>



 <div>
<?php
// Your requests waiting for approval LIST;

$source = "WHERE cont.requestor = $currentIdE AND cont.validationStage != 0 AND cont.validationStage != 4";
echo "<div style=\"width:700px;\" >";
	$cancelBtn=1;
	include ("inc/showUsfProcess.inc.php");
echo "</div>";
 ?>
 </div>
