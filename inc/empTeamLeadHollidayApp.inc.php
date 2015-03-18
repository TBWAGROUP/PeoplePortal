<!--
This script edits an employee's manager and holiday approvers

Included by:
index.php (case ?p=empTeamLeadHollidayApp)

Hrefs pointing here:

Requires:

Includes:
inc/sync/teamleadPUSH.php

Form actions:
index.php?p=empTeamLeadHollidayApp

-->

<?php
	if (isset($_GET['idE'])) {
		$idE = $_GET['idE'];
		$idCon = $_GET['idCon'];
		$upn = $_GET['upn'];
		$objectsid = $_GET["objectsid"];

		if (isset($_GET['up'])) {
			mysql_query("UPDATE teamLeads SET teamLeadDelete = 1 WHERE contracts_idCon = $idCon ") or die(mysql_error());

			$man = $_POST["man"];
			$checkMan = mysql_query("SELECT * FROM teamLeads WHERE contracts_idCon = $idCon AND employees_idE = $man AND appType=0") or die (mysql_error());
			if (mysql_num_rows($checkMan) == 0) {
				mysql_query("INSERT INTO teamLeads (contracts_idCon, employees_idE) VALUES($idCon, $man)") or die (mysql_error()); // and recreate them here
			} else {
				mysql_query("UPDATE teamLeads SET teamLeadDelete = 0 WHERE contracts_idCon = $idCon AND employees_idE = $man AND appType=0;");
			}

			if (!empty($_POST['HolidayApp'])) {
				foreach ($_POST['HolidayApp'] as $check) {
					$checkHolid = mysql_query("SELECT * FROM teamLeads WHERE contracts_idCon = $idCon AND employees_idE = $check AND appType=1") or die (mysql_error());
					if (mysql_num_rows($checkHolid) == 0) {
						mysql_query("INSERT INTO teamLeads (contracts_idCon, employees_idE, appType) VALUES($idCon, $check, 1)") or die (mysql_error()); // and recreate them here
					} else {
						mysql_query("UPDATE teamLeads SET teamLeadDelete = 0 WHERE contracts_idCon = $idCon AND employees_idE = $check AND appType=1;");
					}
				}
			}

			// clean the DB
			mysql_query("DELETE FROM teamLeads WHERE teamLeadDelete = 1 AND contracts_idCon = $idCon ") or die(mysql_error());

			// PUSH manager to AD
			if (($currentUser['itNetworkAdmin'] == 1) && ($objectsid != "")) {
				include($_SERVER['DOCUMENT_ROOT'] . "/inc/sync/pushTeamLead.php");
			}

			echo "<meta http-equiv='refresh' content='0;url=index.php?p=emp&idE=$idE&idCon=$idCon&upn=$upn&objectsid=$objectsid'>";
		} else {
			?>
			<center>
				<h3><?php echo $upn; ?></h3>
				<hr/>
				<form action="index.php?p=empTeamLeadHollidayApp&idE=<?php echo $idE; ?>&upn=<?php echo $upn; ?>&idCon=<?php echo $idCon; ?>&objectsid=<?php echo $objectsid; ?>&up" method="POST">

					<table class="userStartForm" width="250px" style="float:left;margin-left:250px;">
						<tr>
							<th class="title">
								<center>Manager<br>or Mentor for Interns</center>
							</th>
						</tr>
						<tr>
							<td>
								<div class="scrollGroup" id="usfEmailDom">
									<strong>Current: </strong><br/>
									<?php
										$checkManager = mysql_query("SELECT * FROM teamLeads AS tl INNER JOIN employees AS emp ON emp.idE = tl.employees_idE WHERE tl.contracts_idCon= $idCon AND tl.appType = 0 AND (emp.ppAccountStatut = 0 OR emp.ppAccountStatut = 3) ORDER BY emp.firstname, emp.lastname") or die (mysql_error());
										while ($manager = mysql_fetch_array($checkManager)) {
											?>
											<label><input type="radio" id="man" name="man" value="<?php echo $manager['idE']; ?>" checked/> <?php echo $manager['firstname']; ?> <?php echo $manager['lastname']; ?>
											</label><br/>
										<?php } ?>
									<strong>All : </strong><br/>
									<?php
										$checkManager = mysql_query("SELECT * FROM employees  WHERE teamLead = 1 AND (ppAccountStatut = 0 OR ppAccountStatut = 3) ORDER BY firstname, lastname") or die (mysql_error());
										while ($manager = mysql_fetch_array($checkManager)) {
											?>
											<label><input type="radio" id="man" name="man" value="<?php echo $manager['idE']; ?>"/> <?php echo $manager['firstname']; ?> <?php echo $manager['lastname']; ?>
											</label><br/>
										<?php } ?>
								</div>
							</td>
						</tr>
					</table>

					<table class="userStartForm" width="250px" style="margin-left:-100px;">
						<tr>
							<th class="title">
								<center>Holiday Approver(s)<br>for non-Interns only</center>
							</th>
						</tr>
						<tr class="emailHide">
							<td>
								<div class="scrollGroup" id="usfEmailDom">
									<strong>Current: </strong><br/>
									<?php
										$checkHolidayApp = mysql_query("SELECT * FROM teamLeads AS tl INNER JOIN employees AS emp ON emp.idE = tl.employees_idE WHERE tl.contracts_idCon= $idCon AND tl.appType = 1 AND (ppAccountStatut = 0 OR ppAccountStatut = 3)") or die (mysql_error());
										while ($listHolidayApp = mysql_fetch_array($checkHolidayApp)) {
											?>
											<label><input type="checkbox" id="HolidayAppCB" name="HolidayApp[]" value="<?php echo $listHolidayApp['idE']; ?>" checked/> <?php echo $listHolidayApp['firstname']; ?> <?php echo $listHolidayApp['lastname']; ?>
											</label><br/>
										<?php } ?>
									<strong>All : </strong><br/>
									<?php
										$queryHolidayAppAll = mysql_query("SELECT * FROM employees  WHERE (holidayApp = \"Admin\" OR holidayApp = \"Approver\") AND (ppAccountStatut = 0 OR ppAccountStatut = 3)") or die(mysql_error());
										while ($HolidayAppAll = mysql_fetch_array($queryHolidayAppAll)) {
											?>
											<label><input type="checkbox" id="HolidayAppCB" name="HolidayApp[]" value="<?php echo $HolidayAppAll['idE']; ?>"/> <?php echo $HolidayAppAll['firstname']; ?> <?php echo $HolidayAppAll['lastname']; ?>
											</label><br/>
										<?php } ?>
								</div>
							</td>
						</tr>
					</table>
					<p><br/><br/>
						<button class="btn btn-default">Apply</button>
					</p>
					<p>
						<br/><a href="index.php?p=emp&idE=<?php echo $idE; ?>&idCon=<?php echo $idCon; ?>&upn=<?php echo $upn; ?>&objectsid=<?php echo $objectsid; ?>" class="btn btn-default btn-sm"> Back</a>
					</p>
				</form>
			</center>
		<?php
		}
	}
?>
