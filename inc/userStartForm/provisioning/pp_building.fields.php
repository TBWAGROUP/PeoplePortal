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


 if (isset($_GET['updateBuilding'])) {
	mysql_query("UPDATE contracts SET badgeNr = \"$_POST[badgeNr]\", seating = \"$_POST[seating]\", badgeAccessLevel = \"$_POST[badgeAccessLevel]\" WHERE idCon = $idConUrl") or die (mysql_error());


	approvalUp($currentIdE, $idGroup, $idConUrl);

	echo "<h4><img src='img/ajax-loader.gif' /> Updating changes...</h4><meta http-equiv='refresh' content='0;url=index.php?p=emp&idE=$idEurl&idCon=$idConUrl&upn=$upn'>";
} else {
	?>

	<center>

		<form method="POST" action='index.php?p=fields_pp_building&idE=<?php echo $idEurl; ?>&idCon=<?php echo $idConUrl; ?>&upn=<?php echo $contract['upn']; ?>&updateBuilding&tab=2' name="usf1">

			<h2>Building information</h2>
			<table class="userStartForm">
				<tr>
					<th>Badge Number</th>
					<td>
						<input name="badgeNr" type="text" tabindex=10 size="20" value="<?php echo $contract['badgeNr']; ?>"/>
					</td>
					<th>Badge Access Level</th>
					<td>
						<select name="badgeAccessLevel" id="parking">
							<option value="<?php echo $contract['badgeAccessLevel']; ?>"><?php echo $contract['badgeAccessLevel']; ?></option>
							<option value="Admin">Admin</option>
							<option value="Employee">Employee</option>
							<option value="Intern">Intern</option>
						</select>
					</td>
				</tr>
				<tr>
					<th>Seating</th>
					<td colspan=3>
						<input name="seating" type="text" tabindex=20 size="20" value="<?php echo $contract['seating']; ?>"/>
					</td>
				</tr>
			</table>


			<p><br/>
				<button class="btn btn-default btn-sml" title="Save"><img src='img/saveXS.png'/> Save</button>
			</p>
		</form>
	</center>

<?php
} // end else isset update
?>
