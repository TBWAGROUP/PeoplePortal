<!--
This script lists new user requests, for PP HR people

Included by:

Hrefs pointing here:

Requires:

Includes:
inc/showUsfProcess.inc.php

Form actions:

-->

<?php


$queryEmpValidation = mysql_query("
				SELECT * FROM contracts AS cont 
				LEFT JOIN employees AS emp ON emp.idE = cont.idE
				INNER JOIN labels AS lab ON lab.idLab = cont.idLab
				WHERE cont.validationStage = 1
				ORDER BY cont.startDateTS ASC
				") or die(mysql_error());




echo "<h3 class='text-success'>Waiting for HR approval</h3>";


echo "<table class='table table-condensed'>";
echo "<tr class='active'>";
		echo "<th>Name</th>";
		echo "<th>Requested by</th>";
		echo "<th>Request date</th>";
		echo "<th>Label</th>";
		echo "<th>Start date</th>";
		echo "<th>Type</th>";
echo "</tr>";
$toActivate = 0;
			while($empValidation=mysql_fetch_array($queryEmpValidation))
			{
				echo "<tr class='hover'>";
					echo "<td><a href='index.php?p=emp&idE=".$empValidation['idE']."&idCon=".$empValidation['idCon']."&upn=".$empValidation['upn']."&objectsid=".$empValidation['objectsid']."' >".$empValidation['firstname']." ".$empValidation['lastname']."</a></td>";
					
					// find the requestor infomrations
					$requestorIdE = $empValidation['requestor'];
					$queryRequestor = mysql_query("SELECT * FROM employees WHERE idE = \"$requestorIdE\"") or die(mysql_error());
					$requestor = mysql_fetch_array($queryRequestor);
					echo "<td>".$requestor['firstname']." ".$requestor['lastname']."</td>";
					
					echo "<td>".$empValidation['ppConAddDate']."</td>";
					echo "<td>".$empValidation['labelName']."</td>";
					echo "<td>".$empValidation['startDate']."</td>";
					echo "<td>".$empValidation['empType']."</td>";
				echo "</tr>";
			}
echo "</table>";


?>
<hr />

<h4>All contracts currently in approval or provisioning stage</h4>
<?php
	$source = "WHERE cont.validationStage != 0 AND cont.validationStage != 4031 AND cont.validationStage != 4 ";
	$cancelBtn = 0;
	include ("inc/showUsfProcess.inc.php");
?>

