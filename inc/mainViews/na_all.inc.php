<!--
This script shows a generic user's managed employees and open requests, we'll add birthdays, new employees etc later on

Included by:
inc/showUsfProcess.inc.php

Hrefs pointing here:

Requires:

Includes:
inc/showUsfProcess.inc.php

Form actions:

-->

<div class="dashboard">
	<hr>
<!--	<div style="float:right"> -->
		<div>
		<?php
			// You are the manager of LIST;

			$queryTeamLead = mysql_query("
																	SELECT * FROM contracts AS cont
																	INNER JOIN teamLeads AS tl ON cont.idCon = tl.contracts_idCon
																	INNER JOIN employees AS emp ON emp.idE = cont.idE
																	INNER JOIN labels AS lab ON lab.idLab = cont.idLab
																	WHERE tl.employees_idE = $currentIdE
																	AND cont.validationStage = 0
																	ORDER BY cont.endDateTS ASC
																") or die (mysql_error());
			if (mysql_num_rows($queryTeamLead) > 0) {

				echo "<h3 class='text-info'>You are the manager of the people listed below (sorted by end date if any)</h3>";
				echo "<table class='table table-condensed'>";
				echo "<tr class='active'>";
				echo "<th>Name</th>";
				echo "<th>Start date</th>";
				echo "<th>End date</th>";
				echo "<th>Type</th>";
				echo "</tr>";

				$toActivate = 0;

				while ($teamLead = mysql_fetch_array($queryTeamLead)) {
					echo "<tr class='hover'>";
					echo "<td><a href='index.php?p=emp&idE=" . $teamLead['idE'] . "&idCon=" . $teamLead['idCon'] . "' >" . $teamLead['firstname'] . " " . $teamLead['lastname'] . "</a></td>";
					echo "<td>" . $teamLead['startDate'] . "</td>";
					echo "<td>" . $teamLead['endDate'] . "</td>";
					echo "<td>" . $teamLead['empType'] . "</td>";
					echo "</tr>";
				}
				echo "</table>";
			}
		?>
	</div>

	<div>
		<?php
			// Your requests waiting for approval LIST;

			$source = "WHERE cont.requestor = $currentIdE AND cont.validationStage != 0 AND cont.validationStage != 4";
			echo "<div>";
			$cancelBtn = 1;
			include("inc/showUsfProcess.inc.php");
			echo "</div>";
		?>
	</div>

	<div>
		<?php
			echo "<h3 class='text-info'>Your profile</h3>";
			$_GET['idE'] = $currentIdE;
			include($_SERVER["DOCUMENT_ROOT"]."/inc/employeeProfile.inc.php");
		?>
	</div>
</div> <!-- dashboard -->
