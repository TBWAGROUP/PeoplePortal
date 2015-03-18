<!--
This script checks maintenance mode, current user signin status, opens session, checks user PP group membership
Builds the framework of all pages with buttons and actions at very top, based on PP membership
Selects different default employee lists based on PP membership
Nerve-center of ?p=emp variable to call different pages

Included by:
none

Hrefs pointing here:
inc/empEmailDomain.inc.php
inc/tools.inc.php
inc/userStartForm/provisioning/pp_facebook.fields.php
inc/userStartForm/provisioning/pp_finance.fields.php
inc/userStartForm/provisioning/pp_building.fields.php
index.php
inc/empTeamLeadHollidayApp.inc.php
inc/employeesLists/pp_admin/usersTrash.inc.php
inc/employeesLists/pp_admin/usersFrozen.inc.php
inc/employeesLists/pp_admin/usersStartForm.inc.php
inc/managing/labelEmailDomains.inc.php
inc/managing/labelManagor.inc.php
inc/managing/departmentManagor.inc.php
inc/managing/functionsManagor.inc.php
inc/managing/contractsManagor.inc.php
inc/managing/fileServerManagor.inc.php
inc/managing/securityAccessManagor.inc.php
inc/employeeProfile.inc.php
inc/employeesLists/pp_admin/usersStandalone.inc.php
inc/ppTools/groupsManager.inc.php
inc/ppTools/userGroupsManager.inc.php
inc/employeesLists/pp_admin/usersActive.inc.php
inc/employeesLists/pp_admin/usersArchived.inc.php
inc/ppTools/usersManager.inc.php
inc/employeesLists/pp_admin/usersAll.inc.php
inc/employeesLists/na_all-employeeList.inc.php
inc/employeesLists/pp_hr-employeeList.inc.php
inc/employeesLists/pp_building-employeeList.inc.php
inc/employeesLists/pp_planning-employeeList.inc.php
inc/employeesLists/pp_admin-employeeList.inc.php
inc/employeesLists/pp_carfleet-employeeList.inc.php
inc/employeesLists/pp_facebook-employeeList.inc.php
inc/employeesLists/pp_finance-employeeList.inc.php
inc/sync/syncGlobalAD.php
inc/userStartForm/cancelRequest.inc.php
inc/ad/showDisabledAccount.inc.php

Requires:
bdd/connect.inc.php
bdd/adConnect.inc.php
functions.php
groupsName.conf.php

Includes:
inc/ad/manageAccount.inc.php
inc/ad/showDisabledAccount.inc.php
inc/ad/viewAll.inc.php
inc/ADcheck.inc.php
inc/emp.inc.php
inc/empEmailDomain.inc.php
inc/employeesLists/*-employeeList.inc.php
inc/empTeamLeadHollidayApp.inc.php
inc/managing/contractsManagor.inc.php
inc/managing/departmentManagor.inc.php
inc/managing/fileServerManagor.inc.php
inc/managing/functionsManagor.inc.php
inc/managing/labelEmailDomains.inc.php
inc/managing/labelManagor.inc.php
inc/managing/securityAccessManagor.inc.php
inc/ppTools/accessRequest.inc.php
inc/ppTools/groupsManager.inc.php
inc/ppTools/usersManager.inc.php
inc/saveEmp.inc.php
inc/saveEmpFinance.inc.php
inc/sync/syncGlobalAD.php
inc/sync/empGroupSync.php
inc/sync/groupSync.php
inc/sync/pullUserAdSync.php
inc/sync/userAdSync.php
inc/tools.inc.php
inc/userStartForm/0-usfFields.inc.php
inc/userStartForm/0-usfSend.inc.php
inc/userStartForm/1-usfSend.inc.php
inc/userStartForm/cancelRequest.inc.php
inc/userStartForm/provisioning/pp_admin.fields.php
inc/userStartForm/provisioning/pp_building.fields.php
inc/userStartForm/provisioning/pp_car_fleet.fields.php
inc/userStartForm/provisioning/pp_facebook.fields.php
inc/userStartForm/provisioning/pp_finance.fields.php
inc/userStartForm/provisioning/pp_planning.fields.php
main.inc.php
signIn.inc.php

-->

<?php
	$maintenanceMode = 0; // 1 : activate, 0 : unactivated (default)
	if ($maintenanceMode == 0) {
		// -------------------------
		session_start();
		if (isset($_SESSION['user'])) {
			require("bdd/connect.inc.php");
			require("bdd/adConnect.inc.php");
			require("functions.php");
			require("groupsName.conf.php");
			// Gdata library path
			$clientLibraryPath = $_SERVER['DOCUMENT_ROOT'] . 'Zend/';
			$oldPath = set_include_path(get_include_path() . PATH_SEPARATOR . $clientLibraryPath);
			// not yet tested
			$currentIdE = $_SESSION['idE'];
			$userName = $_SESSION['user']; // name.first
			$user = $_SESSION['user'] . "@ad.tbwagroup.be"; // name.first@ad.tbwagroup.be
			$pass = $_SESSION['password']; // AD user password
			// GET info from the connected user
			$queryCurrentUser = mysql_query("SELECT * FROM employees AS emp INNER JOIN contracts AS cont ON emp.idE = cont.idE WHERE emp.idE = $currentIdE");
			$currentUser = mysql_fetch_array($queryCurrentUser);
			//print_r($_SESSION['securityGroup']); //groupMember debuging
			// Zend Gdata infos
			$emailGdata = $userName . "@tbwa.be";
			// spreadsheets keys
			// $employeeList = "0AkcKsaT3ojiNdHp0TWF2WF9WcnpPQ2xGcHQzMUVhS1E"; // test
			$employeeList = "0AqfehxH0LfjgdDBOa1R0Yk9Mb1lJX2RwQzB5d25oQ1E"; // test
			// $employeeList = "0AqfehxH0LfjgdF9SV0dyMlU3NVFGY3NfY1VwTGd1c0E"; // employees-back
			$todayDate = date("j/m/Y");
			// echo dateToAccountExpires("30/06/2007");
			// echo "<br />";
			// echo accountExpiresToDate("128276280010000000");
			//define the employee list
			if (memberOf($na_all)) {
				$empList = "na_all";
			}
			if (memberOf($pp_facebook)) {
				$empList = "pp_facebook";
			}
			if (memberOf($pp_planning)) {
				$empList = "pp_planning";
			}
			if (memberOf($pp_carfleet)) {
				$empList = "pp_carfleet";
			}
			if (memberOf($pp_building)) {
				$empList = "pp_building";
			}
			if (memberOf($pp_finance)) {
				$empList = "pp_finance";
			}
			if (memberOf($pp_hr)) {
				$empList = "pp_hr";
			}
			if (memberOf($pp_admin)) {
				$empList = "pp_admin";
			}


			?>

			<!DOCTYPE html>
			<html lang="en">
			<head>
				<meta charset="utf-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, initial-scale=1">
				<meta name="description" content="">
				<meta name="author" content="Lhost Pierre">
				<link rel="shortcut icon" href="favicon.ico">

				<title>People Portal</title>

				<!-- Bootstrap core CSS -->
				<link href="css/bootstrap.css" rel="stylesheet">
				<link href="css/theme.css" rel="stylesheet">
				<script src="js/jquery.min.js"></script>
				<script src="js/bootstrap.min.js"></script>

				<script>

					function updateClock() {
						var currentTime = new Date();
						var currentDay = currentTime.getDate();
						var currentMonth = (currentTime.getMonth() + 1);
						var currentYear = currentTime.getFullYear();
						var currentHours = currentTime.getHours();
						var currentMinutes = currentTime.getMinutes();
						var currentSeconds = currentTime.getSeconds();

						if (currentDay < 10) {
							currentDay = "0" + currentDay;
						}
						if (currentMonth < 10) {
							currentMonth = "0" + currentMonth;
						}
						if (currentHours < 10) {
							currentHours = "0" + currentHours;
						}
						if (currentMinutes < 10) {
							currentMinutes = "0" + currentMinutes;
						}
						if (currentSeconds < 10) {
							currentSeconds = "0" + currentSeconds;
						}

						// Compose the string for display
						var currentTimeString = currentDay + "/" + currentMonth + "/" + currentYear + " " + currentHours + ":" + currentMinutes + ":" + currentSeconds;


						$("#clock").html(currentTimeString);

					}

					$(document).ready(function () {
						setInterval('updateClock()', 1000);
					});
				</script>

				<link href="jtable/themes/redmond/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css"/>
				<script src="jtable/scripts/jquery-1.6.4.min.js" type="text/javascript"></script>
				<script src="jtable/scripts/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
				<link href="jtable/themes/lightcolor/gray/jtable.css" rel="stylesheet" type="text/css"/>
				<script src="jtable/scripts/jtable/jquery.jtable.js" type="text/javascript"></script>

			</head>

			<body>

			<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
				<div class="container">
					<div class="navbar-header">
						<span class="navbar-brand" href="index.php"><img src="img/logoDashS.png"/> PEOPLE PORTAL</span>
					</div>
					<div class="menuHeader">
						<a href="index.php?p=main"><strong><img src="img/homeWht.png"/> Home</strong></a>
						<a href="index.php?p=empList"><strong><img src="img/peopleWht.png"/> List of people</strong></a>
						<?php if ((((memberOf($pp_admin)) || (memberOf($pp_hr)) || (memberOf($pp_planning)) || ($currentUser['teamLead'])))) { ?>
							<a href="index.php?p=0-usfFields"><strong><img src="img/userformWht.png"/> User start form</strong></a><?php } ?>
						<?php if (memberOf($pp_admin)) { ?>
							<a href="index.php?p=viewAD"><strong><img src="img/windows2012_40x40.png"/> AD search</strong></a><?php } ?>
						<?php if ((memberOf($pp_admin)) || (memberOf($pp_hr))) { ?>
							<a href="index.php?p=tools"><strong><img src="img/toolsWht.png"/> Tools</strong></a><?php } ?>
						<span class="text_bar"><?php echo "Current Login: ".$_SESSION['firstname'] . " " . $_SESSION['lastname']; ?> </span>
						<a href="logout.php"><strong><img src="img/logout.png"/> Sign out</strong></a>
						<span class="text_bar"> <img src='img/clock24x24Wht.png'/>
							<span id='clock'><?php echo date("j/m/Y H:i:s"); ?></span></span>
					</div>
				</div>
			</div>

			<!-- Pages. With div class='container' for most -->
			<?php
				if (isset($_GET["p"])) {
					switch ($_GET["p"]) {

						// FIELDS
						case "fields_pp_building":
							echo "<div class='employeeList'>";
							include("inc/userStartForm/provisioning/pp_building.fields.php");
							echo "</div>";
							break;
						case "fields_pp_parking":
							echo "<div class='employeeList'>";
							include("inc/userStartForm/provisioning/pp_parking.fields.php");
							echo "</div>";
							break;
						case "fields_pp_facebook":
							if (memberOf($pp_facebook)) {
								echo "<div class='employeeList'>";
								include("inc/userStartForm/provisioning/pp_facebook.fields.php");
								echo "</div>";
							} else {
								echo "<div class='container starter-template'><h2 class='text-danger'>You can't access here</h2></div>";
							}
							break;
						case "fields_pp_car_fleet":
							if (memberOf($pp_carfleet)) {
								echo "<div class='employeeList'>";
								include("inc/userStartForm/provisioning/pp_car_fleet.fields.php");
								echo "</div>";
							} else {
								echo "<div class='container starter-template'><h2 class='text-danger'>You can't access here</h2></div>";
							}
							break;
						case "fields_pp_finance":
							if (memberOf($pp_finance)) {
								echo "<div class='employeeList'>";
								include("inc/userStartForm/provisioning/pp_finance.fields.php");
								echo "</div>";
							} else {
								echo "<div class='container starter-template'><h2 class='text-danger'>You can't access here</h2></div>";
							}
							break;
						case "fields_pp_admin":
							if (memberOf($pp_admin)) {
								echo "<div class='employeeList'>";
								include("inc/userStartForm/provisioning/pp_admin.fields.php");
								echo "</div>";
							} else {
								echo "<div class='container starter-template'><h2 class='text-danger'>You can't access here</h2></div>";
							}
							break;
						case "fields_pp_planning":
							if (memberOf($pp_planning)) {
								echo "<div class='employeeList'>";
								include("inc/userStartForm/provisioning/pp_planning.fields.php");
								echo "</div>";
							} else {
								echo "<div class='container starter-template'><h2 class='text-danger'>You can't access here</h2></div>";
							}
							break;
						case "empList":
							echo "<div class='employeeList'>";
							include("inc/employeesLists/" . genMainView($empList) . "-employeeList.inc.php");
							echo "</div>";
							break;
						case "emp":
							echo "<div class='container starter-template'>";
							include("inc/emp.inc.php");
							echo "</div>";
							break;
						case "tools":
							echo "<div class='container starter-template'>";
							include("inc/tools.inc.php");
							echo "</div>";
							break;

						// Synchronisation scripts
						case "globalSync":
							if (memberOf($pp_admin)) {
								echo "<div class='container starter-template'>";
								include("inc/sync/syncGlobalAD.php");
								echo "</div>";
							} else {
								echo "<h2 class='text-danger'>You can't access here</h2>";
							}
							break;
						case "empGroupSync" :
							echo "<div class='container starter-template'>";
							include("inc/sync/syncEmployeeGroupMembership.php");
							echo "</div>";
							break;
						case "syncEmp" :
							echo "<div class='container starter-template'>";
							include("inc/sync/syncUsersFromADtoDB.php");
							echo "</div>";
							break;
						case "pullEmp" :
							echo "<div class='container starter-template'>";
							include("inc/sync/pullUserAdSync.php");
							echo "</div>";
							break;
						case "groupSync" :
							echo "<div class='container starter-template'>";
							include("inc/sync/syncGroupsFromADtoDB.php");
							echo "</div>";
							break;
						case "ADcheck" :
							echo "<div class='container starter-template'>";
							include("inc/ADcheck.inc.php");
							echo "</div>";
							break;

						// ACTIVE DIRECTORY SCRIPTS
						case "showDisAcc":
							echo "<div class='container starter-template'>";
							include("inc/ad/showDisabledAccount.inc.php");
							echo "</div>";
							break;
						case "manAcc":
							echo "<div class='container starter-template'>";
							include("inc/ad/manageAccount.inc.php");
							echo "</div>";
							break;
						case "viewAD":
							echo "<div class='container starter-template'>";
							include("inc/ad/viewAll.inc.php");
							echo "</div>";
							break;

						// User start forms
						case "cancelRequest": // Start form + approval
							echo "<div class='container starter-template'>";
							include("inc/userStartForm/cancelRequest.inc.php");
							echo "</div>";
							break;

						// START ENTRY
						case "0-usfFields": // Start form + approval
							echo "<div class='container starter-template'>";
							include("inc/userStartForm/0-usfFields.inc.php");
							echo "</div>";
							break;
						case "0-usfSend":    // start form send - adding
							echo "<div class='container starter-template'>";
							include("inc/userStartForm/0-usfSend.inc.php");
							echo "</div>";
							break;
						case "1-usfSend":    // HR Approver send - updating
							echo "<div class='container starter-template'>";
							include("inc/userStartForm/1-usfSend.inc.php");
							echo "</div>";
							break;

						// PP Tools
						case "accessRequest" :
							echo "<div class='container starter-template'>";
							include("inc/ppTools/accessRequest.inc.php");
							echo "</div>";
							break;
						case "empTeamLeadHollidayApp" :
							echo "<div class='container starter-template'>";
							include("inc/empTeamLeadHollidayApp.inc.php");
							echo "</div>";
							break;
						case "empEmailDomain" :
							echo "<div class='container starter-template'>";
							include("inc/empEmailDomain.inc.php");
							echo "</div>";
							break;
						case "saveEmp" :
							echo "<div class='container starter-template'>";
							include("inc/saveEmp.inc.php");
							echo "</div>";
							break;
						case "saveEmpFinance" :
							echo "<div class='container starter-template'>";
							include("inc/saveEmpFinance.inc.php");
							echo "</div>";
							break;
						case "saveEmpCarfleet" :
							echo "<div class='container starter-template'>";
							include("inc/saveEmpCarfleet.inc.php");
							echo "</div>";
							break;
						case "saveEmpBuilding" :
							echo "<div class='container starter-template'>";
							include("inc/saveEmpBuilding.inc.php");
							echo "</div>";
							break;
						case "groupsManager" :
							echo "<div class='container starter-template'>";
							include("inc/ppTools/groupsManager.inc.php");
							echo "</div>";
							break;
						case "usersManager" :
							echo "<div class='container starter-template'>";
							include("inc/ppTools/usersManager.inc.php");
							echo "</div>";
							break;

						// MANAGING
						case "functionsManagor":
							echo "<div class='container starter-template'>";
							include("inc/managing/functionsManagor.inc.php");
							echo "</div>";
							break;
						case "fileServerManagor":
							echo "<div class='container starter-template'>";
							include("inc/managing/fileServerManagor.inc.php");
							echo "</div>";
							break;
						case "departmentManagor":
							echo "<div class='container starter-template'>";
							include("inc/managing/departmentManagor.inc.php");
							echo "</div>";
							break;
						case "labelManagor":
							echo "<div class='container starter-template'>";
							include("inc/managing/labelManagor.inc.php");
							echo "</div>";
							break;
						case "labelEmailDomains" :
							echo "<div class='container starter-template'>";
							include("inc/managing/labelEmailDomains.inc.php");
							echo "</div>";
							break;
						case "securityAccessManagor" :
							echo "<div class='container starter-template'>";
							include("inc/managing/securityAccessManagor.inc.php");
							echo "</div>";
							break;
						case "contractsManagor" :
							echo "<div class='container starter-template'>";
							include("inc/managing/contractsManagor.inc.php");
							echo "</div>";
							break;

						// TESTS
						case "calendarTest" :
							echo "<div class='container starter-template'>";
							include("Tests/calendarTest.php");
							echo "</div>";
							break;

						// ********************
						default:
							echo "<div class='container starter-template'>";
							include("main.inc.php");
							echo "</div>";
							break;
					}
				} else {
					echo "<div class='container starter-template'>";
					include("main.inc.php");
					echo "</div>";
				}

			?>
			<!-- /.container -->

			</body>

			<footer>
				<div class="container">
					<p><br></p>
					<table class="table">
					<tr>
						<td>
							<address>
								<strong>TBWA</strong><br>
								Kroonlaan 165 avenue de la Couronne<br>
								B-1050 Brussels, Belgium<br>
								VAT BE0414.235.827<br>
							</address>
						</td>
						<td>
							<address>
								<strong>TEQUILA</strong><br>
								Kroonlaan 165 avenue de la Couronne<br>
								B-1050 Brussels, Belgium<br>
								VAT BE0455.891.783
							</address>
						</td>
						<td>
							<address>
								<strong>E-GRAPHICS</strong><br>
								Kroonlaan 165 avenue de la Couronne<br>
								B-1050 Brussels, Belgium<br>
								VAT BE0444.878.226
							</address>
						</td>
						<td>
							<address>
								<strong>MARKETING & ENTERTAINMENT</strong><br>
								Vorstermanstraat 14A<br>
								B-2000 Antwerp, Belgium<br>
								VAT BE0459.390.317
							</address>
						</td>
					</tr>
				</table>
					</div>
			</footer>
			</html>

		<?php
		} // end if (isset($_session[user]))
		else {
			include('signIn.inc.php');
		}

		?>
		<?php if ($localTest == 1) { ?>
			<p class="text-info"><img src="img/adOffline.png"/> Connecting to a local test server. </p>
		<?php } ?>



		<?php
		// Maintenance mode
	} else {
		session_start();
		session_destroy();
		echo "<link href='css/bootstrap.css' rel='stylesheet'>";
		echo "<meta http-equiv='refresh' content='6;url=index.php'>";
		// Maintenance mode message :
		echo "<h1 class='text-danger'>People Portal is under maintenance... Please come back later.</h1>";
	}