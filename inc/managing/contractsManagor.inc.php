<?php
	// contracts NA & JR
	$queryCont = mysql_query("SELECT * FROM contracts") or die(mysql_error());
	$nbrCont = mysql_num_rows($queryCont);
	$nbrContCol = ceil($nbrCont / 2);
	$nbrContCol2 = $nbrContCol + 1;
	$queryCont1 = mysql_query("SELECT * FROM contracts AS cont LEFT JOIN employees AS emp ON cont.idE = emp.idE ORDER BY idCon DESC LIMIT 0,$nbrContCol") or die(mysql_error());
	$queryCont2 = mysql_query("SELECT * FROM contracts AS cont LEFT JOIN employees AS emp ON cont.idE = emp.idE ORDER BY idCon DESC LIMIT $nbrContCol2,$nbrCont") or die(mysql_error());
	$querycontracts = mysql_query("SELECT * FROM contracts") or die(mysql_error());

	if (!isset($_GET['idE'])) {
		?>
		<h1><img src="img/setupBig.png"/> Contracts Manager</h1>
		<center>
			<table class="float tableEmp">
				<tr>
					<th>
						<center>Contracts</center>
					</th>
				</tr>
				<!-- Col 1 -->
				<?php while ($contract1 = mysql_fetch_array($queryCont1)) { ?>
					<tr class="hover">
						<td><span align="right"><img src="img/ppAS-<?php echo $contract1['ppAccountStatut']; ?>_S.png"/>
								<a href="index.php?p=emp&tab=1&idE=<?php echo $contract1['idE']; ?>&idCon=<?php echo $contract1['idCon']; ?>">#<?php echo $contract1['idCon']; ?> <?php echo $contract1['firstname']; ?>  <?php echo $contract1['lastname']; ?>
						</td>
					</tr>
				<?php } ?>
			</table>

			<table class="tableEmp">
				<tr>
					<th>
						<center>Contracts</center>
					</th>
				</tr>
				<!-- Col 2 -->
				<?php while ($contract2 = mysql_fetch_array($queryCont2)) {
					$queryContract = mysql_query("SELECT * FROM contracts WHERE idE = $contract2[idE]") or die(mysql_error());
					$upn = $contract2['upn'];
					?>

					<tr class="hover">
						<td>
							<span align="right"><a href="index.php?p=emp&tab=1&idE=<?php echo $contract2['idE']; ?>&idCon=<?php echo $contract2['idCon']; ?>&upn=<?php echo $upn; ?>"><img src="img/ppAS-<?php echo $contract2['ppAccountStatut']; ?>_S.png"/>
									#<?php echo $contract2['idCon']; ?> <?php echo $contract2['firstname'] . " " . $contract2['lastname']; ?>
								</a></span></td>

					</tr>
				<?php } ?>
			</table>

		</center>
	<?php
	} else if (!isset($_GET['up'])) { //while($contract=mysql_fetch_array($querycontracts)) {
		$idE = $_GET['idE'];
		$querycontractsUp = mysql_query("SELECT * FROM employees WHERE idE = $idE") or die(mysql_error());
		while ($contract = mysql_fetch_array($querycontractsUp)) {
			$querycontracts = mysql_query("SELECT * FROM contracts ORDER BY startDate DESC") or die(mysql_error());
			?>
		<form method="POST" action='index.php?p=emp&idE=<?php echo $idE; ?>&up&upn=<?php echo $upn; ?>' name="gm">
		<h3 align="center"><a href="index.php?p=emp&idE=<?php echo $contract['idE']; ?>" ><?php echo $contract['firstname'] . " " . $contract['lastname']; ?></a></h3>
			<?php
			// contract
			$queryContract = mysql_query("
											SELECT * FROM contracts AS cont 
												INNER JOIN employees AS emp ON cont.idE = emp.idE 
												INNER JOIN departments AS dep ON cont.idDep = dep.idDep
												INNER JOIN functions AS func ON cont.idFunc = func.idFunc
												INNER JOIN labels AS lab ON cont.idLab = lab.idLab
											WHERE cont.idE = $idE
											ORDER BY  cont.startDateTS ASC
											") or die(mysql_error());
			$nbrcontracts = mysql_num_rows($queryContract);
			while ($contract = mysql_fetch_array($queryContract)) {
				$idCon = $contract['idCon'];
				$requestor = $contract['requestor'];
				?>
  <style type="text/css">
#contract
{
	width:900px;
	margin:auto;
}
#contractInfo
{
	width:400px;
	border:1px dashed #9E9E9E;
	border-radius:5px;
	padding:20px;
	<?php if ($nbrcontracts > 1) {
					echo "float :right;";
				} ?>
	margin-right:50px;
	margin-top:10px;
	margin-bottom:50px;
}

#contractInfo th
{
	color:#666;
	font-weight:normal;
	width:150px;	
}
#contractInfo td
{
	color: #000;	
}
#contractInfo h5
{
	font-weight:bold;
	text-align:left;
	color:#585858;	
}
</style>              
                
<div id="contract">

<div id="contractInfo">
<!-- CHANGE CONTRACT STATUT -->
<?php
				if (isset($_GET['changeStatut'])) {
					if ((memberOf($pp_admin)) || (memberOf($pp_hr))) {
						?>
						<table class="table table-condensed">
							<tr>
								<th colspan=3>
									<center><strong>Change Status</strong></center>
								</th>
							</tr>
							<tr>
								<th>Change Only</th>
								<th>
									<center>Status</center>
								</th>
								<th>+ Mail Notification</th>
							</tr>
							<tr>
								<td>
									<a href="index.php?p=emp&idE=<?php echo $idE; ?>&idCon=<?php echo $idConUrl; ?>&upn=<?php echo $upn; ?>&tab=1&changeStatut=0"><img src="img/tool.png" height="15px"/> Default</a>
								</td>
								<td>
									<center>0</center>
								</td>
								<td>
									<a href="index.php?p=emp&idE=<?php echo $idE; ?>&idCon=<?php echo $idConUrl; ?>&upn=<?php echo $upn; ?>&tab=1&changeStatut=0&mail"><img src="img/mail.png" height="15px"/> Default</a>
								</td>
							<tr>
							<tr>
								<td>
									<a href="index.php?p=emp&idE=<?php echo $idE; ?>&idCon=<?php echo $idConUrl; ?>&upn=<?php echo $upn; ?>&tab=1&changeStatut=1"><img src="img/tool.png" height="15px"/> HR approval</a>
								</td>
								<td>
									<center>1</center>
								</td>
								<td>
									<a href="index.php?p=emp&idE=<?php echo $idE; ?>&idCon=<?php echo $idConUrl; ?>&upn=<?php echo $upn; ?>&tab=1&changeStatut=1&mail"><img src="img/mail.png" height="15px"/> HR approval</a>
								</td>
							</tr>
							<tr>
								<td>
									<a href="index.php?p=emp&idE=<?php echo $idE; ?>&idCon=<?php echo $idConUrl; ?>&upn=<?php echo $upn; ?>&tab=1&changeStatut=2"><img src="img/tool.png" height="15px"/> Provisioning</a>
								</td>
								<td>
									<center>2</center>
								</td>
								<td>
									<a href="index.php?p=emp&idE=<?php echo $idE; ?>&idCon=<?php echo $idConUrl; ?>&upn=<?php echo $upn; ?>&tab=1&changeStatut=2&mail"><img src="img/mail.png" height="15px"/> Provisioning</a>
								</td>
							</tr>
							<tr>
								<td>
									<a href="index.php?p=emp&idE=<?php echo $idE; ?>&idCon=<?php echo $idConUrl; ?>&upn=<?php echo $upn; ?>&tab=1&changeStatut=4&requestor=<?php echo $requestor; ?>"><img src="img/tool.png" height="15px"/> Archived</a>
								</td>
								<td>
									<center>4</center>
								</td>
								<td>

								</td>
							</tr>
							<tr>
								<td>
									<a href="index.php?p=emp&idE=<?php echo $idE; ?>&idCon=<?php echo $idConUrl; ?>&upn=<?php echo $upn; ?>&tab=1&changeStatut=4031&requestor=<?php echo $requestor; ?>"><img src="img/tool.png" height="15px"/> Refused</a>
								</td>
								<td>
									<center>4031</center>
								</td>
								<td>
									<a href="index.php?p=emp&idE=<?php echo $idE; ?>&idCon=<?php echo $idConUrl; ?>&upn=<?php echo $upn; ?>&tab=1&changeStatut=4031&requestor=<?php echo $requestor; ?>&mail"><img src="img/mail.png" height="15px"/> Refused</a>
								</td>
							</tr>
							<tr>
								<th colspan=3>
									<center>
										<a href="index.php?p=emp&idE=<?php echo $idE; ?>&upn=<?php echo $upn; ?>&tab=1">Cancel</a>
									</center>
								</th>
							</tr>
						</table>

						<?php
						require($_SERVER['DOCUMENT_ROOT'] . "/class/PHPMailer/class.phpmailer.php");
						// initialising fields for email
						$firstname = $contract["firstname"];
						$emp["firstname"] = $contract["firstname"];
						$lastname = $contract["lastname"];
						$emp["lastname"] = $contract["lastname"];
						$requestor = $contract["requestor"];
						$emp["requestor"] = $contract["requestor"];
						$startDate = $contract["startDate"];
						$emp["startDate"] = $contract["startDate"];
						$nameDepartment = $contract["nameDepartment"];
						$emp["nameDepartment"] = $contract["nameDepartment"];
						$functionName = $contract["functionName"];
						$emp["functionName"] = $contract["functionName"];
						$emp["empType"] = $contract["empType"];
						$emp["businessCardNeeded"] = $contract["businessCardNeeded"];
						$emp["financeJobCost"] = $contract["financeJobCost"];
						$emp["financePurchaseOrders"] = $contract["financePurchaseOrders"];
						$emp["financeAccountsPayable"] = $contract["financeAccountsPayable"];
						$emp["financeInvoicing"] = $contract["financeInvoicing"];
						$emp["financeFixedAssets"] = $contract["financeFixedAssets"];
						$emp["financeAccountReceivable"] = $contract["financeAccountReceivable"];
						$emp["financeAccountReceivable"] = $contract["financeAccountReceivable"];
						$emp["financeGeneralLedger"] = $contract["financeGeneralLedger"];

						if ($_GET['changeStatut'] == 0) {
							if (isset($_GET['mail'])) {
								require($_SERVER['DOCUMENT_ROOT'] . "/inc/userStartForm/mailNotifications/mail.manager.php");
							}
							mysql_query("UPDATE contracts SET validationStage = 0 WHERE idCon = $idCon") or die (mysql_error());
							echo "<meta http-equiv='refresh' content='0;url=index.php?p=emp&idE=$idE&upn=$upn&idCon=$idConUrl&tab=1'></h4><img src='img/ajax-loader.gif' />";
						}
						if ($_GET['changeStatut'] == 1) {
							if (isset($_GET['mail'])) {
								require($_SERVER['DOCUMENT_ROOT'] . "/inc/userStartForm/mailNotifications/mail.hr.php");
							}
							mysql_query("UPDATE contracts SET validationStage = 1 WHERE idCon = $idCon") or die (mysql_error());
							echo "<meta http-equiv='refresh' content='0;url=index.php?p=emp&idE=$idE&upn=$upn&idCon=$idConUrl&tab=1'></h4><img src='img/ajax-loader.gif' />";
						}
						if ($_GET['changeStatut'] == 2) {
							if (isset($_GET['mail'])) {
								require($_SERVER['DOCUMENT_ROOT'] . "/inc/userStartForm/mailNotifications/mail.provisioning.all.php");
								require($_SERVER['DOCUMENT_ROOT'] . "/inc/userStartForm/mailNotifications/mail.manager.php");
							}
							mysql_query("UPDATE contracts SET validationStage = 2 WHERE idCon = $idCon") or die (mysql_error());
							echo "<meta http-equiv='refresh' content='0;url=index.php?p=emp&idE=$idE&upn=$upn&idCon=$idConUrl&tab=1'></h4><img src='img/ajax-loader.gif' />";
						}
						if ($_GET['changeStatut'] == 4) {
							if (isset($_GET['mail'])) {
								//require($_SERVER['DOCUMENT_ROOT'] . "/inc/userStartForm/mailNotifications/mail.hr.refused.php");
							}
							mysql_query("UPDATE contracts SET validationStage = 4 WHERE idCon = $idCon") or die (mysql_error());
							echo "<meta http-equiv='refresh' content='0;url=index.php?p=emp&idE=$idE&upn=$upn&idCon=$idConUrl&tab=1'></h4><img src='img/ajax-loader.gif' />";
						}
						if ($_GET['changeStatut'] == 4031) {
							if (isset($_GET['mail'])) {
								require($_SERVER['DOCUMENT_ROOT'] . "/inc/userStartForm/mailNotifications/mail.hr.refused.php");
							}
							mysql_query("UPDATE contracts SET validationStage = 4031 WHERE idCon = $idCon") or die (mysql_error());
							echo "<meta http-equiv='refresh' content='0;url=index.php?p=emp&idE=$idE&upn=$upn&idCon=$idConUrl&tab=1'></h4><img src='img/ajax-loader.gif' />";
						}
					}
				}
				?>

# <?php echo "<strong>" . $contract['idCon'] . "</strong>"; ?>
				<?php if ((memberOf($pp_admin)) || (memberOf($pp_hr))) {
					echo " - Status : " . $contract['validationStage']; ?><?php if ($contract['contract'] == $idCon AND ($contract['validationStage'] != 4)) {
						echo "<br /><img src=\"img/okS.png\" /> <span class=\"text-success\"><strong>Current contract</strong></span>";
						$currentContract = TRUE;
						$currentContractId = $contract['contract'];
					} else {
						echo "<br /><img src=\"img/clockS.png\" /> <span><strong>Archived contract</strong></span>";
						$currentContract = FALSE;
					}
				} ?>

		<h5>Info</h5>
		<table class="table table-condensed">
		  <tr>
			<th>Company (label):</th>
			<td><?php echo $contract['labelName']; ?></td>
		  </tr>
		  <tr>
			<th>Payroll:</th>
			<td><?php echo $contract['financePayroll']; ?></td>
		  </tr>
		  <tr>
			<th>Department:</th>
			<td><?php echo $contract['nameDepartment']; ?></td>
		  </tr>
		  <tr>
			<th>Function:</th>
			<td><?php echo $contract['functionName']; ?></td>
		  </tr>
		  <tr>
			<th>Type:</th>
			<td><?php echo $contract['empType']; ?></td>
		  </tr>
		</table>

		 <h5>Date
		  <?php if (memberOf($pp_hr)) { ?>
					<a href="index.php?p=emp&idE=<?php echo $contract['idE']; ?>&idCon=<?php echo $contract['idCon']; ?>&upn=<?php echo $contract['upn']; ?>&tab=2">
						<img src="img/editS.png"/></a><?php } ?>
		  </h5>
			<table class="table table-condensed">
			  <tr>
			    <th>Contract start date:</th>
			    <td><?php echo $contract['startDate']; ?></td>
		      </tr>
			  <tr>
			    <th>Contract end date:</th>
			    <td><?php echo $contract['endDate']; ?></td>
		      </tr>
			  <tr>
			    <th>Operational end date:</th>
			    <td><?php echo $contract['operationalEndDate']; ?></td>
		      </tr>
			  <tr>
			    <th>Material return date:</th>
			    <td><?php echo $contract['materialReturnDate']; ?></td>
		      </tr>
			  <tr>
			    <th>Disable account date:</th>
			    <td><?php echo $contract['disableAccountDate']; ?></td>
		      </tr>
            </table>

              <h5>Time</h5>
            <table class="table table-condensed">
              <tr>
                <th>Time Regime:</th>
                <td><?php echo $contract['timeRegime']; ?></td>
              </tr>
<!--              <tr>
                <th>Time Lockout:</th>
                <td><?php echo strtoupper($contract['timeLockout']); ?></td>
              </tr>
              <tr>
                <th>Check minimum hours:</th>
                <td><?php if ($contract['checkMinHours']) {
					echo "Yes";
				} else {
					echo "No";
				} ?></td>
              </tr> 
-->
            </table>
            <h5>Other</h5>
            <table class="table table-condensed">
              <tr>
                <th>Badge number:</th>
                <td><?php echo $contract['badgeNr']; ?></td>
              </tr>
              <tr>
                <th>Primary Email:</th>
                <td><?php echo $contract['primaryEmail']; ?></td>
              </tr>
              <tr>
                <th>Business Card:</th>
                <td><?php if ($contract['businessCardNeeded']) {
					echo "Yes";
				} else {
					echo "No";
				} ?></td>
              </tr>
              <tr>
                <th>Company Phone:</th>
                <td><?php echo $contract['companyPhone']; ?></td>
              </tr> 
			  <tr>
                <th>Internal Phone:</th>
                <td><?php echo $contract['internalPhone']; ?></td>
              </tr>
            </table>
			  <h5>Note</h5>
            <table class="table table-condensed">
              <tr>
                <td><?php echo nl2br($contract['note']); ?></td>
              </tr>
            </table>

			 <?php if ((memberOf($pp_admin)) || (memberOf($pp_hr))) { ?>
					<a href="index.php?p=emp&idE=<?php echo $contract['idE']; ?>&idCon=<?php echo $contract['idCon']; ?>&upn=<?php echo $contract['upn']; ?>&tab=1&changeStatut=50">Change status</a>
					<?php if (!$currentContract) { ?> -
					<a href="index.php?p=emp&idE=<?php echo $contract['idE']; ?>&idCon=<?php echo $contract['idCon']; ?>&upn=<?php echo $contract['upn']; ?>&tab=1&current">Select contract</a><?php } ?>
					<?php if (!$currentContract) { ?><br/><span style="font-size:10px;">
						<a href="index.php?p=emp&idE=<?php echo $contract['idE']; ?>&idCon=<?php echo $contract['idCon']; ?>&upn=<?php echo $contract['upn']; ?>&tab=1&removing">Remove</a>
						</span><?php } ?>
				<?php } ?>
</form>
	</div>		
</div>
<?php } // end while
			?>
		<?php
		} ?>
	<?php
	} ?>





<!-- MAKE CURRENT CONTRACT -->
<?php
	if (isset($_GET['current'])) {
		if ((memberOf($pp_admin)) || (memberOf($pp_hr))) {
			mysql_query("UPDATE employees SET contract = $idConUrl  WHERE idE = $idE") or die (mysql_error());
			echo "<meta http-equiv='refresh' content='0;url=index.php?p=emp&idE=$idE&idCon=$idConUrl&upn=$upn&tab=1'></h4><img src='img/ajax-loader.gif' />";
		}
	}
?>


<!-- CONTRACT REMOVING TOOL -->
<?php
	if (isset($_GET['removing'])) {
		if ($idConUrl == $currentContractId) {
			echo "<h5 class=\"text-danger\">You can't delete the current contract for a user</h5>";
		} else {
			if ((memberOf($pp_admin)) || (memberOf($pp_hr))) {
				?>
				<h5 class="text-danger">Are you sure? This operation is irreversible.</h5>
				Remove contract # <?php echo $idConUrl;?>?
				<a href="index.php?p=emp&idE=<?php echo $idE; ?>&idCon=<?php echo $idConUrl; ?>&tab=1&removing&removingYes">Yes</a>
				<?php
				if ((isset($_GET['removing'])) && (isset($_GET['removingYes']))) {
					mysql_query("DELETE FROM approvals WHERE idCon =$idConUrl ") or die (mysql_error());
					mysql_query("DELETE FROM emailAliasesEmp WHERE idCon = $idConUrl ") or die (mysql_error());
					mysql_query("DELETE FROM teamLeads WHERE contracts_idCon = $idConUrl ") or die (mysql_error());
					mysql_query("DELETE FROM parking WHERE contracts_idCon = $idConUrl ") or die (mysql_error());
					mysql_query("DELETE FROM contracts WHERE idCon = $idConUrl  AND idE = $idE") or die (mysql_error());
					echo "#" . $idConUrl . " removed <br />";

					echo "<meta http-equiv='refresh' content='0;url=index.php?p=emp&idE=$idE&idCon=$idConUrl&tab=1'></h4><img src='img/ajax-loader.gif' />";
				}
			}
		}
	}
?>
	
