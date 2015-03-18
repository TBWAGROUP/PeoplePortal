<?php

	$time_start_mysqluser = microtime(true);

	$queryEmp = mysql_query("SELECT * FROM employees WHERE objectsid = \"$objectsid\" ") or die(mysql_error());
	$queryNbr = mysql_num_rows($queryEmp);
	$query = mysql_fetch_array($queryEmp);
	$idCon = $query['contract'];
	$idE = $query['idE'];


	//UPDATE ALL DB FIELDS


	// Up or Add the function
	$time_start_mysqluserfunction = microtime(true);
	if ($function != "") {
		// echo "<span class='text-danger'>Function: " . $function;
		// echo "<br>\n";
		$function = str_ireplace("Intern ", "", $function);
		//We don't want any Intern functions to be added to the list;
		//echo "<span class='text-danger'>Function: " . $function;
		//echo "<br>\n";

		$findFunc = mysql_query("SELECT * FROM functions WHERE functionName = \"$function\" ") or die(mysql_error());
		$func = mysql_fetch_array($findFunc);
		if (mysql_num_rows($findFunc) == 0) {
			mysql_query("INSERT INTO functions (functionName) VALUES (\"$function\") ") or die(mysql_error());
			$queryIdFunc = mysql_query("SELECT idFunc FROM functions WHERE idFunc =  LAST_INSERT_ID()") or die(mysql_error());
			$idFunc = array_shift(mysql_fetch_array($queryIdFunc));
			echo "Function :" . $function . " added in DB<br>";
		} else {
			$idFunc = $func['idFunc'];
		}
	} else {
		$idFunc = 74;
	}
	// Display Script End time
	$time_end_mysqluserfunction = microtime(true);
	$execution_time_mysqluserfunction = round(($time_end_mysqluserfunction - $time_start_mysqluserfunction), 2);
	echo '<b>Execution Time of mysql update for function ' . $function .' of upn ' . $upn . ':</b> '.$execution_time_mysqluserfunction.' seconds.<br>';


	// Up or Add the department
	$time_start_mysqluserdepartment = microtime(true);
	if ($department != "") {
		$findDep = mysql_query("SELECT * FROM departments WHERE nameDepartment = \"$department\" ") or die(mysql_error());
		$Dep = mysql_fetch_array($findDep);
		if (mysql_num_rows($findDep) == 0) {
			echo "Department : " . $department;
			mysql_query("INSERT INTO departments (nameDepartment) VALUES (\"$department\") ") or die(mysql_error());
			$queryIdDep = mysql_query("SELECT idDep FROM departments WHERE idDep =  LAST_INSERT_ID()") or die(mysql_error());
			$idDep = array_shift(mysql_fetch_array($queryIdDep));
			echo "Department :" . $department . " added in DB<br>";
		} else {
			$idDep = $Dep['idDep'];
		}
	} else {
		$idDep = 18;
	}
	// Display Script End time
	$time_end_mysqluserdepartment = microtime(true);
	$execution_time_mysqluserdepartment = round(($time_end_mysqluserdepartment - $time_start_mysqluserdepartment), 2);
	echo '<b>Execution Time of mysql update for department ' . $department .' of upn ' . $upn . ':</b> '.$execution_time_mysqluserdepartment.' seconds.<br>';


	// Up or Add the label	
	$time_start_mysqluserlabel = microtime(true);
	if ($company != "") {
		$findLab = mysql_query("SELECT * FROM labels WHERE companyCode = \"$company\" ORDER BY labelName ASC LIMIT 0,1") or die(mysql_error());
		$Lab = mysql_fetch_array($findLab);
		$labelName = $Lab["labelName"];
		$companyCode = $Lab["companyCode"];
		if (mysql_num_rows($findLab) == 0) {
			mysql_query("INSERT INTO labels (labelName, companyCode) VALUES (\"$labelName\", \"$company\") ") or die(mysql_error());
			$queryIdLab = mysql_query("SELECT idLab FROM labels WHERE idLab =  LAST_INSERT_ID()") or die(mysql_error());
			$idLab = array_shift(mysql_fetch_array($queryIdLab));
			echo "Label :" . $company . " added in DB<br>";
		} else {
			$idLab = $Lab['idLab'];
		}
	} else {
		$idLab = 22;
	}
	// Display Script End time
	$time_end_mysqluserlabel = microtime(true);
	$execution_time_mysqluserlabel = round(($time_end_mysqluserlabel - $time_start_mysqluserlabel), 2);
	echo '<b>Execution Time of mysql update for label ' . $labelName .' of upn ' . $upn . ':</b> '.$execution_time_mysqluserlabel.' seconds.<br>';


	// if the employee exist in DB.employees (employees returns 1 result from LDAP objectsID search in DB.employees), update his infos from AD
	if ($queryNbr > 0) {

		$time_start_mysqluserupdate = microtime(true);

		if ($emp['ppAccountStatut'] == 1) // if a trashed user is found, move him to frozen user
		{
			mysql_query("UPDATE employees SET ppAccountStatut = 2 WHERE objectsid = \"$objectsid\"") or die(mysql_error());
			$updated++;
		}
		if ($primaryEmail == "") {
			$primaryEmail = $emp['primaryEmail'];
		}
		if ($mobile == "") {
			$mobile = $emp['mobile'];
		}
		// update his informations
		mysql_query("
								UPDATE contracts SET 
									idFunc = \"$idFunc\",  idDep = \"$idDep\", idLab = \"$idLab\" , primaryEmail = \"$primaryEmail\", phoneNumber = \"$phoneNumber\", itNetworkAdmin = \"$itNetworkAdmin\"
								WHERE idCon = \"$idCon\"") or die(mysql_error());
		mysql_query("
								UPDATE employees SET 
									mobile = \"$mobile\"
								WHERE objectsid = \"$objectsid\"") or die(mysql_error());
		// Display Script End time
		$time_end_mysqluserupdate = microtime(true);
		$execution_time_mysqluserupdate = round(($time_end_mysqluserupdate - $time_start_mysqluserupdate), 2);
		echo '<b>Execution Time of mysql update for user ' . $upn . ':</b> '.$execution_time_mysqluserupdate.' seconds.<br>';

	} // else (if employee not found in DB by LDAP objectsID), add him to DB.employees and DB.contracts
	else {

		$time_start_mysqluserinsert = microtime(true);

		$ppAddDate = date("Y-m-d H:i:s");
		echo "<strong>" . $upn . " inserted and added in Frozen list</strong><br />";
		mysql_query("INSERT INTO employees (objectsid, firstname, lastname, upn, mobile, ppAccountStatut, ppAddDate) VALUES (\"$objectsid\", \"$firstname\", \"$lastname\", \"$upn\", \"$mobile\", \"2\", \"$ppAddDate\" )") or die(mysql_error());
		$queryIdE = mysql_query("SELECT idE FROM employees WHERE idE =  LAST_INSERT_ID()") or die(mysql_error());
		$idE = array_shift(mysql_fetch_array($queryIdE));
		mysql_query("INSERT INTO contracts (idE, idFunc, idDep, idLab, primaryEmail, phoneNumber, disableAccountDate, itNetworkAdmin, validationStage) VALUES (\"$idE\", \"$idFunc\", \"$idDep\", \"$idLab\",\"$primaryEmail\", \"$phoneNumber\", \"$disableAccountDate\", \"$itNetworkAdmin\", \"0\")") or die(mysql_error());
		$queryIdcon = mysql_query("SELECT idCon FROM contracts WHERE idCon =  LAST_INSERT_ID()") or die(mysql_error());
		$idCon = array_shift(mysql_fetch_array($queryIdcon));
		mysql_query("UPDATE employees SET contract = \"$idCon\" WHERE idE = \"$idE\"") or die(mysql_error());
		$inserted++;
		// Display Script End time
		$time_end_mysqluserinsert = microtime(true);
		$execution_time_mysqluserinsert = round(($time_end_mysqluserinsert - $time_start_mysqluserinsert), 2);
		echo '<b>Execution Time of mysql insert for user ' . $upn . ':</b> '.$execution_time_mysqluserinsert.' seconds.<br>';

	}

	// Display Script End time
	$time_end_mysqluser = microtime(true);
	$execution_time_mysqluser = round(($time_end_mysqluser - $time_start_mysqluser), 2);
	echo '<b>Execution Time of mysql for user ' . $upn . ':</b> '.$execution_time_mysqluser.' seconds.<br><br>';


?>
