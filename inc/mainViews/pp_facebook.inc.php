<!--
This script lists new users for facebook info, for PP facebook people

Included by:

Hrefs pointing here:

Requires:

Includes:

Form actions:

-->

<h2>Active users with unverified FaceBook status</h2>
<hr />
<?php


	$queryEmpValidation = mysql_query("
				SELECT * FROM contracts AS cont
					LEFT JOIN employees AS emp ON emp.idE = cont.idE
					INNER JOIN labels AS lab ON lab.idLab = cont.idLab
				WHERE cont.createdFb = 0 AND ppAccountStatut = 0
				ORDER BY firstname ASC, lastname ASC
				") or die(mysql_error());


echo "<h4 class='text-success'>To add or not to add on Facebook, that's the question</h4>";

echo "<table class='table table-condensed '>";
echo "<tr>";
		echo "<th>Added</th>";
		echo "<th>Name</th>";
		echo "<th>Requested by</th>";
		echo "<th>Request date</th>";
	echo "<th>Label</th>";
	echo "<th>Type</th>";
		echo "<th>Start date</th>";
echo "</tr>";
$toActivate = 0;
			while($empValidation=mysql_fetch_array($queryEmpValidation))
			{
				$idCon = $empValidation['idCon'];
				$queryPark = mysql_query("SELECT * FROM parking WHERE contracts_idCon = $idCon") or die (mysql_error());
				$parking = mysql_fetch_array($queryPark);
				
				
				echo "<tr class='hover'>";
					echo "<td style=\"width:30px;\"><center><img src='img/fb-".$empValidation['createdFb']."-S.png' /></center></td>";
					echo "<td><a href='index.php?p=emp&tab=3&idE=".$empValidation['idE']."&idCon=".$empValidation['idCon']."&activation&upn=".$empValidation['upn']."&objectsid=".$empValidation['objectsid']."&tab=2' >".$empValidation['firstname']." ".$empValidation['lastname']."</a></td>";
					
					// find the requestor infomrations
					$requestorIdE = $empValidation['requestor'];
					$queryRequestor = mysql_query("SELECT * FROM employees WHERE idE = \"$requestorIdE\"") or die(mysql_error());
					$requestor = mysql_fetch_array($queryRequestor);
					echo "<td>".$requestor['firstname']." ".$requestor['lastname']."</td>";
					
					echo "<td>".$empValidation['ppConAddDate']."</td>";
				echo "<td>".$empValidation['labelName']."</td>";
				echo "<td>".$empValidation['empType']."</td>";
					echo "<td>".$empValidation['startDate']."</td>";
				echo "</tr>";
			}
echo "</table>";
?>
