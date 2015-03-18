<?php

	if (isset($_GET['idCon'])) {
		$idConUrl = mysql_real_escape_string($_GET['idCon']);
	} else {
		$idConUrl = $idCon;
	}
	$idEurl = mysql_real_escape_string($_GET['idE']);
	$upn = mysql_real_escape_string($_GET['upn']);
	$queryCont = mysql_query("
												SELECT * FROM contracts AS cont
													INNER JOIN employees AS emp ON cont.idE = emp.idE
												WHERE idCon = $idConUrl
											") or die (mysql_error());

	$contract = mysql_fetch_array($queryCont);

	$queryPark = mysql_query("SELECT * FROM parking WHERE contracts_idCon = $idConUrl") or die (mysql_error());
	$parking = mysql_fetch_array($queryPark);
	$nbrPark = mysql_num_rows($queryPark);

	$queryGroupId = mysql_query("SELECT idGroup FROM groups WHERE groupName = \"$pp_building\"") or die (mysql_error());
	$idGroup = array_shift(mysql_fetch_array($queryGroupId));

	$queryGroupStatut = mysql_query("SELECT * FROM approvals WHERE idGroup = $idGroup AND idCon = $idConUrl") or die (mysql_error());
	$checkApprov = mysql_num_rows($queryGroupStatut);

	if (isset($_GET['updateBC'])) {
		// update contracts for primary BC
		if (!empty($_POST['bcPrimaryNeeded'])) {
			mysql_query("UPDATE contracts SET businessCardNeeded = 1 WHERE idCon = $idCon AND idE = $idE;");
		} else {
			mysql_query("UPDATE contracts SET businessCardNeeded = 0 WHERE idCon = $idCon AND idE = $idE;");
		}
		if (!empty($_POST['bcPrimaryCreated'])) {
			mysql_query("UPDATE contracts SET businessCardCreated = 1 WHERE idCon = $idCon AND idE = $idE;");
		} else {
			mysql_query("UPDATE contracts SET businessCardCreated = 0 WHERE idCon = $idCon AND idE = $idE;");
		}

		// update emailAliasesEmp for alias BC
		mysql_query("UPDATE emailAliasesEmp SET businessCardNeeded = 0 WHERE idCon = $idCon ;") or die (mysql_error());
		if (!empty($_POST['bcAliasNeeded'])) {
			foreach ($_POST['bcAliasNeeded'] as $check) {
				mysql_query("UPDATE emailAliasesEmp SET businessCardNeeded = 1 WHERE idCon = $idCon AND idAliase = $check;");
			}
		}
		mysql_query("UPDATE emailAliasesEmp SET businessCardCreated = 0 WHERE idCon = $idCon ;") or die (mysql_error());
		if (!empty($_POST['bcAliasCreated'])) {
			foreach ($_POST['bcAliasCreated'] as $check) {
				mysql_query("UPDATE emailAliasesEmp SET businessCardCreated = 1 WHERE idCon = $idCon AND idAliase = $check;");
			}
		}

		echo "<meta http-equiv='refresh' content='0;url=index.php?p=emp&idE=$idE&idCon=$idCon&upn=$upn&tab=0'>";
	}

	?>
	<center>

		<h2>Business Cards</h2>

		<?php
		$checkBcAliasesNeeded = mysql_query("SELECT * FROM emailAliasesEmp AS eae INNER JOIN emailAliases AS ea ON eae.idAliase=ea.idAliase INNER JOIN labels AS lab ON ea.idLab = lab.idLab WHERE eae.idCon= \"$idCon\" AND eae.businessCardNeeded = 1") or die (mysql_error());

		 if (($contract['businessCardNeeded'] == 1) || (mysql_num_rows($checkBcAliasesNeeded) > 0)) { ?>
			<h4 class="text-info" align="center">
				<img src="img/cardNeeded.png"/> This user needs at least one business card</h4>
		<?php } else { ?>
			<h4 class="text-info" align="center">
				<img src="img/cardNotNeeded.png"/> This user currently doesn't need a business card</h4>
		<?php } ?>

<!--		<table class="table">
			<tr>
				<td>
					<address>
						<strong>TBWA</strong><br>
						Kroonlaan 165 avenue de la Couronne<br>
						B-1050 Brussels, Belgium<br>
						VAT BE0414.235.827<br>
					</address>
				</td>
				<td>
					<address>
						<strong>TEQUILA</strong><br>
						Kroonlaan 165 avenue de la Couronne<br>
						B-1050 Brussels, Belgium<br>
						VAT BE0455.891.783
					</address>
				</td>
				<td>
					<address>
						<strong>E-GRAPHICS</strong><br>
						Kroonlaan 165 avenue de la Couronne<br>
						B-1050 Brussels, Belgium<br>
						VAT BE0444.878.226
					</address>
				</td>
				<td>
					<address>
						<strong>MARKETING & ENTERTAINMENT</strong><br>
						Vorstermanstraat 14A<br>
						B-2000 Antwerp, Belgium<br>
						VAT BE0459.390.317
					</address>
				</td>
			</tr>
		</table>
-->
		<form action="index.php?p=emp&idE=<?php echo $idE; ?>&upn=<?php echo $upn; ?>&idCon=<?php echo $idCon; ?>&updateBC" method="POST">

			<table class="userStartForm" width="700px">
				<tr>
					<th class="title">
						<center>Select for wich email domains a Business Card is needed (and when created between brackets)</center>
					</th>
				</tr>
				<tr class="emailHide">
					<td>
						<div class="scrollGroup" id="usfEmailDom" style="height:200px;">
							<?php
								if ($contract["businessCardNeeded"]) {
									$checked = "checked";
								} else {
									$checked = "";
								}
								if ($contract["businessCardCreated"]) {
									$checkedcreated = "checked";
								} else {
									$checkedcreated = "";
								}
							?>
							<?php
								if ($contract["primaryEmail"] != "") {
							?>
							<strong>Primary Email : </strong><br/>
							<label><input type="checkbox" id="bcPrimaryNeeded" name="bcPrimaryNeeded[]" value="1" <?php echo $checked; ?> /> <?php echo $contract['primaryEmail']; ?>
							</label> (
							<input type="checkbox" id="bcPrimaryCreated" name="bcPrimaryCreated[]" value="1" <?php echo $checkedcreated; ?> /> created)<br/>
							<?php
							}
							?>
							<?php
								$checkEmailAliasesEmp = mysql_query("SELECT * FROM emailAliasesEmp AS eae INNER JOIN emailAliases AS ea ON eae.idAliase=ea.idAliase INNER JOIN labels AS lab ON ea.idLab = lab.idLab WHERE eae.idCon= \"$idCon\"") or die (mysql_error());
								if (mysql_num_rows($checkEmailAliasesEmp) > 0) {
									echo "<strong>Other(s) :";
									echo "<br/>";
								}
								while ($listAliases = mysql_fetch_array($checkEmailAliasesEmp)) {
									if ($listAliases['businessCardNeeded'] == 1) {
										$checked = "checked";
									} else {
										$checked = "";
									}
									if ($listAliases['businessCardCreated'] == 1) {
										$checkedcreated = "checked";
									} else {
										$checkedcreated = "";
									}
									?>
									<label>
										<input type="checkbox" id="bcAliasNeeded" name="bcAliasNeeded[]" value="<?php echo $listAliases['idAliase']; ?>" <?php echo $checked; ?> /> <?php echo $listAliases['email']; ?> (
										<input type="checkbox" id="bcAliasCreated" name="bcAliasCreated[]" value="<?php echo $listAliases['idAliase']; ?>" <?php echo $checkedcreated; ?> /> created)
									</label><br/>
								<?php
								}
							?>
						</div>
					</td>
				</tr>
			</table>
			<p><br/>
				<button class="btn btn-default btn-sml" title="Save"><img src='img/saveXS.png'/> Save</button>
			</p>
		</form>
	</center>