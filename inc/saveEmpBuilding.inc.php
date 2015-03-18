<!--
This script saves employee info to DB (NOT enough privs for non-admins to then calls empUpFieldsFinance.inc.php to update AD user record)

Included by:
index.php (case ?p=saveEmpCarfleet)

Hrefs pointing here:

Requires:

Includes:
empUpFieldsCarfleet.inc.php (commented out)

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


				// employee update

				mysql_query("UPDATE employees SET
									contactEmergency = \"$_POST[contactEmergency]\",
									mobile = \"$_POST[mobile]\"
						WHERE idE = \"$idE\"
			") or die(mysql_error());

				// contract update

				mysql_query("UPDATE contracts SET
							internalPhone=\"$_POST[internalPhone]\",
							phoneNumber=\"$_POST[phoneNumber]\",
							parkingRemarks=\"$_POST[parkingRemarks]\",
							badgeNr=\"$_POST[badgeNr]\",
							badgeAccessLevel=\"$_POST[badgeAccessLevel]\"
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
