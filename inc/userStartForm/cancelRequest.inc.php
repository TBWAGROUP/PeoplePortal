<?php

	$idE = mysql_real_escape_string($_GET['idE']);
	$idCon = mysql_real_escape_string($_GET['idCon']);
	$result = mysql_query("
			SELECT * FROM employees AS emp
								INNER JOIN contracts AS cont ON cont.idCon = emp.contract
								INNER JOIN departments AS dep ON cont.idDep = dep.idDep
								INNER JOIN labels AS lab ON lab.idLab = cont.idLab
								INNER JOIN functions AS func ON func.idFunc = cont.idFunc
			WHERE emp.idE=$idE") or die(mysql_error());;
	$emp = mysql_fetch_array($result);
	if (!isset($_GET['delete'])) {
		?>

		<h4>Contract #<?php echo $emp["idCon"]; ?> for <?php echo $emp["firstname"] . " " . $emp["lastname"]; ?></h4>
		<p>
			Type : <?php echo $emp["empType"]; ?>
			<br/>Start date: <?php echo $emp["startDate"]; ?>
		<p class="text-danger">Are you sure to cancel this request?</p>
		<p>
			<a href="index.php" class="btn btn-default">NO</a>
			&nbsp;&nbsp;<a href="index.php?p=cancelRequest&idCon=<?php echo $idCon; ?>&idE=<?php echo $idE; ?>&delete" class="btn btn-default btn-xs">YES</a>
		</p>
	<?php
	} else if (isset($_GET["delete"])) {
		if (($currentIdE == $emp["requestor"]) || (memberOf($pp_hr)) || (memberOf($pp_admin))) {
			// ====================================================================
			// ====================================================================
			// ====================================================================
			// email notification
			$firstname = $emp["firstname"];
			$lastname = $emp["lastname"];
			$startDate = $emp["startDate"];
			$requestor = $emp["requestor"];

			if ($emp["validationStage"] == 1) {
				// hr mail
				require($_SERVER['DOCUMENT_ROOT'] . "/class/PHPMailer/class.phpmailer.php");
				require($_SERVER['DOCUMENT_ROOT'] . "/inc/userStartForm/mailNotificationsCancelRequest/mail.hr.php");
				require($_SERVER['DOCUMENT_ROOT'] . "/inc/userStartForm/mailNotificationsCancelRequest/mail.requestor.php");
			} else {
				// Always include
				require($_SERVER['DOCUMENT_ROOT'] . "/class/PHPMailer/class.phpmailer.php");
				require($_SERVER['DOCUMENT_ROOT'] . "/inc/userStartForm/mailNotificationsCancelRequest/mail.manager.php");
				require($_SERVER['DOCUMENT_ROOT'] . "/inc/userStartForm/mailNotificationsCancelRequest/mail.holidayApprover.php");
				require($_SERVER['DOCUMENT_ROOT'] . "/inc/userStartForm/mailNotificationsCancelRequest/mail.provisioning.admin.php");
				require($_SERVER['DOCUMENT_ROOT'] . "/inc/userStartForm/mailNotificationsCancelRequest/mail.provisioning.building.php");

				// Business Cards
				if ($emp["businessCardNeeded"] == "1") {
					require($_SERVER['DOCUMENT_ROOT'] . "/inc/userStartForm/mailNotificationsCancelRequest/mail.provisioning.building.businessCard.php");
				}

				// Reception and Picture
				if (($emp["empType"] == "Employee") || ($emp["empType"] == "Contractor timebased") || ($emp["empType"] == "Contractor fixed")) {
					require($_SERVER['DOCUMENT_ROOT'] . "/inc/userStartForm/mailNotificationsCancelRequest/mail.provisioning.reception.php"); // reception@tbwagroup.be
					require($_SERVER['DOCUMENT_ROOT'] . "/inc/userStartForm/mailNotificationsCancelRequest/mail.provisioning.picture.php"); // Danny
				}

				// Facebook and Car Fleet (only Perms)
				if (($emp["empType"] == "Employee") || ($emp["empType"] == "Contractor fixed")) {
					require($_SERVER['DOCUMENT_ROOT'] . "/inc/userStartForm/mailNotificationsCancelRequest/mail.provisioning.facebook.php");
					require($_SERVER['DOCUMENT_ROOT'] . "/inc/userStartForm/mailNotificationsCancelRequest/mail.provisioning.carfleet.php");
				}

				// Maconomy
				if ($emp["maconomy"] == "1") {
					require($_SERVER['DOCUMENT_ROOT'] . "/inc/userStartForm/mailNotificationsCancelRequest/mail.provisioning.finance.php");
				}
			}
			// email notification
			// ====================================================================
			// ====================================================================
			// ====================================================================

			// Deleting the contract from database
			mysql_query("DELETE FROM teamLeads WHERE contracts_idCon = \"$idCon\"") or die (mysql_error());
			mysql_query("DELETE FROM approvals WHERE idCon =\"$idCon\"") or die (mysql_error());
			mysql_query("DELETE FROM emailAliasesEmp WHERE idCon = \"$idCon\"") or die (mysql_error());
			mysql_query("DELETE FROM parking WHERE contracts_idCon = \"$idCon\"") or die (mysql_error());
			mysql_query("DELETE FROM contracts WHERE idCon = \"$idCon\"") or die (mysql_error());
			$queryCheckContracts = mysql_query("SELECT * FROM contracts WHERE idE = $idE") or die (mysql_error());
			if (mysql_num_rows($queryCheckContracts) == 0) // if no contracts for the user, remove the employee
			{
				mysql_query("DELETE FROM employeeGroup WHERE idE = $idE") or die (mysql_error());
				mysql_query("DELETE FROM teamLeads WHERE employees_idE = $idE") or die (mysql_error());
				mysql_query("DELETE FROM approvals WHERE idE =\"$idE\"") or die (mysql_error());
				mysql_query("DELETE FROM employees WHERE idE = \"$idE\"") or die (mysql_error());
			} else {
				echo "Others contracts found for this user. Keeped in database";
			}

			echo "<h3 class=\"text-success\">Request correctly cancelled</h3>";

			echo "<meta http-equiv='refresh' content='2;url=index.php'>";
		} else {
			echo "Oups, you are not the requetor of this request or you don't have the right to do that.";
		}
	}
?>
