<!--
This script pushes a single employee's info from PP to AD, this won't work, finance users are not admin, neder called, see below included by

Included by:
inc/saveEmpFinance.inc.php (but commented out)

Hrefs pointing here:

Requires:

Includes:

Form actions:

-->

<?php

// PUSH from PP to AD

// Connecting to LDAP (port : 389)
$ldapconn = ldap_connect($server) or die("Could not connect to dc");
ldap_set_option ($ldapconn,LDAP_OPT_PROTOCOL_VERSION, 3);

if ($ldapconn) {
	echo "<p><img src='img/ajax-loader.gif' /> Updating Active Directory's user information</p>";
    // binding anonymously
    $ldapbind = ldap_bind($ldapconn, $user, $pass);
    if ($ldapbind) {
		$firstGet = $_GET['first'];
		$nameGet = $_GET['name'];
		$sam=$_GET['upn'];
		$fullDN = getDN($ldapconn, $sam, $dnServer);
		$dn  = $fullDN;
		$upnGet = $_GET["upn"]."@ad.tbwagroup.be"; // current upn, to find the user
		$sam = $_GET["upn"]; // keep SAM entry on AD (only) up to date when UPN is modified
		$idFunc = $_POST['function'];
			$functionQuery = mysql_query("SELECT * FROM functions WHERE idFunc = $idFunc") or die (mysql_error()) ;
			$functionList = mysql_fetch_array($functionQuery);
		$idDep = $_POST['department'];
			$depQuery = mysql_query("SELECT * FROM departments WHERE idDep = $idDep") or die (mysql_error()) ;
			$depList = mysql_fetch_array($depQuery);
		$ad['title'] = $functionList['functionName'];
		$ad['department'] = $depList['nameDepartment'];
		$ad['company'] = $_POST['label'];
		
		// Updating infos to the AD entry
		$upDate = ldap_modify($ldapconn, $dn, $ad);
		if ($upDate) {
			echo "<h5 class='text-success'><img src='img/compOk.png' /> User ".$upnGet." successfully updated in Active Directory. </h3>";
		} else {
			echo "<h5 class='text-danger'>Oops! Error while updating the user ".$upnGet." in Active Directory.</h5>";
		}
	}
	ldap_close ($ldapconn);		
}
?>
