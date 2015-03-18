<?php



// call the Zend Gdata library
require_once 'Zend/Loader.php';
Zend_Loader::loadClass('Zend_Http_Client');
Zend_Loader::loadClass('Zend_Gdata');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
Zend_Loader::loadClass('Zend_Gdata_Spreadsheets');
Zend_Loader::loadClass('Zend_Gdata_Docs');


// Authenticate to Google docs
$authService = Zend_Gdata_Spreadsheets::AUTH_SERVICE_NAME;
$httpClient = Zend_Gdata_ClientLogin::getHttpClient($emailGdata, $pass, $authService);
$spreadSheetService = new Zend_Gdata_Spreadsheets($httpClient);

// cols needed to display
	if (!isset($_GET['cols'])) { $colsNeed = 35; } else { $colsNeed = $_GET['cols']; }
// ****************----------------------------------------------


//  Get column information
$query = new Zend_Gdata_Spreadsheets_CellQuery();
$query->setSpreadsheetKey($employeeList);
$query->setMaxCol($colsNeed); // number of needed columns
$query->setReturnEmpty(true); // return empty fields

$feed = $spreadSheetService->getCellFeed($query);

$columnCount = $feed->getColumnCount()->getText();
$rowCount = $feed->getRowCount()->getText();

// calc the $z value needed in function of the number of columns
$colsNeedShow = $columnCount - $colsNeed;
$colsNeedShow = $columnCount - $colsNeedShow;

$columns = array();
/****/

echo $rowCount." rows. "; // 141
echo $columnCount ." columns - ".$colsNeed." used<BR/>"; // 71
//  initialising $z
$z = $colsNeedShow; // start at case needed

$updated = 0;
$inserted = 0;


for ($y = 1; $y < $rowCount; $y++)
{
		for ($x=0; $x < $colsNeed; $x++)
		{
			$entry = $feed->entries[$z]->getCell()->getText();
			include ("inc/sync/generatingGoogleSyncFields.inc.php");
			//echo "<td> x : ".$x. "<br /> y : ".$y."<br /> z : ".$z."</td>"; // for debug
			//Update record in database
			$z++;
		}
		
		// SYNC function
		$queryFunc = mysql_query("SELECT * FROM functions WHERE functionName = \"$function\" ") or die(mysql_error());
		$queryFuncNbr = mysql_num_rows($queryFunc);
		if ($queryFuncNbr == 0)
		{
			mysql_query("INSERT INTO functions (functionName) VALUES (\"$function\")") or die(mysql_error());
			echo "<strong>function</strong> :".$function." inserted <br />"; // debug
			$idFunc1 = mysql_query("SELECT idFunc FROM functions WHERE idFunc = LAST_INSERT_ID();");
			$idFunc = array_shift(mysql_fetch_array($idFunc1));
			$inserted++;
			echo $function. " <b>inserted</b><br />";
		} else { $func=mysql_fetch_array($queryFunc); $idFunc = $func['idFunc']; echo $function. " skipped<br />";}
		
		
		// SYNC label
		$queryLab = mysql_query("SELECT * FROM labels WHERE labelName = \"$label\" ") or die(mysql_error());
		$queryNbr = mysql_num_rows($queryLab);
		if ($queryNbr == 0)
		{
			mysql_query("INSERT INTO labels (labelName) VALUES (\"$label\")") or die(mysql_error());
			echo "<strong>label </strong>".$label." <strong>inserted</strong> <br />"; // debug
			$idLab1 = mysql_query("SELECT idLab FROM labels WHERE idLab = LAST_INSERT_ID();");
			$idLab = array_shift(mysql_fetch_array($idLab1));
			$inserted++;
			echo $label. " <b>inserted</b><br />";
		} else { $lab=mysql_fetch_array($queryLab); $idLab = $lab['idLab']; echo $function. " skipped<br />";}
		
		
		// SYNC department
		$queryDep = mysql_query("SELECT * FROM departments WHERE nameDepartment = \"$department\" ") or die(mysql_error());
		$queryNbr = mysql_num_rows($queryDep);
			if ($queryNbr == 0)
			{
				mysql_query("INSERT INTO departments (nameDepartment) VALUES (\"$department\")") or die(mysql_error());
				echo "<strong>Department </strong>".$department." <strong>inserted</strong> <br />"; // debug
				$idDep1 = mysql_query("SELECT idDep FROM departments WHERE idDep = LAST_INSERT_ID();");
				$idDep = array_shift(mysql_fetch_array($idDep1));
				$inserted++;
				echo $department. " <b>inserted</b><br />";
		} else { $dep=mysql_fetch_array($queryDep); $idDep = $dep['idDep']; echo $department. " skipped<br />";}

		
		// check employee to employees
		$query = mysql_query("SELECT * FROM employees WHERE upn = \"$upn\" ") or die(mysql_error());
		$queryNbrUser = mysql_num_rows($query);
		

		if ($queryNbrUser == 0)
		{
			$ppAddDate = date("Y-m-d H:i:s");
			mysql_query("INSERT INTO employees (firstname, lastname, language, address, birthdate, upn, mobile, mobilePhoneOwner, cellPhoneAbo, contactEmergency, ppAddDate)
								VALUES (\"$firstname\", \"$lastname\", \"$language\", \"$address\", \"$birthdate\", \"$upn\", \"$mobile\", \"$mobilePhoneOwner\", \"$cellPhoneAbo\", \"$contactEmergency\", \"$ppAddDate\" )") or die(mysql_error());
			$idE1 = mysql_query("SELECT idE FROM employees WHERE idE = LAST_INSERT_ID();");
			$idE = array_shift(mysql_fetch_array($idE1));
			
			echo $firstname." ". $lastname." <b>inserted</b><br />";
			
			// contract creation
			mysql_query("INSERT INTO contracts (idE, idFunc, idDep, idLab, startDate, endDate, operationalEndDate, statut, timeRegime, maconomyRol, financePayroll, timeLockout, checkMinHours, businessCard, badgeNr, emailSignatureLogo, primaryEmail, companyPhone, phoneNumber,internalPhone)
								VALUES (\"$idE\", \"$idFunc\", \"$idDep\", \"$idLab\", \"$startDate\", \"$endDate\", \"$operationalEndDate\", \"$statut\", \"$timeRegime\", \"$maconomyRol\", \"$financePayroll\", \"$timeLockout\", \"$checkMinHours\", \"$businessCard\", \"$badgeNr\", \"$emailSignatureLogo\", \"$primaryEmail\", \"$companyPhone\", \"$phoneNumber\", \"$internalPhone\" )") or die(mysql_error());
			$idCon1 = mysql_query("SELECT idCon FROM contracts WHERE idCon = LAST_INSERT_ID();");
			$idCon = array_shift(mysql_fetch_array($idCon1));
			mysql_query("UPDATE employees SET contract = $idCon WHERE idE = $idE") or die(mysql_error());
												
			echo "<strong>Contract</strong> ".$idCon."<strong> for " .$firstname." ". $lastname." created </strong><br />"; // debug
			
			$inserted++;
		}
		else 
		{
			while($emp=mysql_fetch_array($query))
			{
				$idE = $emp['idE']; 
				$queryIDcon = mysql_query("SELECT * FROM employees WHERE idE = '$idE' ") or die(mysql_error());
				while($cont=mysql_fetch_array($queryIDcon))
				{
					$idCon = $cont['contract']; 
					echo "contract found for ". $firstname." ". $lastname." with id ".$idCon." <br />";
				}
			}
		}
		// echo $idCon;
		// adding teamlead
		$teamLead = spliti ("," , $teamLead);
		$teamLeadNbr = count($teamLead);
		print_r($teamLead);
		echo $teamLeadNbr ." team lead for this guy <br />";
		
	if 	($teamLeadNbr > 0) {
		for ($tl = 0; $tl < $teamLeadNbr ; $tl++)
		{
			$tlUpn = genUpnCN($teamLead[$tl]);
			$queryTL = mysql_query("SELECT * FROM employees WHERE upn = \"$tlUpn\" ") or die(mysql_error());
			
			if (mysql_num_rows($queryTL) == 1)
			{
				$tl=mysql_fetch_array($queryTL); $tlIdE = $tl['idE'];
				echo $idCon;
				mysql_query("INSERT INTO teamLeads (employees_idE, contracts_idCon) VALUES (\"$tlIdE\", \"$idCon\" )") or die(mysql_error());
			}
			else {
				echo "Team lead ".$tlUpn." not found in the database or multiple account for this user <br />";
			}
		}
	}
	
	echo "update contract : ".$idCon;
		/****/	
		while($func=mysql_fetch_array($queryFunc))
		{
			
			if ($queryFuncNbr != 0) { $idFunc = $func['idFunc']; };
			mysql_query("UPDATE contracts SET idFunc = '$idFunc' WHERE idCon = '$idCon' ") or die(mysql_error());
		}
		while($labelQ=mysql_fetch_array($queryLab))
		{
			if ($queryNbr != 0) { $idLab = $labelQ['idLab']; }
			mysql_query("UPDATE contracts 
			SET idLab = '$idLab'  
			WHERE idCon = '$idCon' ") or die(mysql_error());
		}				

		while($departmentQ=mysql_fetch_array($queryDep))
		{
			if ($queryNbr != 0) { $idDep = $departmentQ['idDep']; }
			mysql_query("UPDATE contracts
			SET idDep = '$idDep' 
			WHERE idCon = $idCon") or die(mysql_error());
		}	
		
		// SYNC parking
		$queryPark = mysql_query("SELECT * FROM parking WHERE idCon = \"$idCon\" ") or die(mysql_error());
		$queryNbr = mysql_num_rows($queryLab);
			if ($queryNbr == 0)
			{
				mysql_query("INSERT INTO parking (idCon, nrPlaat, comesBy, parking, parkingBricoNr) 
									VALUES (\"$idCon\",\"$nrPlaat\",\"$comesBy\",\"$parking\",\"$parkingBricoNr\")") or die(mysql_error());
				echo "<strong>label </strong>".$label." <strong>inserted</strong> <br />"; // debug
				$idPark = mysql_query("SELECT idPark FROM parking WHERE idPark = LAST_INSERT_ID();");
				$inserted++;
				echo "parking infos updated <br  />";
			}

		
		// Update employee to employees
		$query = mysql_query("SELECT * FROM employees WHERE upn = \"$upn\" ") or die(mysql_error());
		$queryNbrUser = mysql_num_rows($query);
		
		if ($queryNbrUser == 1)
		{
			while($emp=mysql_fetch_array($query))
			{
				$idE = $emp['idE']; 
				$idCon = mysql_query("SELECT contract FROM employees WHERE upn = \"$upn\" ") or die(mysql_error());
			mysql_query("UPDATE employees SET 
			firstname = \"$firstname\", lastname = \"$lastname\",  language = \"$language\", address = \"$address\", birthdate = \"$birthdate\", upn = \"$upn\", mobile = \"$mobile\" , mobilePhoneOwner = \"$mobilePhoneOwner\", cellPhoneAbo = \"$cellPhoneAbo\" , contactEmergency = \"$contactEmergency\" 
			WHERE idE = \"$idE\" ") or die(mysql_error());
			
			// contract update
				mysql_query("UPDATE contracts SET 
									idFunc=\"$idFunc\", idDep=\"$idDep\", idLab=\"$idLab\", startDate=\"$startDate\", endDate=\"$endDate\", operationalEndDate=\"$operationalEndDate\", statut=\"$statut\", timeRegime=\"$timeRegime\", maconomyRol=\"$maconomyRol\",
									financePayroll=\"$financePayroll\", timeLockout=\"$timeLockout\", checkMinHours=\"$checkMinHours\", businessCard=\"$businessCard\", badgeNr=\"$badgeNr\", emailSignatureLogo=\"$emailSignatureLogo\", primaryEmail=\"$primaryEmail\", companyPhone=\"$companyPhone\", phoneNumber=\"$phoneNumber\", internalPhone=\"$internalPhone\"  
									WHERE idCon = \"$idCon\" ") or die(mysql_error());
				echo "contract ".$idE."updated<br />"; // debug		

			$updated++;
			echo "user ".$upn." updated <br />"; // debug
			}
		}
				
		
	echo "----------------------------------------------------------------------------------------------------------  <br />";	
}		

?>