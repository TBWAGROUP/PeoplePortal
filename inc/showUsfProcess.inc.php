<!--
This script shows the user approval and provisioning status and allows to edit once approved/done, all depending on PP group membership

Included by:
inc/emp.inc.php
inc/mainViews/na_all.inc.php
inc/mainViews/pp_admin.inc.php
inc/mainViews/pp_hr.inc.php
inc/mainViews/pp_planning.inc.php

Hrefs pointing here:

Requires:

Includes:

Form actions:

-->

<?php
if (!isset($cancelBtn)) { $cancelBtn = 0; }
			
			
$queryRequest = mysql_query ("
								SELECT * FROM contracts AS cont
									INNER JOIN employees AS emp ON emp.idE = cont.idE
									INNER JOIN labels AS lab ON lab.idLab = cont.idLab
									INNER JOIN functions AS fun ON fun.idFunc = cont.idFunc
									$source
									ORDER BY cont.startDateTS, cont.empType, fun.functionName, emp.firstname ASC
								") or die (mysql_error());

if (mysql_num_rows($queryRequest) > 0)
{

	echo "<h3 class='text-info'>User Start Form workflow status</h3>";
	echo "<table class='table table-condensed'>";
	echo "<tr class='active'>";
			if ($cancelBtn==1) { echo "<th>Cancel</th>"; }
			echo "<th>Name</th>";
			echo "<th>Start date</th>";
			echo "<th>Type</th>";
			echo "<th><center>HR</center></th>";
			echo "<th><center>Badge</center></th>";
			echo "<th><center>Maconomy</center></th>";
			echo "<th><center>B. Card</center></th>";
			echo "<th><center>Facebook</center></th>";
			echo "<th><center>IT</center></th>";
	echo "</tr>";

	$toActivate = 0;
			while ($request = mysql_fetch_array($queryRequest))
			{
				$requestIdCon = $request['idCon'];
				$queryApprovalLog = mysql_query ("
									SELECT * FROM contracts AS cont
										INNER JOIN employees AS emp ON emp.contract = cont.idCon
										WHERE idCon = $requestIdCon
									") or die (mysql_error());
					// initialisation
					$hr = "quest" ; $buildingIcon = "questS"; $planningIcon = "questS"; $essCreatedIcon = "questS"; $maconomyIcon = "questS"; $facebookIcon = "questS"; $adminIcon = "questS"; $businessCardIcon = "questS";
					while ($approvalStatut = mysql_fetch_array($queryApprovalLog))
					{
						if ($approvalStatut['badgeNr'] == "") { $buildingIcon = "notNeeded"; } else if ($approvalStatut['badgeNr'] == "0") { $buildingIcon = "questS"; }  else if ($approvalStatut['badgeNr'] > 0) { $buildingIcon = "okS"; }
						if ($approvalStatut['maconomy'] == 0) { $maconomyIcon = "notNeeded"; } else if ($approvalStatut['maconomy'] == 1) { $maconomyIcon = "questS"; } if ($approvalStatut['createdInMaconomy'] == 1) { $maconomyIcon = "okS"; }
						if ($approvalStatut['businessCardNeeded'] == 0) { $businessCardIcon = "notNeeded"; } else if ($approvalStatut['businessCardNeeded'] == 1) { $businessCardIcon = "questS"; }  if ($approvalStatut['businessCardCreated'] == 1) { $businessCardIcon = "okS"; }
						if ($approvalStatut['createdFb'] == "0") { $facebookIcon = "questS"; } else if ($request['createdFb'] == 1) { $facebookIcon = "okS"; } else if ($request['createdFb'] == 2) { $facebookIcon = "notNeeded"; }
						if ($approvalStatut['objectsid'] == "") { $adminIcon = "questS"; } else if ($request['objectsid'] != "") { $adminIcon = "okS"; } else if ($request['ppAccountStatut'] == 1) { $adminIcon = "noOkS"; }
					}
					if ($request['validationStage'] == 2) { $hrIcon = "ok"; } 
					else if ($request['validationStage'] == 0) { $hrIcon = "ok"; }
					else if ($request['validationStage'] == 1) { $hrIcon = "quest"; }				
					else if ($request['validationStage'] == 4031) { $hrIcon = "noOk"; }				
					
					echo "<tr class='hover'>";
					if ($cancelBtn==1) { 
						echo "<td><a href=\"index.php?p=cancelRequest&idCon=".$request['idCon']."&idE=".$request['idE']."\"><img class=\"ref\" src=\"img/cancel.png\" title=\"Cancel Request\" /> </a></td>"; 
					}
						echo "<td><a href='index.php?p=emp&tab=0&idE=".$request['idE']."&idCon=".$request['idCon']."&activation&upn=".$request['upn']."&objectsid=".$request['objectsid']."' >".$request['firstname']." ".$request['lastname']."</a></td>";
						echo "<td>".$request['startDate']."</td>";
						echo "<td>".$request['empType']."</td>";
						echo "<td><center><img src='img/".$hrIcon."S.png' /></center></td>";					
						echo "<td><center><img src='img/".$buildingIcon.".png' /></center></td>";					
						echo "<td><center><img src='img/".$maconomyIcon.".png' /></center></td>";					
						echo "<td><center><img src='img/".$businessCardIcon.".png'  title=\"Business card\" /></center></td>";	
						echo "<td><center><img src='img/".$facebookIcon.".png' /></center></td>";	
						echo "<td><center><img src='img/".$adminIcon.".png'/></center></td>";	
					echo "</tr>";
			}
	echo "</table>";
}
//else { echo "None"; }
 ?>