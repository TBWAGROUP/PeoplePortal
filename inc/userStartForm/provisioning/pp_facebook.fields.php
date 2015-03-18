<?php
	$idEurl = mysql_real_escape_string($_GET['idE']);
	$queryContFb = mysql_query("
												SELECT * FROM employees AS emp
												INNER JOIN contracts AS cont ON cont.idCon = emp.contract
												WHERE emp.idE = $idEurl
											") or die (mysql_error() );
	$contract = mysql_fetch_array($queryContFb);
	$idCon = $contract['idCon'];
	
	$queryGroupId = mysql_query("SELECT idGroup FROM groups WHERE groupName = \"$pp_facebook\"") or die (mysql_error());
	$idGroup = array_shift(mysql_fetch_array($queryGroupId));
	$queryGroupStatut = mysql_query("SELECT * FROM approvals WHERE idGroup = $idGroup AND idCon = $idCon") or die (mysql_error());
	$checkApprov = mysql_num_rows($queryGroupStatut );

if (isset($_GET['fbAdded']))
{
		$createdFb = $_GET['fbAdded'];
		mysql_query("UPDATE contracts SET 
										createdFb = $createdFb
										WHERE idCon = $idCon") or die (mysql_error());
		approvalUp ($currentIdE, $idGroup, $idConUrl);
	echo "<h4><img src='img/ajax-loader.gif' /> Updating changes...</h4><meta http-equiv='refresh' content='0;url=index.php?p=emp&tab=1&idE=$idEurl&idCon=$idCon'>";
}
?>


<center>
  <h2>Facebook information</h2>
	

 <center>
	 <?php if ($contract['createdFb'] == 0) { ?>
		 <p class="infoCreated btn-danger"><img src="img/noOk.png"/> Not on Facebook</p>
		 <a class="btn btn-default btn-sm" href="index.php?p=emp&idE=<?php echo $idEurl; ?>&idCon=<?php echo $idCon; ?>&fbAdded=1"><img src="img/fb-1-S.png"/> Added On  Facebook</a></p>
		 <a class="btn btn-default btn-sm" href="index.php?p=emp&idE=<?php echo $idEurl; ?>&idCon=<?php echo $idCon; ?>&fbAdded=2"><img src="img/fb-2-S.png"/> Not needed on Facebook</a></p>
	 <?php
	 }
		 if ($contract['createdFb'] == 1) {
			 ?>
			 <p class="infoCreated btn-success"><img src="img/okWht.png"/> On Facebook</p>
			 <a class="btn btn-default btn-sm" href="index.php?p=emp&idE=<?php echo $idEurl; ?>&idCon=<?php echo $idCon; ?>&fbAdded=0"><img src="img/fb-0-S.png"/> Not yet on Facebook</a></p>
			 <a class="btn btn-default btn-sm" href="index.php?p=emp&idE=<?php echo $idEurl; ?>&idCon=<?php echo $idCon; ?>&fbAdded=2"><img src="img/fb-2-S.png"/> Not needed on Facebook</a></p>
		 <?php } ?>
	 <?php
		 if ($contract['createdFb'] == 2) {
			 ?>
			 <p class="infoCreated btn-warning"><img src="img/okWht.png"/> Not needed on Facebook</p>
			 <a class="btn btn-default btn-sm" href="index.php?p=emp&idE=<?php echo $idEurl; ?>&idCon=<?php echo $idCon; ?>&fbAdded=0"><img src="img/fb-0-S.png"/> To add on Facebook</a></p>
			 <a class="btn btn-default btn-sm" href="index.php?p=emp&idE=<?php echo $idEurl; ?>&idCon=<?php echo $idCon; ?>&fbAdded=1"><img src="img/fb-1-S.png"/> Added on Facebook</a></p>
		 <?php } ?>
 </center>
  <hr />


