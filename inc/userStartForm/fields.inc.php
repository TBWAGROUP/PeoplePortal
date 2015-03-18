<?php


	// *******************************************
	// employees table fields
	// *******************************************

	$firstname = $_POST['firstnamePF'];
	$lastname = $_POST['lastnamePF'];
	$language = $_POST['language'];
	$address = $_POST['address'];
	$birthdate = $_POST['birthdate'];
	$upn = $_POST['loginAD'];
	$mobile = $_POST['mobilephone'];
	$contactEmergency = $_POST['emergencyContact'];

	// *******************************************
	// contracts table fields
	// *******************************************

	$function = $_POST['function'];
	$empType = $_POST['type'];
	if (($empType == "Employee") || ($empType == "Contractor timebased") || ($empType == "Contractor fixed")) {
		$createdFb = 0;
	} else {
		$createdFb = 2;
	}
	$department = $_POST['department'];
	$label = $_POST['label'];
	$startDate = $_POST['startDate'];
	if (isset ($_POST['endDate'])) {
		$endDate = $_POST['endDate'];
	} else {
		$endDate = "";
	}
	if ($startDate != "") {
		$startDateTS = convertTimestamp($startDate);
	} else {
		$startDateTS = "";
	}
	if (isset ($_POST['endDate'])) {
		if ($_POST['endDate'] != "") {
			$endDateTS = convertTimestamp($endDate);
		} else {
			$endDateTS = "";
		}
	} else {
		$endDateTS = "";
	}
	$timeRegime = $_POST['timeRegime'];
	$primaryEmail = $_POST['primaryEmail'];
	$otherEmail = $_POST['email'];
	$workstation = $_POST['wsType'];

	$transportEntry = $_POST['transportEntry'];
	$note = $_POST['note'];

	if (!empty($_POST['businessCardNeeded'])) {
		$businessCard = TRUE;
	} else {
		$businessCard = FALSE;
	}
	$badgeNr = $_POST['badgeNr'];

	if (!empty($_POST['maconomy'])) {
		$maconomy = 1;
	} else {
		$maconomy = 0;
	}
	if (!empty($_POST['financePayrollAccess'])) {
		$financePayrollAccess = 1;
	} else {
		$financePayrollAccess = 0;
	}
	if (!empty($_POST['fileserver'])) {
		$fileServer = TRUE;
	} else {
		$fileServer = FALSE;
	}
	if (!empty($_POST['vpn'])) {
		$vpn = TRUE;
	} else {
		$vpn = FALSE;
	}
	if (!empty($_POST['timesheets'])) {
		$timesheets = TRUE;
	} else {
		$timesheets = FALSE;
	}
	if (!empty($_POST['timesheetsblocking'])) {
		$timeSheetBlocking = TRUE;
	} else {
		$timeSheetBlocking = FALSE;
	}
	if (!empty($_POST['internalphone'])) {
		$internalPhone = TRUE;
	} else {
		$internalPhone = FALSE;
	}
	if (!empty($_POST['3Gdata'])) {
		$_3Gdata = TRUE;
	} else {
		$_3Gdata = FALSE;
	}
	$_3Gdata = $_POST['3Gdata'];
	$kensingtonLockNr = $_POST['kensingtonLockNr'];
	$mobilePhoneModel = $_POST['mobilePhoneModel'];

	// team lead
	$manager = $_POST["managerFinal"];

	// IT fields
	if (!empty($_POST['computeradmin'])) {
		$itComputerAdmin = TRUE;
	} else {
		$itComputerAdmin = FALSE;
	}
	if (!empty($_POST['networkadmin'])) {
		$itNetworkAdmin = TRUE;
	} else {
		$itNetworkAdmin = FALSE;
	}

	// finance fields
	if (!empty($_POST['jobcost'])) {
		$financeJobCost = TRUE;
	} else {
		$financeJobCost = FALSE;
	}
	if (!empty($_POST['accountspayable'])) {
		$financeAccountsPayable = TRUE;
	} else {
		$financeAccountsPayable = FALSE;
	}
	if (!empty($_POST['accountsreceivable'])) {
		$financeAccountReceivable = TRUE;
	} else {
		$financeAccountReceivable = FALSE;
	}
	if (!empty($_POST['fixedassets'])) {
		$financeFixedAssets = TRUE;
	} else {
		$financeFixedAssets = FALSE;
	}
	if (!empty($_POST['purchaseorders'])) {
		$financePurchaseOrders = TRUE;
	} else {
		$financePurchaseOrders = FALSE;
	}
	if (!empty($_POST['invoicing'])) {
		$financeInvoicing = TRUE;
	} else {
		$financeInvoicing = FALSE;
	}
	if (!empty($_POST['generalledger'])) {
		$financeGeneralLedger = TRUE;
	} else {
		$financeGeneralLedger = FALSE;
	}
	if (!empty($_POST['hr'])) {
		$financeHR = TRUE;
	} else {
		$financeHR = FALSE;
	}
	$financePayroll = $_POST['payroll'];

	$ppAddDate = date("Y-m-d h:i:s");
?>