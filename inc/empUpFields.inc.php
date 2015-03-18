<!--
This script pushes a single employee's info from PP to AD, only for admins


Included by:
inc/saveEmp.inc.php

Hrefs pointing here:

Requires:

Includes:

Form actions:

-->

<?php

	// PUSH from PP to AD

	// Connecting to LDAP (port : 389)
	$ldapconn = ldap_connect($server) or die("Could not connect to dc");
	ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);

	if ($ldapconn) {
		echo "<p><img src='img/ajax-loader.gif' /> Updating Active Directory's user information</p>";
		// binding anonymously
		$ldapbind = ldap_bind($ldapconn, $user, $pass);
		if ($ldapbind) {
			$firstGet = $_GET['first'];
			$nameGet = $_GET['name'];
			$sam = $_GET['upn'];
			$fullDN = getDN($ldapconn, $sam, $dnServer);
			$dn = $fullDN;
			$upnGet = $_GET["upn"] . "@ad.tbwagroup.be"; // current upn, to find the user
			$upnPost = $_POST["upn"] . "@ad.tbwagroup.be"; // new upn
			$sam = genSAMAD($_POST["upn"]); // keep SAM entry on AD (only) up to date when UPN is modified
			$cn = accentADCN($_POST['firstname']) . " " . accentADCN($_POST['lastname']);
			$ad['givenName'] = accentADCN($_POST['firstname']);
			$ad['sn'] = accentADCN($_POST['lastname']);
			if ($_POST['disableAccountDate'] != "") {
				$accountExpires = dateToAccountExpires($_POST['disableAccountDate']);
			} else {
				$accountExpires = 0;
			}
			$ad["accountExpires"] = $accountExpires;
			$ad["displayName"] = accentADCN($_POST['firstname']) . " " . accentADCN($_POST['lastname']);
			$ad['userPrincipalName'] = $upnPost;
			$ad['sAMAccountName'] = $sam; // ERROR
			if ($_POST['mobile'] != "") {
				$ad['mobile'] = $_POST['mobile'];
			} else {
				$ad['mobile'] = " ";
			}
			if ($_POST['phoneNumber'] != "") {
				$ad['telephoneNumber'] = $_POST['phoneNumber'];
			} else {
				$ad['telephoneNumber'] = " ";
			}
			if ($_POST['internalPhone'] != "") {
				$ad['ipPhone'] = $_POST['internalPhone'];
			} else {
				$ad['ipPhone'] = " ";
			}
			if ($_POST['primaryEmail'] != "") {
				$ad['mail'] = $_POST['primaryEmail'];
			} else {
				$ad['mail'] = " ";
			}
			$idFunc = $_POST['function'];
			$functionQuery = mysql_query("SELECT * FROM functions WHERE idFunc = $idFunc") or die (mysql_error());
			$functionList = mysql_fetch_array($functionQuery);
			$idDep = $_POST['department'];
			$depQuery = mysql_query("SELECT * FROM departments WHERE idDep = $idDep") or die (mysql_error());
			$depList = mysql_fetch_array($depQuery);
			$labelName = $_POST['label'];
			$labQuery = mysql_query("SELECT * FROM labels WHERE labelName = \"$labelName\"") or die (mysql_error());
			$companyCode = mysql_fetch_array($labQuery);
			$ad['title'] = $functionList['functionName'];
			$ad['department'] = $depList['nameDepartment'];
			$ad['company'] = $companyCode['companyCode'];

			// Updating infos to the AD entry
			$upDate = ldap_modify($ldapconn, $dn, $ad);
			if ($upDate) {
				echo "<h5 class='text-success'><img src='img/compOk.png' /> User " . $upnGet . " successfully updated in Active Directory. </h3>";
			} else {
				echo "<h5 class='text-danger'>Oops! Error while updating the user " . $upnGet . " in Active Directory.</h5>";
			}
			// Changing the Relative Distinguished Name (RDN)
			$dnLenght = strlen(getCN($fullDN)) + 4; // +4 : cn= ... ,
			$parentDN = substr($fullDN, $dnLenght); // full DN without the "cn=xxx," part
			$newName = "cn=" . accentADCN($_POST['firstname']) . " " . accentADCN($_POST['lastname']);
			$rename = ldap_rename($ldapconn, $dn, $newName, $parentDN, TRUE);
			if ($rename) {
				echo "Loading... <br />";
			} else {
				echo "Oops, problem when changing RDN";
			}
			// Checking Domain Admin
			$domainAdminGroupName = "Domain Admins"; // EN Win Serv Version
			// $domainAdminGroupName = "Admins du domaine"; // FR version
			$domainAdminGroupDN = "CN=$domainAdminGroupName, CN=Users, $dnServer";
			$groupMember['member'] = $dn;
			if ($_POST["itNetworkAdmin"] == 1) {
				// we check if the association as made in AD
				if (checkGroup($ldapconn, $dn, $domainAdminGroupDN)) // if the user is not on the group (checkgroup)
				{
					// if not, we had the user to the group on the AD
					if (ldap_mod_add($ldapconn, $domainAdminGroupDN, $groupMember)) {
						echo "<h6 class='text-success'><strong>added</strong> in " . $domainAdminGroupName . " on AD</h6>";
					}
				}
			} else {
				if (!checkGroup($ldapconn, $dn, $domainAdminGroupDN)) // if the user is on the group (!checkgroup)
				{
					// echo "delete from : ".$groupName."<br />";
					if (ldap_mod_del($ldapconn, $domainAdminGroupDN, $groupMember)) {
						echo "<h6 class='text-danger'><strong>deleted</strong> from " . $domainAdminGroupName . " on AD</h6>";
					} else {
						echo "<h6 class='text-danger'><strong>deleted</strong> from " . $domainAdminGroupName . " on AD</h6>";
					}
				}
			}
		}
		ldap_close($ldapconn);
	}
?>
