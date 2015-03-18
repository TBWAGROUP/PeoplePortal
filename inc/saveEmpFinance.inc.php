<!--
This script saves employee info to DB (NOT enough privs for non-admins to then calls empUpFieldsFinance.inc.php to update AD user record)

Included by:
index.php (case ?p=saveEmpFinance)

Hrefs pointing here:

Requires:

Includes:
empUpFieldsFinance.inc.php (commented out)

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

				// employee update

				mysql_query("UPDATE employees SET
								holidayApp = \"$_POST[holidayApp]\"
						WHERE idE = \"$idE\"
				") or die(mysql_error());

				// contract update

				mysql_query("UPDATE contracts SET
							jobPortalRole = \"$_POST[jobPortalRole]\",
							timesheets= \"$timesheets\",
							timesheetblocking= \"$timeSheetBlocking\",
							financeJobCost = \"$financeJobCost\",
							financeAccountsPayable = \"$financeAccountsPayable\",
							financeAccountReceivable=\"$financeAccountReceivable\",
							financeFixedAssets=\"$financeFixedAssets\",
							financePurchaseOrders=\"$financePurchaseOrders\",
							financeInvoicing=\"$financeInvoicing\",
							financeGeneralLedger=\"$financeGeneralLedger\",
							financeHR=\"$financeHR\",
							note= \"$_POST[note]\"
						WHERE idCon = \"$idCon\"
						") or die(mysql_error());


				echo "<img src='img/ajax-loader.gif' /> Updating informations. Redirecting...<meta http-equiv='refresh' content='0;url=index.php?p=emp&idE=$idE&idCon=$idCon&upn=$upn'>";
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
