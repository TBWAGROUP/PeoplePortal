<?php
	if ($hrByPass == 1) // take employees info from the form (POST)
	{
		$emp["firstname"] = $firstname;
		$emp["lastname"] = $lastname;
		$emp["startDate"] = $startDate;
		$emp["endDate"] = $endDate;
		$emp["requestor"] = $requestor;
		$emp["nameDepartment"] = $department;
		$emp["labelName"] = $labelName;
		$emp["functionName"] = $function;
		$emp["empType"] = $empType;
	}

	// Always include
	require($_SERVER['DOCUMENT_ROOT'] . "/inc/userStartForm/mailNotifications/mail.manager.php");
	require($_SERVER['DOCUMENT_ROOT'] . "/inc/userStartForm/mailNotifications/mail.holidayApprover.php");
	require($_SERVER['DOCUMENT_ROOT'] . "/inc/userStartForm/mailNotifications/mail.provisioning.admin.php");
	require($_SERVER['DOCUMENT_ROOT'] . "/inc/userStartForm/mailNotifications/mail.provisioning.building.php");


	// Business cards
	if ($emp["businessCardNeeded"] == "1") {
		require($_SERVER['DOCUMENT_ROOT'] . "/inc/userStartForm/mailNotifications/mail.provisioning.building.businessCard.php");
	}


	// Reception and Picture
	if (($emp["empType"] == "Employee") || ($emp["empType"] == "Contractor timebased") || ($emp["empType"] == "Contractor fixed")) {
		require($_SERVER['DOCUMENT_ROOT'] . "/inc/userStartForm/mailNotifications/mail.provisioning.reception.php"); // reception@tbwagroup.be
		require($_SERVER['DOCUMENT_ROOT'] . "/inc/userStartForm/mailNotifications/mail.provisioning.picture.php"); // Danny
	}

	// Facebook and Car Fleet (only Perms)
	if (($emp["empType"] == "Employee") || ($emp["empType"] == "Contractor fixed")) {
		require($_SERVER['DOCUMENT_ROOT'] . "/inc/userStartForm/mailNotifications/mail.provisioning.facebook.php");
		require($_SERVER['DOCUMENT_ROOT'] . "/inc/userStartForm/mailNotifications/mail.provisioning.carfleet.php");
	}


	// Maconomy
	if ($emp["maconomy"] == "1") {
		require($_SERVER['DOCUMENT_ROOT'] . "/inc/userStartForm/mailNotifications/mail.provisioning.finance.php");
	}
?>
