<div class="scroll" style="height:600px;">
<?php
	$queryEmp = mysql_query("
													SELECT * FROM approvals AS app
													INNER JOIN employees AS emp ON app.idE = emp.idE
													INNER JOIN contracts AS cont ON app.idCon = cont.idCon
													INNER JOIN groups AS grp ON app.idGroup = grp.idGroup
													ORDER BY approveDateTS DESC, idApp DESC
												") or die(mysql_error());
while($app = mysql_fetch_array($queryEmp))
{
	$queryEmpCont = mysql_query("SELECT * FROM employees WHERE contract = \"$app[idCon]\"");
	$employee = mysql_fetch_array($queryEmpCont);

	$queryApprover = mysql_query("SELECT * FROM employees WHERE idE = \"$app[idE]\"");
	$approver = mysql_fetch_array($queryApprover);

	$approveStatut = " modified ";
	
	echo $app['approveDate']  . " - " . $app['firstname']  . " " . $app['lastname'] . $approveStatut. $app['groupName'] . " for <a href=\"index.php?p=empList&searchF=" .  $employee['firstname']." ".$employee['lastname']." \">".$employee['firstname']." ".$employee['lastname'] . " (idCon " .  $app['idCon']. "/idE ". $app['idE']. ") </a><br />";
} // while approv
?>
</div>
