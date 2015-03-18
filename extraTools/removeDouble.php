<?php
// find doubles on teamLead table

		require ("bdd/connect.inc.php");


	$query1 = mysql_query("SELECT * FROM teamLeads ORDER BY contracts_idCon ASC") or die(mysql_error());
	while ($tl = mysql_fetch_array ($query1))
	{	
		$idCon = $tl['contracts_idCon'];
		$idE = $tl['employees_idE'];
		$query2 = mysql_query("SELECT * FROM teamLeads WHERE contracts_idCon = $idCon AND employees_idE = $idE ") or die(mysql_error());
		$tl = mysql_fetch_array ($query2);
		if (mysql_num_rows($query2) > 1)
		{
			$idHA = $tl["idHA"];
			echo $idHA." - Doublon sur ".$tl["contracts_idCon"]." & ".$tl["employees_idE"]."<br />";
			// mysql_query("DELETE FROM teamLead WHERE idHA = $idHA")or die (mysql_error());
		}
	}
	
	
	
	
	
?>