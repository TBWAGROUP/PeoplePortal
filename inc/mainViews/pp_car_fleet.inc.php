<!--
This script lists new users for car fleet info, mainly license plate, for PP carfleet people

Included by:

Hrefs pointing here:

Requires:

Includes:

Form actions:

-->

<?php

	$queryEmpValidation = mysql_query("
SELECT * FROM contracts AS cont
				LEFT JOIN employees AS emp ON emp.idE = cont.idE
				INNER JOIN labels AS lab ON lab.idLab = cont.idLab
				LEFT JOIN parking AS park ON park.contracts_idCon = cont.idCon
				WHERE ppAccountStatut = 0 AND (endDateTS > CURRENT_DATE OR endDateTS = 0 OR endDateTS IS NULL) AND (cont.idCon NOT IN (SELECT idCon FROM parking) OR park.comesBy = 'car' OR length(park.comesBy) = 0 OR (park.comesBy LIKE '%car%' AND length(park.nrPlaat) = 0) OR (park.comesBy LIKE '%car%' AND length(park.parking) = 0) OR (park.parking = 'brico' AND length(park.parkingNr) = 0))
				ORDER BY firstname ASC, lastname ASC
								") or die(mysql_error());


echo "<h3 class='text-success'>Users with missing transport details</h3>";


echo "<table class='table table-condensed'>";
echo "<tr class='active'>";
		echo "<th>Status</th>";
		echo "<th>Name</th>";
		echo "<th>Label</th>";
	echo "<th>Type</th>";
	echo "<th>Comes by</th>";
	echo "<th>License Plate Entry</th>";
	echo "<th>Parking Location</th>";
	echo "<th>Parking Number</th>";
echo "</tr>";
$toActivate = 0;
			while($empValidation=mysql_fetch_array($queryEmpValidation))
			{				
				$queryGroupId = mysql_query("SELECT idGroup FROM groups WHERE groupName = \"$pp_carfleet\"") or die (mysql_error());
				$idGroup = array_shift(mysql_fetch_array($queryGroupId));
				$queryGroupStatut = mysql_query("SELECT * FROM approvals WHERE idGroup = \"$idGroup\" AND idCon = \"$empValidation[idCon]\" ") or die (mysql_error());
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
					
				
				
				
				echo "<tr class='hover'>";
					echo "<td><img src='img/".$statut."S.png' /></td>";
					echo "<td><a href='index.php?p=emp&tab=3&idE=".$empValidation['idE']."&idCon=".$empValidation['idCon']."&activation&upn=".$empValidation['upn']."&objectsid=".$empValidation['objectsid']."&tab=2' >".$empValidation['firstname']." ".$empValidation['lastname']."</a></td>";
					
					echo "<td>".$empValidation['labelName']."</td>";
				echo "<td>".$empValidation['empType']."</td>";
				echo "<td>".$empValidation['comesBy']."</td>";
				echo "<td>".$empValidation['nrPlaat']."</td>";
				echo "<td>".$empValidation['parking']."</td>";
				echo "<td>".$empValidation['parkingNr']."</td>";
				echo "</tr>";
			}
echo "</table>";
?>
