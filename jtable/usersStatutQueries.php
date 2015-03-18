<?php

/*
	This script defines queries for PP Admin lists

	Included by:
	inc/employeesLists/pp_admin/usersActive.inc.php
	inc/employeesLists/pp_admin/usersArchived.inc.php
	inc/employeesLists/pp_admin/usersFrozen.inc.php
	inc/employeesLists/pp_admin/usersStandalone.inc.php
	inc/employeesLists/pp_admin/usersStartForm.inc.php
	inc/employeesLists/pp_admin/usersTrash.inc.php

	Hrefs pointing here:

	Requires:

	Includes:
	/bdd/connect.inc.php
*/

try
{
	//Open database connection
	include ("../bdd/connect.inc.php");
	
	//Getting records (listAction)
	if($_GET["action"] == "listAllStaff")
	{

		// ************

		//Get record count
		$result = mysql_query("SELECT COUNT(*) AS RecordCount FROM employees WHERE ppAccountStatut = 3;");
		$row = mysql_fetch_array($result);
		$recordCount = $row['RecordCount'];

		//Get records from database
		$result = mysql_query("
								SELECT * FROM employees AS emp
								INNER JOIN contracts AS cont ON emp.contract = cont.idCon
								INNER JOIN departments AS dep ON cont.idDep = dep.idDep
								INNER JOIN labels AS lab ON lab.idLab = cont.idLab
								INNER JOIN functions AS func ON func.idFunc = cont.idFunc
								ORDER BY " . $_GET["jtSorting"] . " LIMIT " . $_GET["jtStartIndex"] . "," . $_GET["jtPageSize"] .
			";");

		//Add all records to an array
		$rows = array();
		while($row = mysql_fetch_array($result))
		{
			$rows[] = $row;
		}

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		$jTableResult['TotalRecordCount'] = $recordCount;
		$jTableResult['Records'] = $rows;
		print json_encode($jTableResult);
	}

	if($_GET["action"] == "listArchived")
	{		
		
		// ************
		
		//Get record count
		$result = mysql_query("SELECT COUNT(*) AS RecordCount FROM employees WHERE ppAccountStatut = 4;");
		$row = mysql_fetch_array($result);
		$recordCount = $row['RecordCount'];

		//Get records from database
		$result = mysql_query("
								SELECT * FROM contracts AS cont
								INNER JOIN employees AS emp ON cont.idCon = emp.contract
								INNER JOIN departments AS dep ON cont.idDep = dep.idDep
								INNER JOIN labels AS lab ON lab.idLab = cont.idLab
								INNER JOIN functions AS func ON func.idFunc = cont.idFunc
								LEFT JOIN parking AS park ON emp.contract = park.contracts_idCon
								WHERE ppAccountStatut = 4
								ORDER BY " . $_GET["jtSorting"] . " LIMIT " . $_GET["jtStartIndex"] . "," . $_GET["jtPageSize"] . 
						";");
		
		//Add all records to an array
		$rows = array();
		while($row = mysql_fetch_array($result))
		{
		    $rows[] = $row;
		}

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		$jTableResult['TotalRecordCount'] = $recordCount;
		$jTableResult['Records'] = $rows;
		print json_encode($jTableResult);
	}
	//Updating a record (updateAction)
	
	if($_GET["action"] == "deleteFromTrash")
	{
		$idE = $_POST["idE"];
		
				mysql_query("DELETE FROM employeeGroup WHERE idE = $idE") or die (mysql_error() );
				mysql_query("DELETE FROM teamLeads WHERE employees_idE = $idE") or die (mysql_error() );
				
				$delContracts = mysql_query("SELECT idCon, idE FROM contracts WHERE idE = $idE")or die (mysql_error());
				while ($findContracts = mysql_fetch_array($delContracts)) 
				{	
					mysql_query("DELETE FROM approvals WHERE idCon =\"$findContracts[idCon]\" ") or die (mysql_error() );
					mysql_query("DELETE FROM approvals WHERE idE = $idE ") or die (mysql_error() );
					mysql_query("DELETE FROM emailAliasesEmp WHERE idCon = \"$findContracts[idCon]\"") or die (mysql_error() );
					mysql_query("DELETE FROM teamLeads WHERE contracts_idCon = \"$findContracts[idCon]\"") or die (mysql_error() );
					mysql_query("DELETE FROM parking WHERE contracts_idCon = \"$findContracts[idCon]\"") or die (mysql_error() );
					mysql_query("DELETE FROM contracts WHERE idCon = \"$findContracts[idCon]\" AND idE = $idE") or die (mysql_error() );
					mysql_query("DELETE FROM employees WHERE ppAccountStatut = 1 AND idE = $idE") or die (mysql_error() );
				}		
		
		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		print json_encode($jTableResult);
	}
	
	if($_GET["action"] == "editStatut")
	{
		//Update record in database
		$result = mysql_query("UPDATE employees SET ppAccountStatut = '" . $_POST["ppAccountStatut"] . "' WHERE idE = " . $_POST["idE"] . ";");

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		print json_encode($jTableResult);
	}
	
	
	if($_GET["action"] == "listStandalone")
	{
		
		// ************
		
		//Get record count
		$result = mysql_query("SELECT COUNT(*) AS RecordCount FROM employees WHERE ppAccountStatut = 3;");
		$row = mysql_fetch_array($result);
		$recordCount = $row['RecordCount'];

		//Get records from database
		$result = mysql_query("
								SELECT * FROM employees AS emp
								INNER JOIN contracts AS cont ON emp.contract = cont.idCon
								INNER JOIN departments AS dep ON cont.idDep = dep.idDep
								INNER JOIN labels AS lab ON lab.idLab = cont.idLab
								INNER JOIN functions AS func ON func.idFunc = cont.idFunc
								WHERE ppAccountStatut = 3
								ORDER BY " . $_GET["jtSorting"] . " LIMIT " . $_GET["jtStartIndex"] . "," . $_GET["jtPageSize"] . 
						";");
		
		//Add all records to an array
		$rows = array();
		while($row = mysql_fetch_array($result))
		{
		    $rows[] = $row;
		}

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		$jTableResult['TotalRecordCount'] = $recordCount;
		$jTableResult['Records'] = $rows;
		print json_encode($jTableResult);
	}
	if($_GET["action"] == "listActive")
	{
		
		// ************
		
		//Get record count
		$result = mysql_query("SELECT COUNT(*) AS RecordCount FROM employees WHERE ppAccountStatut = 0;");
		$row = mysql_fetch_array($result);
		$recordCount = $row['RecordCount'];

		//Get records from database
		$result = mysql_query("
								SELECT * FROM employees AS emp
								INNER JOIN contracts AS cont ON emp.contract = cont.idCon
								INNER JOIN departments AS dep ON cont.idDep = dep.idDep
								INNER JOIN labels AS lab ON lab.idLab = cont.idLab
								INNER JOIN functions AS func ON func.idFunc = cont.idFunc
								WHERE ppAccountStatut = 0
								ORDER BY " . $_GET["jtSorting"] . " LIMIT " . $_GET["jtStartIndex"] . "," . $_GET["jtPageSize"] . 
						";");
		
		//Add all records to an array
		$rows = array();
		while($row = mysql_fetch_array($result))
		{
		    $rows[] = $row;
		}

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		$jTableResult['TotalRecordCount'] = $recordCount;
		$jTableResult['Records'] = $rows;
		print json_encode($jTableResult);
	}
	if($_GET["action"] == "listTrash")
	{
		
		// ************
		
		//Get record count
		$result = mysql_query("SELECT COUNT(*) AS RecordCount FROM employees WHERE ppAccountStatut = 1 OR ppAccountStatut = 403;");
		$row = mysql_fetch_array($result);
		$recordCount = $row['RecordCount'];

		//Get records from database
		$result = mysql_query("
								SELECT * FROM employees AS emp
								INNER JOIN contracts AS cont ON emp.contract = cont.idCon
								INNER JOIN departments AS dep ON cont.idDep = dep.idDep
								INNER JOIN labels AS lab ON lab.idLab = cont.idLab
								INNER JOIN functions AS func ON func.idFunc = cont.idFunc
								WHERE ppAccountStatut = 1 OR ppAccountStatut = 403
								ORDER BY " . $_GET["jtSorting"] . " LIMIT " . $_GET["jtStartIndex"] . "," . $_GET["jtPageSize"] . 
						";");
		
		//Add all records to an array
		$rows = array();
		while($row = mysql_fetch_array($result))
		{
		    $rows[] = $row;
		}

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		$jTableResult['TotalRecordCount'] = $recordCount;
		$jTableResult['Records'] = $rows;
		print json_encode($jTableResult);
	}
	if($_GET["action"] == "moveTrashToFreeze")
	{
		//Update record in database
		$result = mysql_query("UPDATE employees SET ppAccountStatut = '2' WHERE idE = " . $_POST["idE"] . ";");

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		print json_encode($jTableResult);
	}
	
	
	
	if($_GET["action"] == "moveFreezeToUsers")
	{
		//Update record in database
		$result = mysql_query("UPDATE employees SET ppAccountStatut = '0' WHERE idE = " . $_POST["idE"] . ";");

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		print json_encode($jTableResult);
	}	else if($_GET["action"] == "moveFreezeToArchive")
	{
		//Update record in database
		$result = mysql_query("UPDATE employees SET ppAccountStatut = '4' WHERE idE = " . $_POST["idE"] . ";");

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		print json_encode($jTableResult);
	}
	if($_GET["action"] == "listFrozen")
	{
		
		// ************
		
		//Get record count
		$result = mysql_query("SELECT COUNT(*) AS RecordCount FROM employees WHERE ppAccountStatut = 2;");
		$row = mysql_fetch_array($result);
		$recordCount = $row['RecordCount'];

		//Get records from database
		$result = mysql_query("
								SELECT * FROM employees AS emp
								INNER JOIN contracts AS cont ON cont.idE = emp.idE
								INNER JOIN departments AS dep ON cont.idDep = dep.idDep
								INNER JOIN labels AS lab ON lab.idLab = cont.idLab
								INNER JOIN functions AS func ON func.idFunc = cont.idFunc
								WHERE ppAccountStatut = 2
								ORDER BY " . $_GET["jtSorting"] . " LIMIT " . $_GET["jtStartIndex"] . "," . $_GET["jtPageSize"] . 
						";");
		
		//Add all records to an array
		$rows = array();
		while($row = mysql_fetch_array($result))
		{
		    $rows[] = $row;
		}

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		$jTableResult['TotalRecordCount'] = $recordCount;
		$jTableResult['Records'] = $rows;
		print json_encode($jTableResult);
	}
	if($_GET["action"] == "listUsf")
	{
		
		// ************
		
		//Get record count
		$result = mysql_query("SELECT COUNT(*) AS RecordCount FROM employees WHERE ppAccountStatut = 5;");
		$row = mysql_fetch_array($result);
		$recordCount = $row['RecordCount'];

		//Get records from database
		$result = mysql_query("
								SELECT * FROM employees AS emp
								INNER JOIN contracts AS cont ON emp.contract = cont.idCon
								INNER JOIN departments AS dep ON cont.idDep = dep.idDep
								INNER JOIN labels AS lab ON lab.idLab = cont.idLab
								INNER JOIN functions AS func ON func.idFunc = cont.idFunc
								WHERE ppAccountStatut = 5
								ORDER BY " . $_GET["jtSorting"] . " LIMIT " . $_GET["jtStartIndex"] . "," . $_GET["jtPageSize"] . 
						";");
		
		//Add all records to an array
		$rows = array();
		while($row = mysql_fetch_array($result))
		{
		    $rows[] = $row;
		}

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		$jTableResult['TotalRecordCount'] = $recordCount;
		$jTableResult['Records'] = $rows;
		print json_encode($jTableResult);
	}

	else if($_GET["action"] == "search")
	{
		$search = $_GET["searchF"];

		//Get records from database
		$result = mysql_query("SELECT COUNT(*) AS RecordCount
							FROM employees AS emp
								INNER JOIN contracts AS cont ON emp.contract = cont.idCon
								INNER JOIN departments AS dep ON cont.idDep = dep.idDep
								INNER JOIN labels AS lab ON lab.idLab = cont.idLab
								INNER JOIN functions AS func ON func.idFunc = cont.idFunc
								LEFT JOIN parking AS park ON emp.contract = park.contracts_idCon
						WHERE
						(
							CONCAT(firstname, ' ', lastname) LIKE '%".$search."%'
							OR emp.firstname LIKE '%".$search."%'
							OR emp.lastname LIKE '%".$search."%'
							OR cont.primaryEmail LIKE '%".$search."%'
							OR dep.nameDepartment LIKE '%".$search."%'
							OR lab.labelName LIKE '%".$search."%'
							OR func.functionName LIKE '%".$search."%'
							OR park.nrPlaat LIKE '%".$search."%'
							OR park.parking LIKE '%".$search."%'
						) ;");
		$row = mysql_fetch_array($result);
		$recordCount = $row['RecordCount'];

		$result  = mysql_query("
				SELECT * FROM employees AS emp
								INNER JOIN contracts AS cont ON emp.contract = cont.idCon
								INNER JOIN departments AS dep ON cont.idDep = dep.idDep
								INNER JOIN labels AS lab ON lab.idLab = cont.idLab
								INNER JOIN functions AS func ON func.idFunc = cont.idFunc
								LEFT JOIN parking AS park ON emp.contract = park.contracts_idCon
						WHERE
						(
							CONCAT(firstname, ' ', lastname) LIKE '%".$search."%'
							OR emp.firstname LIKE '%".$search."%'
							OR emp.lastname LIKE '%".$search."%'
							OR cont.primaryEmail LIKE '%".$search."%'
							OR dep.nameDepartment LIKE '%".$search."%'
							OR lab.labelName LIKE '%".$search."%'
							OR func.functionName LIKE '%".$search."%'
							OR park.nrPlaat LIKE '%".$search."%'
							OR park.parking LIKE '%".$search."%'
						)
						ORDER BY " . $_GET["jtSorting"] . " LIMIT " . $_GET["jtStartIndex"] . "," . $_GET["jtPageSize"] . ";"
		);
		//Add all records to an array
		$rows = array();
		while($row = mysql_fetch_array($result))
		{
			$rows[] = $row;
		}

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		$jTableResult['TotalRecordCount'] = $recordCount;
		$jTableResult['Records'] = $rows;
		print json_encode($jTableResult);
	}


	//Close database connection
	mysql_close($con);

}
catch(Exception $ex)
{
    //Return error message
	$jTableResult = array();
	$jTableResult['Result'] = "ERROR";
	$jTableResult['Message'] = $ex->getMessage();
	print json_encode($jTableResult);
}
	
?>
