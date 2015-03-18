<!--
This script shows all new employees in provisioning stage, recent and upcomming starters and terminations

Included by:

Hrefs pointing here:

Requires:

Includes:
inc/showUsfProcess.inc.php
inc/logs/access.log.php
inc/logs/approvals.log.php

Form actions:

-->

<script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css"/>

<h2>People Portal Admin</h2>
<hr/>

<div id="TabbedPanels1" class="TabbedPanels">
	<ul class="TabbedPanelsTabGroup">
		<li class="TabbedPanelsTab" tabindex="0">Home</li>
		<li class="TabbedPanelsTab" tabindex="10">Access Log</li>
		<li class="TabbedPanelsTab" tabindex="20">Approvals log</li>
	</ul>

	<div class="TabbedPanelsContentGroup">
		<!-- Infos -->

		<div class="TabbedPanelsContent">
			<h4>All contracts currently in approval or provisioning stage</h4>
			<?php
				$source = "WHERE cont.validationStage != 0 AND cont.validationStage != 4031 AND cont.validationStage != 4";
				$cancelBtn = 1;
				include("inc/showUsfProcess.inc.php");
			?>

			<hr/>

			<?php
				echo "<h4>Please enter time range for overview of starters and terminations below</h4>";
				$daysPast = 0;
				$daysFuture = 365;

				if (isset($_POST['submitDateRange'])) {
					$daysPast = $_POST['daysPast'];
					$daysFuture = $_POST['daysFuture'];
				}
			?>

			<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				Looking back
				<input type="text" size=3 name="daysPast" value="<?php echo $daysPast ?>"> days. Looking forward
				<input type="text" size=3 name="daysFuture" value="<?php echo $daysFuture ?>"> days.
				<input type="submit" name="submitDateRange" value="Go"><br><br>
			</form>

			<?php
				// Starters in dateRange
				$queryEmpValidation = mysql_query("
					SELECT * FROM contracts AS cont
					INNER JOIN employees AS emp ON emp.idE = cont.idE
					INNER JOIN labels AS lab ON lab.idLab = cont.idLab
					INNER JOIN functions AS fun ON fun.idFunc = cont.idFunc
					LEFT JOIN teamLeads AS lead ON lead.contracts_idCon = cont.idCon
					WHERE (lead.appType = 0 OR lead.appType IS NULL)
					ORDER BY cont.startDateTS, cont.empType, fun.functionName, emp.firstname
					") or die(mysql_error());
				echo "<h4>Users needing activation in time range</h4>";
				echo "<table class='table table-condensed'>";
				echo "<tr  class='active'>";
				echo "<th>HR</th>";
				echo "<th>In AD</th>";
				echo "<th>Provisioning</th>";
				echo "<th>Name</th>";
				echo "<th>Type</th>";
				echo "<th>Function</th>";
				echo "<th>Manager</th>";
				echo "<th>Start date</th>";
				echo "<th>End date</th>";
				echo "<th>Requested by</th>";
				echo "</tr>";
				while ($empValidation = mysql_fetch_array($queryEmpValidation)) {
					if ($empValidation['objectsid'] != "") {
						$statut = "ok";
					} else {
						$statut = "noOk";
					}

					if (($empValidation['validationStage'] >= 2)) {
						$hrApprov = "ok";
					} else {
						$hrApprov = "noOk";
					}
					if (($empValidation['validationStage'] >= 3)) {
						$provApprov = "ok";
					} else {
						$provApprov = "noOk";
					}
					if (($empValidation['validationStage'] == 0)) {
						$hrApprov = "ok";
						$provApprov = "ok";
					}

					$startDate = $empValidation['startDate'];
					echo "<tr class='hover'>";
					// 1 day = 86 400 seconds
					$startDateBef = time() - ($daysPast +1) * 86400;
					$startDateAft = time() + ($daysFuture) * 86400;
					$startDate = str_replace("/", "-", $startDate);
					$startDate = strtotime($startDate);

					if (($startDate >= $startDateBef) && ($startDate <= $startDateAft)) {

						// find the requestor infomrations
						$requestorIdE = $empValidation['requestor'];
						$queryRequestor = mysql_query("SELECT * FROM employees WHERE idE = \"$requestorIdE\"") or die(mysql_error());
						$requestor = mysql_fetch_array($queryRequestor);

						// find the manager infomrations
						$managerIdE = $empValidation['employees_idE'];
						$queryManager = mysql_query("SELECT * FROM employees WHERE idE = \"$managerIdE\"") or die(mysql_error());
						$manager = mysql_fetch_array($queryManager);

						echo "<td><img src='img/" . $hrApprov . "S.png' /></td>";
						echo "<td><img src='img/" . $statut . "S.png' /></td>";
						echo "<td><img src='img/" . $provApprov . "S.png' /></td>";
						echo "<td><a href='index.php?p=emp&idE=" . $empValidation['idE'] . "&idCon=" . $empValidation['idCon'] . "&activation&upn=" . $empValidation['upn'] . "&objectsid=" . $empValidation['objectsid'] . "' >" . $empValidation['firstname'] . " " . $empValidation['lastname'] . "</a></td>";
						echo "<td>" . $empValidation['empType'] . "</td>";
						echo "<td>" . $empValidation['functionName'] . "</td>";
						echo "<td>" . $manager['firstname'] . " " . $manager['lastname'] . "</td>";
						echo "<td>" . $empValidation['startDate'] . "</td>";
						echo "<td>" . $empValidation['endDate'] . "</td>";
						echo "<td>" . $requestor['firstname'] . " " . $requestor['lastname'] . "</td>";
					}
					echo "</tr>";
				}
				echo "</table>";

				// Terminations in dateRange
				$queryEmpTermination = mysql_query("
					SELECT * FROM contracts AS cont
                    INNER JOIN employees AS emp ON emp.idE = cont.idE
                    INNER JOIN labels AS lab ON lab.idLab = cont.idLab
					LEFT JOIN teamLeads AS lead ON lead.contracts_idCon = cont.idCon
					WHERE (lead.appType = 0 OR lead.appType IS NULL)
					AND cont.disableAccountDate != 0
					AND emp.ppAccountStatut != 1
					ORDER BY disableAccountDateTS
                    ") or die(mysql_error());

				echo "<h4>Users needing termination in time range</h4>";
				echo "<table class='table table-condensed'>";
				echo "<tr  class='active'>";
				echo "<th>In AD</th>";
				echo "<th>Name</th>";
				echo "<th>Type</th>";
				echo "<th>Manager</th>";
				echo "<th>Contract End</th>";
				echo "<th>Operational</th>";
				echo "<th>Disable Account</th>";
				echo "<th>Material Return</th>";
				echo "</tr>";
				while ($empTermination = mysql_fetch_array($queryEmpTermination)) {
					if ($empTermination['objectsid'] != "") {
						$statut = "ok";
					} else {
						$statut = "noOk";
					}

					if (($empTermination['validationStage'] >= 2)) {
						$hrApprov = "ok";
					} else {
						$hrApprov = "noOk";
					}
					if (($empTermination['validationStage'] >= 3)) {
						$provApprov = "ok";
					} else {
						$provApprov = "noOk";
					}
					if (($empTermination['validationStage'] == 0)) {
						$hrApprov = "ok";
						$provApprov = "ok";
					}

					$endDate = $empTermination['disableAccountDate'];

					echo "<tr class='hover'>";
					// 1 day = 86 400 seconds
					$endDateBef = time() - ($daysPast +1) * 86400;
					$endDateAft = time() + ($daysFuture) * 86400;
					$endDate = str_replace("/", "-", $endDate);
					$endDate = strtotime($endDate);

					if (($endDate >= $endDateBef) && ($endDate <= $endDateAft)) {
						echo "<td><img src='img/" . $statut . "S.png' /></td>";
						echo "<td><a href='index.php?p=emp&tab=0&idE=" . $empTermination['idE'] . "&idCon=" . $empTermination['idCon'] . "&activation&upn=" . $empTermination['upn'] . "&objectsid=" . $empTermination['objectsid'] . "' >" . $empTermination['firstname'] . " " . $empTermination['lastname'] . "</a></td>";
						echo "<td>" . $empTermination['empType'] . "</td>";

						// find the manager infomrations
						$managerIdE = $empTermination['employees_idE'];
						$queryManager = mysql_query("SELECT * FROM employees WHERE idE = \"$managerIdE\"") or die(mysql_error());
						$manager = mysql_fetch_array($queryManager);

						echo "<td>" . $manager['firstname'] . " " . $manager['lastname'] . "</td>";
						echo "<td>" . $empTermination['endDate'] . "</td>";
						echo "<td>" . $empTermination['operationalEndDate'] . "</td>";
						echo "<td>" . $empTermination['disableAccountDate'] . "</td>";
						echo "<td>" . $empTermination['materialReturnDate'] . "</td>";
					}
					echo "</tr>";
				}
				echo "</table>";

			?>
			<hr/>

		</div> <!-- Tab 1 -->
		<div class="TabbedPanelsContent"><?php include('inc/logs/access.log.php'); ?></div>
		<div class="TabbedPanelsContent"><?php include('inc/logs/approvals.log.php'); ?></div>
	</div> <!-- Tab group -->
</div> <!-- content pane -->
<script type="text/javascript">
	var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1", {defaultTab: 0});
</script>
