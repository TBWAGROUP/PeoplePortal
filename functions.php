<!--
This script defines some functions used elsewhere

Included by:
index.php
login.inc.php
signin.inc.php

Hrefs pointing here:

Requires:

Includes:
func.gCalendar.php
groupsName.conf.php

Form actions:

-->

<?php


// used for genering the UPN
function stripAccents($string) 
{
	return str_replace( array('à','á','â','ã','ä', 'ç', 'è','é','ê','ë', 'ì','í','î','ï', 'ñ', 'ò','ó','ô','õ','ö', 'ù','ú','û','ü', 'ý','ÿ', 'À','Á','Â','Ã','Ä', 'Ç', 'È','É','Ê','Ë', 'Ì','Í','Î','Ï', 'Ñ', 'Ò','Ó','Ô','Õ','Ö', 'Ù','Ú','Û','Ü', 'Ý'), 
	array('a','a','a','a','a', 'c', 'e','e','e','e', 'i','i','i','i', 'n', 'o','o','o','o','o', 'u','u','u','u', 'y','y', 'A','A','A','A','A', 'C', 'E','E','E','E', 'I','I','I','I', 'N', 'O','O','O','O','O', 'U','U','U','U', 'Y'), $string);
}

 
 
// test if a connected user is a member of a group
function memberOf ($groupName)
{
	$test = 0;
	foreach ($_SESSION['securityGroup'] AS $group)
	{
		if ($groupName == $group) { 
			$test++; 
		} 
	}
	
	if ($test > 0) { 
		return TRUE; 
	} 
	else { 
		return FALSE; 
	}
}

function memberOfNAOnly()
{
	include ('groupsName.conf.php');
	if ( (memberOf ($pp_admin) || (memberOf ($pp_finance) || (memberOf ($pp_hr)) || (memberOf ($pp_planning)) || (memberOf ($pp_hr)) || (memberOf ($pp_building))) ) ) 
		return FALSE;
	else
		return TRUE;
}

function approvalUp ($userIdE, $userIdGroup, $approvIdConUrl)
{
	// approvals infos
	$queryGroupStatut = mysql_query("SELECT * FROM approvals WHERE idGroup = \"$userIdGroup\" AND idCon = \"$approvIdConUrl\"") or die (mysql_error());
	$checkApprov = mysql_num_rows($queryGroupStatut );

	$approveDate = date("d/m/Y H:i");
	$approveDateTS = date("YmdHi");
	if ($checkApprov > 0) 
		mysql_query("UPDATE approvals SET statut = 1, approveDate=\"$approveDate\", approveDateTS=\"$approveDateTS\", idE = \"$userIdE\" WHERE idGroup = \"$userIdGroup\" AND  idCon = \"$approvIdConUrl\"") or die (mysql_error());
	else 
		mysql_query("INSERT INTO approvals (idGroup, idCon, statut, approveDate, approveDateTS, idE) VALUES ($userIdGroup, $approvIdConUrl, 1, \"$approveDate\", \"$approveDateTS\", $userIdE)") or die (mysql_error());
}

// genering the mainView name page for a group
function genMainView($groupName)
{
		// genering UPN
		$groupName = stripAccents($groupName); // accents
		$groupName = str_replace(" ","_", $groupName);// replace the " " by "_"
		$groupName = strtolower($groupName); 
		return $groupName;
}



// show OU until x levels
function showOU ($levels, $cn)
{
	for($i = $levels ; $i >= 1  ; $i--)
	{	
		if (isset($cn[$i]))
		{
			if (($cn[$i] == "ad") || ($cn[$i] == "tbwagroup") || ($cn[$i] == "be") ) {}
			else
			{ 
				echo $cn[$i];
				if ($i > 1) { echo "/"; }
			};
		}
	}
}

// Genering the OU field
function addToOU ($levels, $cn)
{
	for($i = 0 ; $i < $levels  ; $i++)
	{	
		if (isset($cn[$i]))
		{
			if (($cn[$i] == "ad") || ($cn[$i] == "tbwagroup") || ($cn[$i] == "be") || ($cn[$i] == "Employees") ) {}
			else
			{ 
				//echo $cn[$i];
				if ($i < $levels) { echo ",OU=".$cn[$i]; }
			};
		}
	}
}

 
 
// extract the company code from the OU field
// ,OU=03032000 TBWAGROUP Administrators,OU=03032000 TBWAGROUP --> 03032000
function company ($ou)
{
	$pattern = "([0-9]{4})";
	$matches = array();
	if ( preg_match($pattern,$ou,$matches) ) 
	{
		$code= $matches[0];
	}
}

// genering UPN by using the first and lastname
function genEmailName ($firstname,$lastname)
	{
		// genering UPN
		$emailName = $firstname.".".$lastname;
		$emailName = stripAccents($emailName); // accents
		$emailName = str_replace(" ",".", $emailName);// replace the " " by "."
		$emailName = str_replace("'","", $emailName);// replace the "'" by ""
		$emailName = strtolower($emailName);

		return $emailName;
	}
function genUpn ($firstname,$lastname)
	{
		// genering UPN
		$upn = $firstname.".".$lastname;
		$upn = stripAccents($upn); // accents
		$upn = str_replace(" ",".", $upn);// replace the " " by "."
		$upn = str_replace("'","", $upn);// replace the "'" by ""
		$upn = substr($upn, 0, 20); // cut after 20 chars
		$upn = strtolower($upn);

		return $upn;
	}
function genUpnCN ($fullName)
{
		//20 char max
		// genering UPN
		$fullName = stripAccents($fullName); // accents
		$fullName = str_replace(" ",".", $fullName);// replace the " " by "."
		$fullName = str_replace("'","", $fullName);// replace the "'" by ""
		$fullName = strtolower($fullName); 
		
		return $fullName;
}
function genSAMAD ($fullName)
{
		//20 char max
		// genering UPN
		$fullName = stripAccents($fullName); // accents
		$fullName = str_replace(" ",".", $fullName);// replace the " " by "."
		$fullName = str_replace("'","", $fullName);// replace the "'" by ""
		$fullName = substr($fullName, 0, 20); // cut after 20 chars
		$fullName = strtolower($fullName); 
		
		return $fullName;
}
function accentAD ($fullName)
{
		//20 char max
		// genering UPN
		$fullName = stripAccents($fullName); // accents
		//$fullName = str_replace(" ",".", $fullName);// replace the " " by "."
		$fullName = str_replace("'","", $fullName);// replace the "'" by ""		
		return $fullName;
}
function accentADCN ($fullName)
{
		//20 char max
		// genering UPN
		$fullName = stripAccents($fullName); // accents
		$fullName = str_replace("'","", $fullName);// replace the "'" by ""		
		return $fullName;
}




/** LDAP FUNCTION **/
/*
* This function searchs in LDAP tree ($ad -LDAP link identifier)
* entry specified by samaccountname and returns its DN or epmty
* string on failure.
*/
function getDN($ad, $samaccountname, $basedn) {
    $attributes = array('dn');
    $result = ldap_search($ad, $basedn,
        "(samaccountname={$samaccountname})", $attributes);
    if ($result === FALSE) { return ''; }
    $entries = ldap_get_entries($ad, $result);
    if ($entries['count']>0) { return $entries[0]['dn']; }
    else { return ''; };
}
function getDNUID($ad, $objectsid, $basedn) {
    $attributes = array('dn');
    $result = ldap_search($ad, $basedn,
        "(objectsid={$objectsid})", $attributes);
    if ($result === FALSE) { return ''; }
    $entries = ldap_get_entries($ad, $result);
    if ($entries['count']>0) { return $entries[0]['dn']; }
    else { return ''; };
}

/*
* This function retrieves and returns CN from given DN
*/
function getCN($dn) {
    preg_match('/[^,]*/', $dn, $matchs, PREG_OFFSET_CAPTURE, 3);
    return $matchs[0][0];
}


function checkManager($ad, $userDN, $manager) 
{
    $result = ldap_read($ad, $userDN, "(manager=$manager)");
    $entries = ldap_get_entries($ad, $result);
	$nbr = $entries['count'];
	if ($nbr == 0) { return FALSE; }
	else { return TRUE; }
}

/*
* This function checks group membership of the user, searching only
* in specified group (not recursively).
*/
function checkGroup($ad, $userdn, $groupdn) 
{
    $attributes = array('members');
    $result = ldap_read($ad, $userdn, "(memberOf=$groupdn)", $attributes);
    $entries = ldap_get_entries($ad, $result);
	$nbr = $entries['count'];
	if ($nbr == 0) { return TRUE; }
	else { return FALSE; }
}

/*
* This function checks group membership of the user, searching
* in specified group and groups which is its members (recursively).
*/
function checkGroupEx($ad, $userdn, $groupdn) {
    $attributes = array('memberof');
    $result = ldap_read($ad, $userdn, '(objectclass=*)', $attributes);
    if ($result === FALSE) { return FALSE; };
    $entries = ldap_get_entries($ad, $result);
    if ($entries['count'] <= 0) { return FALSE; };
    if (empty($entries[0]['memberof'])) { return FALSE; } else {
        for ($i = 0; $i < $entries[0]['memberof']['count']; $i++) {
            if ($entries[0]['memberof'][$i] == $groupdn) { return TRUE; }
            elseif (checkGroupEx($ad, $entries[0]['memberof'][$i], $groupdn)) { return TRUE; };
        };
    };
    return FALSE;
}




// Convertir une date en timestamp
//$indate= 'jj/mm/aaaa'; en aaaammjj
// utilisation : convertTimestamp("22/12/1990");

function convertTimestamp($indate){
    list($day, $month, $year) = explode('/', $indate);
    $timestamp = $year.$month.$day;
    return $timestamp;
}

// convert a date (30/12/2014) to an Account-Expires field for Active Directory
function dateToAccountExpires($dateIn) {
    list($day, $month, $year) = explode('/', $dateIn);
	// $day++;
    $dateIn =$month."/".$day."/".$year." 21:00:01";

	$dateIn = strtotime('-1 day', strtotime($dateIn));
	$unix_timestamp = ($dateIn + 11644560000) * 10000000;
	$unix_timestamp = number_format($unix_timestamp, 0, "", "");
	// $unix_timestamp = $unix_timestamp + 
	return $unix_timestamp;
}
// convert an Account-Expires field in a date (01/01/2014)
function accountExpiresToDate($dateIn) {
	$unix_timestamp = ($dateIn / 10000000) - 11644560000;
	$date = date("j/m/Y", $unix_timestamp);
	return $date;
}


function convertgDataTime($indate){
    list($day, $month, $year) = explode('/', $indate);
    $timestamp = $year."-".$month."-".$day;
    return $timestamp;
}



function ouTermination ($type, $labelName)
{
	if ($type == "Freelance") { 
			return "rs"; 
	}
	else { return "s"; }
}






// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// SID FUNCTIONS
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************

	// Returns the textual SID
	function bin_to_str_sid($binsid) 
	{
		$hex_sid = bin2hex($binsid);
		$rev = hexdec(substr($hex_sid, 0, 2));
		$subcount = hexdec(substr($hex_sid, 2, 2));
		$auth = hexdec(substr($hex_sid, 4, 12));
		$result    = "$rev-$auth";

		for ($x=0;$x < $subcount; $x++) {
			$subauth[$x] = 
				hexdec(little_endian(substr($hex_sid, 16 + ($x * 8), 8)));
			$result .= "-" . $subauth[$x];
		}

		// Cheat by tacking on the S-
		// substr($result, 4); // drop the 'S-0-0' string
		return 'S-' . $result;
	}

	// Converts a little-endian hex-number to one, that 'hexdec' can convert
	function little_endian($hex) 
	{
		$result = "";

		for ($x = strlen($hex) - 2; $x >= 0; $x = $x - 2) 
		{
			$result .= substr($hex, $x, 2);
		}
		return $result;
	}


	// This function will convert a binary value guid into a valid string.
	function bin_to_str_guid($object_guid) 
	{
		$hex_guid = bin2hex($object_guid);
		$hex_guid_to_guid_str = '';
		for($k = 1; $k <= 4; ++$k) {
			$hex_guid_to_guid_str .= substr($hex_guid, 8 - 2 * $k, 2);
		}
		$hex_guid_to_guid_str .= '-';
		for($k = 1; $k <= 2; ++$k) {
			$hex_guid_to_guid_str .= substr($hex_guid, 12 - 2 * $k, 2);
		}
		$hex_guid_to_guid_str .= '-';
		for($k = 1; $k <= 2; ++$k) {
			$hex_guid_to_guid_str .= substr($hex_guid, 16 - 2 * $k, 2);
		}
		$hex_guid_to_guid_str .= '-' . substr($hex_guid, 16, 4);
		$hex_guid_to_guid_str .= '-' . substr($hex_guid, 20);

		return strtoupper($hex_guid_to_guid_str);
	}

// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// SID FUNCTIONS
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************




// functions for gCalendar
include ("func.gCalendar.php");

?>
