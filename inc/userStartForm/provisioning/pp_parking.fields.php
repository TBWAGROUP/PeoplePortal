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


	if (isset($_GET['updateParking'])) {
		mysql_query("UPDATE contracts SET parkingRemarks = \"$_POST[parkingRemarks]\" WHERE idCon = $idConUrl") or die (mysql_error());
		if (mysql_num_rows($queryPark) == 0) {
			mysql_query("INSERT INTO parking (contracts_idCon, parking, comesBy, parkingNr, nrPlaat) VALUES ($idConUrl, \"$_POST[parking]\", \"$_POST[comesBy]\", \"$_POST[parkingNr]\", \"$_POST[nrPlaat]\")") or die (mysql_error());
		} else {
			mysql_query("UPDATE parking SET comesBy = \"$_POST[comesBy]\", parking = \"$_POST[parking]\", parkingNr = \"$_POST[parkingNr]\", nrPlaat = \"$_POST[nrPlaat]\" WHERE contracts_idCon = $idConUrl") or die (mysql_error());
		}

		approvalUp($currentIdE, $idGroup, $idConUrl);

		echo "<h4><img src='img/ajax-loader.gif' /> Updating changes...</h4><meta http-equiv='refresh' content='0;url=index.php?p=emp&idE=$idEurl&idCon=$idConUrl&upn=$upn'>";
	} else {
		?>

		<center>

			<form method="POST" action='index.php?p=fields_pp_parking&idE=<?php echo $idEurl; ?>&idCon=<?php echo $idConUrl; ?>&upn=<?php echo $contract['upn']; ?>&updateParking&tab=2' name="usf1">


				<h2>Parking information</h2>
				<table class="userStartForm">
					<tr>
						<th>Comes By</th>
						<td>
							<select name="comesBy" id="parking">
								<option value="<?php echo $parking['comesBy']; ?>"><?php echo $parking['comesBy']; ?></option>
								<option value="Private Car">Private Car</option>
								<option value="Private Moto">Private Moto</option>
								<option value="Company Car">Company Car</option>
								<option value="Company Moto">Company Moto</option>
								<option value="Foot">Foot</option>
								<option value="Public Transport">Public Transport</option>
							</select>
						</td>
						<th>License Plate Number</th>
						<td>
							<input type="text" name="nrPlaat" id="parking" value="<?php echo $parking['nrPlaat']; ?>" tabindex=30/>
						</td>
					</tr>
					<tr>
						<th>Parking Location</th>
						<td>
							<select name="parking" id="parking">
								<option value="<?php echo $parking['parking']; ?>"><?php echo $parking['parking']; ?></option>
								<option value="Kroonlaan 1">Kroonlaan 1</option>
								<option value="Kroonlaan 2">Kroonlaan 2</option>
								<option value="Brico">Brico</option>
								<option value="Corderie">Corderie</option>
								<option value="Antwerpen">Antwerpen</option>
								<option value="Brussel">Brussel</option>
								<option value="Wijgmaal">Wijgmaal</option>
							</select>
						</td>
						<th>Parking Number</th>
						<td>
							<input type="text" name="parkingNr" id="parking" value="<?php echo $parking['parkingNr']; ?>" tabindex=30/>
						</td>
					</tr>
					<tr>
						<th>Parking Remarks</th>
						<td colspan=3>
							<input type="text" name="parkingRemarks" size=50 id="parking" value="<?php echo $contract['parkingRemarks']; ?>" tabindex=30/>
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
