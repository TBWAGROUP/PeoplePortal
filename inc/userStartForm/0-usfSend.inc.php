<?php
	/**
	 * VALIDATIONSTAGE = 1 (HR Approver)
	 **/


	// fields variables association
	require("inc/userStartForm/fields.inc.php");


	if (($empType == "Freelance") || ($empType == "Intern")) {
		$validationStage = 2;
		echo "Your request was accepted and an email was sent to provisioning";
	} else {
		$validationStage = 1;
		echo "Your request was accepted and an email was sent to HR for approval";
	}
	$ppConAddDate = date("d/m/Y H:i");


	// associate ID <-> NAME
	$idLabQuery = mysql_query("SELECT idLab FROM labels WHERE labelName = \"$label\" ");
	$idLab = array_shift(mysql_fetch_array($idLabQuery));

	$idDepQuery = mysql_query("SELECT idDep FROM departments WHERE nameDepartment = \"$department\" ");
	$idDep = array_shift(mysql_fetch_array($idDepQuery));

	$idFuncQuery = mysql_query("SELECT idFunc FROM functions WHERE functionName = \"$function\" ");
	$idFunc = array_shift(mysql_fetch_array($idFuncQuery));

	/** DATABASE INSERT
	 **/
	// check if this employee exist
	$query = mysql_query("SELECT idE FROM employees WHERE upn = \"$upn\" ") or die(mysql_error());
	$queryNbrUser = mysql_num_rows($query);

	if ($queryNbrUser == 0) {
		mysql_query("INSERT INTO employees (ppAccountStatut, firstname, lastname, language, address, birthdate, upn, mobile, contactEmergency, ppAddDate)
								VALUES (5, \"$firstname\", \"$lastname\", \"$language\", \"$address\", \"$birthdate\", \"$upn\", \"$mobile\", \"$contactEmergency\", \"$ppAddDate\" )") or die(mysql_error());
		$idE1 = mysql_query("SELECT idE FROM employees WHERE idE = LAST_INSERT_ID();");
		$idEemp = array_shift(mysql_fetch_array($idE1));

		// contract creation
		mysql_query("INSERT INTO contracts
			(
			validationStage, requestor, idE, idFunc, idDep, idLab, startDate, endDate, startDateTS, endDateTS,
			timeRegime, primaryEmail, empType, internalPhone, fileserver, vpn, timesheets,
			timesheetblocking, workstation, 3gData, financeJobCost, financePurchaseOrders,
			financePayroll, financeAccountsPayable, financeInvoicing, financeAccountReceivable,
			financeGeneralLedger,financeFixedAssets, financeHR,
			itComputerAdmin, itNetworkAdmin, ppConAddDate, businessCardNeeded, note, financePayrollAccess, kensingtonLockNr, mobilePhoneModel,
			badgeNr, createdFb, maconomy,
			operationalEndDate, materialReturnDate,  mobilePhoneReturnDate, carReturnDate, disableAccountDate,
			operationalEndDateTS, materialReturnDateTS,  mobilePhoneReturnDateTS, carReturnDateTS, disableAccountDateTS
			)
			VALUES
			(
			\"$validationStage\", \"$currentIdE\", \"$idEemp\", \"$idFunc\", \"$idDep\", \"$idLab\", \"$startDate\", \"$endDate\", \"$startDateTS\", \"$endDateTS\",
			\"$timeRegime\", \"$primaryEmail\", \"$empType\", \"$internalPhone\", \"$fileServer\", \"$vpn\", \"$timesheets\",
			\"$timeSheetBlocking\", \"$workstation\", \"$_3Gdata\", \"$financeJobCost\", \"$financePurchaseOrders\",
			\"$financePayroll\", \"$financeAccountsPayable\", \"$financeInvoicing\", \"$financeAccountReceivable\",
			\"$financeGeneralLedger\", \"$financeFixedAssets\", \"$financeHR\",
			\"$itComputerAdmin\", \"$itNetworkAdmin\", \"$ppConAddDate\", \"$businessCardNeeded\", \"$note\", \"$financePayrollAccess\", \"$kensingtonLockNr\", \"$mobilePhoneModel\",
			\"$badgeNr\", \"$createdFb\", \"$maconomy\",
			\"$endDate\", \"$endDate\", \"$endDate\", \"$endDate\", \"$endDate\",
			\"$endDateTS\", \"$endDateTS\", \"$endDateTS\", \"$endDateTS\", \"$endDateTS\"
			)
			") or die(mysql_error());

		$idCon1 = mysql_query("SELECT idCon FROM contracts WHERE idCon = LAST_INSERT_ID();");
		$idCon = array_shift(mysql_fetch_array($idCon1));

		mysql_query("UPDATE employees SET contract = $idCon WHERE idE = $idEemp") or die(mysql_error());
		echo "<h4 class='text-info'>Request sent to HR.</h3>";
	} else {
		$idEemp = array_shift(mysql_fetch_array($query));
		// contract creation
		mysql_query("INSERT INTO contracts
			(
			validationStage, requestor, idE, idFunc, idDep, idLab, startDate, endDate, startDateTS, endDateTS,
			timeRegime, primaryEmail, empType, internalPhone, fileserver, vpn, timesheets,
			timesheetblocking, workstation, 3gData, financeJobCost, financePurchaseOrders,
			financePayroll, financeAccountsPayable, financeInvoicing, financeAccountReceivable,
			financeGeneralLedger,financeFixedAssets, financeHR,
			itComputerAdmin, itNetworkAdmin, ppConAddDate, businessCardNeeded, note, financePayrollAccess, kensingtonLockNr, mobilePhoneModel,
			badgeNr, createdFb, maconomy,
			operationalEndDate, materialReturnDate,  mobilePhoneReturnDate, carReturnDate, disableAccountDate,
			operationalEndDateTS, materialReturnDateTS,  mobilePhoneReturnDateTS, carReturnDateTS, disableAccountDateTS
			)
			VALUES
			(
			\"$validationStage\", \"$currentIdE\", \"$idEemp\", \"$idFunc\", \"$idDep\", \"$idLab\", \"$startDate\", \"$endDate\", \"$startDateTS\", \"$endDateTS\",
			\"$timeRegime\", \"$primaryEmail\", \"$empType\", \"$internalPhone\", \"$fileServer\", \"$vpn\", \"$timesheets\",
			\"$timeSheetBlocking\", \"$workstation\", \"$_3Gdata\", \"$financeJobCost\", \"$financePurchaseOrders\",
			\"$financePayroll\", \"$financeAccountsPayable\", \"$financeInvoicing\", \"$financeAccountReceivable\",
			\"$financeGeneralLedger\", \"$financeFixedAssets\", \"$financeHR\",
			\"$itComputerAdmin\", \"$itNetworkAdmin\", \"$ppConAddDate\", \"$businessCardNeeded\", \"$note\", \"$financePayrollAccess\", \"$kensingtonLockNr\", \"$mobilePhoneModel\",
			\"$badgeNr\", \"$createdFb\", \"$maconomy\",
			\"$endDate\", \"$endDate\", \"$endDate\", \"$endDate\", \"$endDate\",
			\"$endDateTS\", \"$endDateTS\", \"$endDateTS\", \"$endDateTS\", \"$endDateTS\"
			)
			") or die(mysql_error());

		$idCon1 = mysql_query("SELECT idCon FROM contracts WHERE idCon = LAST_INSERT_ID();");
		$idCon = array_shift(mysql_fetch_array($idCon1));

		mysql_query("UPDATE employees SET contract = $idCon WHERE idE = $idEemp") or die(mysql_error());
		echo "<h4 class='text-info'>User exist. A new contract had just added for this employee and made it like his current contract.</h3>";
	}


	// adding email aliases
	if (!empty($_POST['emailAliases'])) {
		foreach ($_POST['emailAliases'] as $checkEmailAliases) {
			mysql_query("INSERT INTO emailAliasesEmp (idCon, idAliase) VALUES (\"$idCon\", \"$checkEmailAliases\")") or die(mysql_error());
		}
	}
	if (isset($_POST['emailDL'])) {
		// adding email distibution list (MD groups)
		if (!empty($_POST['emailDL'])) {
			foreach ($_POST['emailDL'] as $checkEmailDL) {
				mysql_query("INSERT INTO employeeGroup (idE, idGroup) VALUES (\"$idEemp\", \"$checkEmailDL\")") or die(mysql_error());
			}
		}
	}
	// adding teamleads
	mysql_query("INSERT INTO teamLeads (employees_idE, contracts_idCon) VALUES (\"$manager\", \"$idCon\" )") or die(mysql_error());


	// adding holiday approvers
	if (!empty($_POST['holidayApp'])) {
		foreach ($_POST['holidayApp'] as $checkHA) {
			mysql_query("INSERT INTO teamLeads (employees_idE, contracts_idCon, appType) VALUES (\"$checkHA\", \"$idCon\", 1 )") or die(mysql_error());
		}
	}


	/** SEND EMAIL ALERT TO HR HERE
	 **/
	$requestor = $currentUser['primaryEmail'];
	if ($validationStage == 1) {
		require($_SERVER['DOCUMENT_ROOT'] . "/class/PHPMailer/class.phpmailer.php");
		require($_SERVER['DOCUMENT_ROOT'] . "/inc/userStartForm/mailNotifications/mail.hr.php");
		require($_SERVER['DOCUMENT_ROOT'] . "/inc/userStartForm/mailNotifications/mail.requestor.php");
	}
	if ($validationStage == 2) {
		require($_SERVER['DOCUMENT_ROOT'] . "/class/PHPMailer/class.phpmailer.php");
		$hrByPass = 1;
		require($_SERVER['DOCUMENT_ROOT'] . "/inc/userStartForm/mailNotifications/mail.provisioning.all.php");
	}
	echo "<meta http-equiv='refresh' content='2;url=index.php'></h4><img src='img/ajax-loader.gif' />";

?>
