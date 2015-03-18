<?php

	if (isset($_GET['idCon'])) {
		$idCon = mysql_real_escape_string($_GET['idCon']);
	} else {
		$idCon = $idCon;
	}
	$idE = mysql_real_escape_string($_GET['idE']);
	$upn = mysql_real_escape_string($_GET['upn']);
	$queryCont = mysql_query("
												SELECT * FROM employees AS emp
												INNER JOIN contracts AS cont ON cont.idE = emp.idE
												INNER JOIN parking AS park on park.contracts_idCon = cont.idCon
												WHERE cont.idCon = $idCon
											") or die (mysql_error());
	$contract = mysql_fetch_array($queryCont);

	$queryPark = mysql_query("SELECT * FROM parking WHERE contracts_idCon = $idConUrl") or die (mysql_error());
	$parking = mysql_fetch_array($queryPark);
	$nbrPark = mysql_num_rows($queryPark);

	$queryGroupId = mysql_query("SELECT idGroup FROM groups WHERE groupName = \"$pp_carfleet\"") or die (mysql_error());
	$idGroup = array_shift(mysql_fetch_array($queryGroupId));


	if (isset($_GET['updateCarFleet'])) {
		$checkPark = mysql_query("SELECT * FROM parking WHERE contracts_idCon = $idCon");
		if (mysql_num_rows($checkPark) > 0) {
			mysql_query("UPDATE parking SET comesBy = \"$_POST[comesBy]\", nrPlaat = \"$_POST[nrPlaat]\", parking = \"$_POST[parking]\", parkingNr = \"$_POST[parkingNr]\" WHERE contracts_idCon = $idCon") or die (mysql_error());
		} else {
			mysql_query("INSERT INTO parking (contracts_idCon, comesBy, nrPlaat, parking, parkingNr) VALUES ($idCon, \"$_POST[comesBy]\", \"$_POST[nrPlaat]\", \"$_POST[parking]\", \"$_POST[parkingNr]\")") or die (mysql_error());
		}
		mysql_query("UPDATE contracts SET carReturnDate= \"$_POST[carReturnDate]\", parkingRemarks = \"$_POST[parkingRemarks]\" WHERE idCon = $idCon") or die (mysql_error());

		approvalUp($currentIdE, $idGroup, $idConUrl);

		echo "<h4><img src='img/ajax-loader.gif' /> Updating changes...</h4><meta http-equiv='refresh' content='1;url=index.php?p=emp&idE=$idE&idCon=$idCon&upn=$upn&tab=3'>";
	} else {
		?>


		<center>
			<h2>Car fleet information</h2>

			<p>&nbsp;</p>

			<form method="POST" action='index.php?p=emp&idE=<?php echo $idE; ?>&idCon=<?php echo $idCon; ?>&upn=<?php echo $upn; ?>&updateCarFleet&tab=3' name="usf1">

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
							<input type="text" name="nrPlaat" id="parking" value="<?php echo $contract['nrPlaat']; ?>" tabindex=30/>
						</td>
					</tr>
					<tr>
						<th>Car return Date</th>
						<td colspan=3>
							<input name="carReturnDate" type="text" tabindex=10 size="20" class='datepicker' value="<?php echo $contract['carReturnDate']; ?>"/>
						</td>
					</tr>
					<tr>
						<th>Parking Location</th>
						<td>
							<select name="parking" id="parking">
								<option value="<?php echo $contract['parking']; ?>"><?php echo $contract['parking']; ?></option>
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
							<input type="text" name="parkingNr" id="parking" value="<?php echo $contract['parkingNr']; ?>" tabindex=30/>
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
