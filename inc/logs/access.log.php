<div class="scroll" style="height:600px;">
<?php
	$queryEmp = mysql_query("
													SELECT * FROM accessLog ORDER BY idaccessLog DESC LIMIT 0,100
												") or die(mysql_error());
while($app = mysql_fetch_array($queryEmp))
{	
	if ($app['statut'] == 0) { $loginStatut = " - correctly connected "; }
	if ($app['statut'] == 1) { $loginStatut = " <span class=\"text-warning\"> - failed to connect (exist in AD but not found in the People Portal database - <strong>sync needed</strong>)</span> "; }
	if ($app['statut'] == 2) { $loginStatut = " <span class=\"text-danger\"> - failed to connect (bad LOGIN/PSWD, not found in Active Directory or not a member of NA ALL)</span> "; }
	if ($app['statut'] == 3) { $loginStatut = " <span class=\"text-danger\"> - failed to connect (bad login / password) </span>"; }
	if ($app['statut'] == 4) { $loginStatut = " <span class=\"text-danger\"> - <strong>rejected</strong> (not in NA All, at least) </span>"; }
	
	
	echo $app['dateTime']  . " - " . $app['ip'] . $loginStatut. " - <a href=\"index.php?p=empList&searchF=" .  $app['userTried']." \">".$app['userTried']." </a><br />";
} // while approv
?>
</div>
