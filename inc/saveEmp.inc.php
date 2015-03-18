<!--
This script saves employee info to DB and then calls empUpFields.inc.php to update AD user record

Included by:
index.php (case ?p=saveEmp)

Hrefs pointing here:

Requires:

Includes:
empUpFields.inc.php

Form actions:

-->

<?php
	if (isset($_GET['idE'])) {
		$idE = $_GET['idE'];
		if (isset($_GET['idCon'])) {
			$idCon = $_GET['idCon'];
			if (isset($_GET['upn'])) {
				$upn = $_GET['upn'];
				$idE = $_GET['idE'];
				$idCon = $_GET['idCon'];
				if (!empty($_POST['vpn'])) {
					$vpn = TRUE;
				} else {
					$vpn = FALSE;
				}
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
				if (!empty($_POST['timesheets'])) {
					$timesheets = TRUE;
				} else {
					$timesheets = FALSE;
				}
				if (!empty($_POST['timesheetblocking'])) {
					$timeSheetBlocking = TRUE;
				} else {
					$timeSheetBlocking = FALSE;
				}
				if (!empty($_POST['essCreated'])) {
					$essCreated = TRUE;
				} else {
					$essCreated = FALSE;
				}
				if ($_POST["startDate"] != "") {
					$startDateTS = convertTimestamp($_POST["startDate"]);
				} else {
					$startDateTS = "";
				}
				if ($_POST['endDate'] != "") {
					$endDateTS = convertTimestamp($_POST['endDate']);
				} else {
					$endDateTS = "";
				}
				if ($_POST["operationalEndDate"] != "") {
					$operationalEndDateTS = convertTimestamp($_POST["operationalEndDate"]);
				} else {
					$operationalEndDateTS = "";
				}
				if ($_POST["disableAccountDate"] != "") {
					$disableAccountDateTS = convertTimestamp($_POST["disableAccountDate"]);
				} else {
					$disableAccountDateTS = "";
				}
				if ($_POST["materialReturnDate"] != "") {
					$materialReturnDateTS = convertTimestamp($_POST["materialReturnDate"]);
				} else {
					$materialReturnDateTS = "";
				}
				if ($_POST["mobilePhoneReturnDate"] != "") {
					$mobilePhoneReturnDateTS = convertTimestamp($_POST["mobilePhoneReturnDate"]);
				} else {
					$mobilePhoneReturnDateTS = "";
				}
				if ($_POST["carReturnDate"] != "") {
					$carReturnDateTS = convertTimestamp($_POST["carReturnDate"]);
				} else {
					$carReturnDateTS = "";
				}
				if ($_POST["birthdate"] != "") {
					$birthdateTS = convertTimestamp($_POST["birthdate"]);
				} else {
					$birthateTS = "";
				}

				// employee update

				mysql_query("UPDATE employees SET
									firstname = \"$_POST[firstname]\",
									lastname = \"$_POST[lastname]\",
									language = \"$_POST[language]\",
									contactEmergency = \"$_POST[contactEmergency]\",
									address = \"$_POST[address]\",
									birthdate = \"$_POST[birthdate]\",
									birthdateTS = \"$birthdateTS\",
									mobile = \"$_POST[mobile]\",
									teamLead = \"$_POST[teamLead]\",
									holidayApp = \"$_POST[holidayApp]\",
									upn = \"$_POST[upn]\"
						WHERE idE = \"$idE\"
			") or die(mysql_error());

				// contract update

				$idLabQuery = mysql_query("SELECT idLab FROM labels WHERE labelName = \"$_POST[label]\" ");
				$label = array_shift(mysql_fetch_array($idLabQuery));

				mysql_query("UPDATE contracts SET
							startDate=\"$_POST[startDate]\",
							endDate=\"$_POST[endDate]\",
							operationalEndDate= \"$_POST[operationalEndDate]\",
							disableAccountDate= \"$_POST[disableAccountDate]\",
							materialReturnDate = \"$_POST[materialReturnDate]\",
							mobilePhoneReturnDate = \"$_POST[mobilePhoneReturnDate]\",
							carReturnDate = \"$_POST[carReturnDate]\",
							startDateTS=\"$startDateTS\",
							endDateTS=\"$endDateTS\",
							operationalEndDateTS=\"$operationalEndDateTS\",
							disableAccountDateTS=\"$disableAccountDateTS\",
							materialReturnDateTS=\"$materialReturnDateTS\",
							mobilePhoneReturnDateTS=\"$mobilePhoneReturnDateTS\",
							carReturnDateTS=\"$carReturnDateTS\",
							financePayroll = \"$_POST[payroll]\",
							idFunc=\"$_POST[function]\",
							idDep=\"$_POST[department]\",
							idLab=\"$label\",
							vpn=\"$vpn\",
							timesheets= \"$timesheets\",
							timesheetblocking= \"$timeSheetBlocking\",
							jobPortalRole = \"$_POST[jobPortalRole]\",
							financeJobCost = \"$financeJobCost\",
							financeAccountsPayable = \"$financeAccountsPayable\",
							financeAccountReceivable=\"$financeAccountReceivable\",
							financeFixedAssets=\"$financeFixedAssets\",
							financePurchaseOrders=\"$financePurchaseOrders\",
							financeInvoicing=\"$financeInvoicing\",
							financeGeneralLedger=\"$financeGeneralLedger\",
							financeHR=\"$financeHR\",
							note=\"$_POST[note]\",
							bio=\"$_POST[bio]\",
							empType=\"$_POST[empType]\",
							timeRegime=\"$_POST[timeRegime]\",
							timeRegimeRmk=\"$_POST[timeRegimeRmk]\",
							mobilePhoneSub=\"$_POST[mobilePhoneSub]\",
							mobilePhoneSubRmk=\"$_POST[mobilePhoneSubRmk]\",
							mobilePhoneModel=\"$_POST[mobilePhoneModel]\",
							mobilePhoneOwner=\"$_POST[mobilePhoneOwner]\",
							3gData=\"$_POST[tGdata]\",
							mobilePhonePurchaseDate=\"$_POST[mobilePhonePurchaseDate]\",
							primaryEmail=\"$_POST[primaryEmail]\",
							internalPhone=\"$_POST[internalPhone]\",
							phoneNumber=\"$_POST[phoneNumber]\",
							workstation=\"$_POST[workstation]\",
							softwareNeeded=\"$_POST[softwareNeeded]\",
							softwareSpecialRequest=\"$_POST[softwareSpecialRequest]\",
							parkingRemarks=\"$_POST[parkingRemarks]\",
							badgeNr=\"$_POST[badgeNr]\",
							badgeAccessLevel=\"$_POST[badgeAccessLevel]\",
							kensingtonLockNr=\"$_POST[kensingtonLockNr]\",
							businessCardNeeded=\"$_POST[businessCardNeeded]\",
							emailSignatureLogo=\"$_POST[emailSignatureLogo]\",
							itOtherHardware=\"$_POST[itOtherHardware]\",
							itNetworkAdmin=\"$_POST[itNetworkAdmin]\",
							essCreated=\"$essCreated\"
						WHERE idCon = \"$idCon\"
											") or die(mysql_error());

				// changing fil servers
				// changer le groupeStatut à 1
				mysql_query("UPDATE contFileServers SET fileServDelete = 1 WHERE idCon = $idCon;");
				if (!empty($_POST['fileServer'])) {
					foreach ($_POST['fileServer'] as $check) {
						$checkGroup = mysql_query("SELECT * FROM contFileServers WHERE idCon = $idCon AND idServ = $check") or die (mysql_error());
						if (mysql_num_rows($checkGroup) == 0) {
							mysql_query("INSERT INTO contFileServers (idCon, idServ) VALUES($idCon, $check)") or die (mysql_error()); // and recreate them here
						} else {
							// si le groupe est déjà associé, repasser groupStatut à 0
							mysql_query("UPDATE contFileServers SET fileServDelete = 0 WHERE idCon = $idCon AND idServ = $check;");
						}
					}
				}
				mysql_query("DELETE FROM contFileServers WHERE idCon = $idCon AND fileServDelete = 1") or die (mysql_error());
				// PUSH data to ad (if the user has been validated and so, user exist in AD)
				$getEmp = mysql_query("
                        SELECT * FROM employees AS emp
                                                                INNER JOIN contracts AS cont ON cont.idCon = emp.contract
                                                                INNER JOIN departments AS dep ON cont.idDep = dep.idDep
                                                                INNER JOIN labels AS lab ON lab.idLab = cont.idLab
                                                                INNER JOIN functions AS func ON func.idFunc = cont.idFunc
                        WHERE emp.idE=$idE") or die(mysql_error());;
				$emp = mysql_fetch_array($getEmp);
				if (($currentUser['itNetworkAdmin'] == 1) && ($emp["objectsid"] != "")) {
					include("empUpFields.inc.php");
				}
				$objectsid = $_GET['objectsid'];
				echo "<img src='img/ajax-loader.gif' /> Updating informations. Redirecting...<meta http-equiv='refresh' content='2;url=index.php?p=emp&idE=$idE&idCon=$idCon&upn=$upn&objectsid=$objectsid'>";
			} // end if isset upn
			else {
				echo "Error. No UPN found";
			}
		} // end if isset idCon
		else {
			echo "Error. No contract ID found";
		}
	} // end if isset idE
	else {
		echo "Error. No employee ID found";
	}
?>
