<?php
// find doubles on employees table
	require ("bdd/connect.inc.php");

	$query1 = mysql_query("SELECT * FROM employees ORDER BY objectsid ASC") or die(mysql_error());
	while ($tl = mysql_fetch_array ($query1))
	{	
		$idCon = $tl['objectsid'];
		$idE = $tl['idE'];
		$query2 = mysql_query("SELECT * FROM employees WHERE objectsid = \"$idCon\"") or die(mysql_error());
		$tl = mysql_fetch_array ($query2);
		if (mysql_num_rows($query2) > 1)
		{
			echo "Doublon sur ".$tl["objectsid"]." & ".$tl["idE"]."<br />";
			// mysql_query("DELETE FROM teamLead WHERE idHA = $idHA")or die (mysql_error());
		}
	}
	
	
	
	
	
?>