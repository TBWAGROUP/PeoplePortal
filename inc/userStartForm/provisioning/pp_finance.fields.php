<?php

	if (isset($_GET['idCon'])) { $idConUrl = mysql_real_escape_string($_GET['idCon']); } else { $idConUrl = $idCon; }
	$idEurl = mysql_real_escape_string($_GET['idE']);
	$queryCont = mysql_query("
												SELECT * FROM employees AS emp
												INNER JOIN contracts AS cont ON cont.idE = emp.idE
												WHERE cont.idCon = $idConUrl
											") or die (mysql_error() );
	$emp = mysql_fetch_array($queryCont);
	
	$queryPark = mysql_query("SELECT * FROM parking WHERE contracts_idCon = $idConUrl") or die (mysql_error());
	$parking = mysql_fetch_array($queryPark);
	$nbrPark = mysql_num_rows($queryPark);
	
	$queryGroupId = mysql_query("SELECT idGroup FROM groups WHERE groupName = \"$pp_finance\"") or die (mysql_error());
	$idGroup = array_shift(mysql_fetch_array($queryGroupId));
	$queryGroupStatut = mysql_query("SELECT * FROM approvals WHERE idGroup = $idGroup AND idCon = $idConUrl") or die (mysql_error());
	$checkApprov = mysql_num_rows($queryGroupStatut );

	
?>




<?php 
if (isset($_GET['maco']))
{
	if ($_GET['maco'] == 1)
	{
		mysql_query("UPDATE contracts SET 
										createdInMaconomy = 1
										WHERE idCon = $idConUrl") or die (mysql_error());
			approvalUp ($currentIdE, $idGroup, $idConUrl);
	}
	if ($_GET['maco'] == 0)
	{
		mysql_query("UPDATE contracts SET 
										createdInMaconomy = 0
										WHERE idCon = $idConUrl") or die (mysql_error());
			approvalUp ($currentIdE, $idGroup, $idConUrl);
	}
	echo "<h4><img src='img/ajax-loader.gif' /> Updating changes...</h4><meta http-equiv='refresh' content='0;url=index.php?p=emp&idE=$idEurl&idCon=$idConUrl&tab=2'>";
}

?>

<center>
  <h2>Finance</h2>
	<div id="profile" style="width:70%;">
		<h5>Desired login (same as Active Directory): <?php echo $upn; ?> <br></h5>
		<h5>Finance Access</h5>
<table class="table table-condensed">
	<tr>
		<th>Timesheets</th>
		<td>
			<?php if ($emp['timesheets']) { echo "Yes"; } else { echo "No"; } ?>		
		</td>
		<th>Timesheets Blocking</th>
		<td>
			<?php if ($emp['timesheetblocking']) { echo "Yes"; } else { echo "No"; } ?>
		</td>
	</tr>
	<tr>
		<th>Job Cost</th>
		<td width="10%">
			<?php if ($emp['financeJobCost']) { echo "Yes"; } else { echo "No"; }?>
		</td>
		<th>Account Payable</th>
		<td width="10%">
			<?php if ($emp['financeAccountsPayable']) { echo "Yes"; } else { echo "No"; }  ?>
		</td>
		<th>Accounts Receivable</th>
		<td width="10%">
			<?php if ($emp['financeAccountReceivable']) { echo "Yes"; } else { echo "No"; } ?>
		</td>
		<th>Fixed Assets</th>
		<td>
			<?php if ($emp['financeFixedAssets']) { echo "Yes"; } else { echo "No"; } ?>
		</td>
	</tr>
	<tr>
		<th>Purchase Orders</th>
		<td width="10%">
			<?php if ($emp['financePurchaseOrders']) { echo "Yes"; } else { echo "No"; } ?>
		</td>
		<th>Invoicing</th>
		<td>
			<?php if ($emp['financeInvoicing']) { echo "Yes"; } else { echo "No"; } ?>
		</td>
		<th>General Ledger</th>
		<td>
			<?php if ($emp['financeGeneralLedger']) { echo "Yes"; } else { echo "No"; } ?>
		</td>
		<th>HR</th>
		<td>
			<?php if ($emp['financeHR']) { echo "Yes"; } else { echo "No"; } ?>
		</td>
	</tr>
</table>
</div>

<center>
		<?php if ($emp['createdInMaconomy'] == 0) { ?>
		<p class="infoCreated"><strong>Maconomy :</strong> <img src="img/noOk.png" /> Not in </p>
	  <p class="text-info">If this user was created in Maconomy, you can click this button to inform People Portal</p>
			<a class="btn btn-default btn-sm" href="index.php?p=emp&idE=<?php echo $idEurl; ?>&idCon=<?php echo $idConUrl; ?>&maco=1&tab=2"><img src="img/okS.png" /> Created in Maconomy</a></p>
	  <?php } 
	  if ($emp['createdInMaconomy'] == 1) {  ?>
		<p class="infoCreated btn-success"><img src="img/okWht.png" /> Created in Maconomy</p>
	  		<a class="btn btn-default btn-sm" href="index.php?p=emp&idE=<?php echo $idEurl; ?>&idCon=<?php echo $idConUrl; ?>&maco=0&tab=2"><img src="img/noOkS.png" /> Not created in Maconomy</a></p>
	  <?php } ?>
</center>

