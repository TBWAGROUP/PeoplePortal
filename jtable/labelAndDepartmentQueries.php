<?php

/*
	This script defines queries for listing employees by all groups except PP Admin (uses usersStatutQueries.php)

	Included by:
	inc/employeesLists/na_all-employeeList.inc.php
	inc/employeesLists/pp_hr-employeeList.inc.php
	inc/employeesLists/pp_building-employeeList.inc.php
	inc/employeesLists/pp_planning-employeeList.inc.php
	inc/employeesLists/pp_carfleet-employeeList.inc.php
	inc/employeesLists/pp_facebook-employeeList.inc.php
	inc/employeesLists/pp_finance-employeeList.inc.php

	Hrefs pointing here:

	Requires:

	Includes:
	/bdd/connect.inc.php

	Form actions:

*/

	include ($_SERVER['DOCUMENT_ROOT']."/bdd/connect.inc.php");

	//Getting records (listAction)
	if ($_GET["action"] == "listLabel")
	{
			$shownLabel = $_GET["label"];
		//Get record count
		$result = mysql_query("SELECT COUNT(*) AS RecordCount FROM contracts AS cont
								INNER JOIN employees AS emp ON cont.idCon = emp.contract
								INNER JOIN departments AS dep ON cont.idDep = dep.idDep
								INNER JOIN labels AS lab ON lab.idLab = cont.idLab
								INNER JOIN functions AS func ON func.idFunc = cont.idFunc
								LEFT JOIN parking AS park ON emp.contract = park.contracts_idCon
								WHERE ( emp.ppAccountStatut = 0 AND lab.labelName = '$shownLabel' )
								;");
		$row = mysql_fetch_array($result);
		$recordCount = $row['RecordCount'];

		//Get records from database
		$result = mysql_query("SELECT cont.*,emp.*,dep.*,lab.*,func.*,park.contracts_idCon AS park_idCon,park.nrPlaat,park.comesBy,park.parkingNr,park.parking FROM contracts AS cont
								INNER JOIN employees AS emp ON cont.idCon = emp.contract
								INNER JOIN departments AS dep ON cont.idDep = dep.idDep
								INNER JOIN labels AS lab ON lab.idLab = cont.idLab
								INNER JOIN functions AS func ON func.idFunc = cont.idFunc
								LEFT JOIN parking AS park ON emp.contract = park.contracts_idCon
								WHERE ( emp.ppAccountStatut = 0 AND lab.labelName = '$shownLabel' )
								ORDER BY " . $_GET["jtSorting"] . " LIMIT " . $_GET["jtStartIndex"] . "," . $_GET["jtPageSize"] .";");

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




	else if ($_GET["action"] == "listDepartment")
	{
		//Get record count
		$result = mysql_query("SELECT COUNT(*) AS RecordCount FROM contracts AS cont
								INNER JOIN employees AS emp ON cont.idCon = emp.contract
								INNER JOIN departments AS dep ON cont.idDep = dep.idDep
								INNER JOIN labels AS lab ON lab.idLab = cont.idLab
								INNER JOIN functions AS func ON func.idFunc = cont.idFunc
								LEFT JOIN parking AS park ON emp.contract = park.contracts_idCon
								WHERE emp.ppAccountStatut = 0 AND ( cont.empType = \"Administrator\" OR cont.empType = \"Employee\" OR cont.empType = \"Contractor Fixed\" OR empType = \"Contractor Timebased\" )
								".$filter.";");
		$row = mysql_fetch_array($result);
		$recordCount = $row['RecordCount'];

		//Get records from database
		$result = mysql_query("SELECT cont.*,emp.*,dep.*,lab.*,func.*,park.contracts_idCon AS park_idCon,park.nrPlaat,park.comesBy,park.parkingNr,park.parking FROM contracts AS cont
								INNER JOIN employees AS emp ON cont.idCon = emp.contract
								INNER JOIN departments AS dep ON cont.idDep = dep.idDep
								INNER JOIN labels AS lab ON lab.idLab = cont.idLab
								INNER JOIN functions AS func ON func.idFunc = cont.idFunc
								LEFT JOIN parking AS park ON emp.contract = park.contracts_idCon
								WHERE emp.ppAccountStatut = 0 AND ( cont.empType = \"Administrator\" OR cont.empType = \"Employee\" OR cont.empType = \"Contractor Fixed\" OR cont.empType = \"Contractor Timebased\" )
								".$filter."
								ORDER BY " . $_GET["jtSorting"] . " LIMIT " . $_GET["jtStartIndex"] . "," . $_GET["jtPageSize"] .";");

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

	
	//search a record
	else if($_GET["action"] == "search")
	{
		$search = $_GET["searchF"];
		
		if (isset ($_GET["admin"])) { $filterAccess = " "; } else { $filterAccess ="(emp.ppAccountStatut = 0) AND "; }

		//Get records from database
		$result = mysql_query("SELECT COUNT(*) AS RecordCount 
							FROM employees AS emp
								INNER JOIN contracts AS cont ON emp.contract = cont.idCon
								INNER JOIN departments AS dep ON cont.idDep = dep.idDep
								INNER JOIN labels AS lab ON lab.idLab = cont.idLab
								INNER JOIN functions AS func ON func.idFunc = cont.idFunc
								LEFT JOIN parking AS park ON emp.contract = park.contracts_idCon
						WHERE
						$filterAccess 
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
				SELECT cont.*,emp.*,dep.*,lab.*,func.*,park.contracts_idCon AS park_idCon,park.nrPlaat,park.comesBy,park.parkingNr,park.parking FROM employees AS emp
								INNER JOIN contracts AS cont ON emp.contract = cont.idCon
								INNER JOIN departments AS dep ON cont.idDep = dep.idDep
								INNER JOIN labels AS lab ON lab.idLab = cont.idLab
								INNER JOIN functions AS func ON func.idFunc = cont.idFunc
								LEFT JOIN parking AS park ON emp.contract = park.contracts_idCon
						WHERE
						$filterAccess 
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
	
	
	
	mysql_close($con);

	
?>
