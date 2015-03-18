<script>

	$(function () {
		$(".datepicker").datepicker({
			dateFormat: 'dd/mm/yy',
			firstDay: 1,
			changeMonth: true,
			changeYear: true,
			yearRange: "1900:2100",
			constrainInput: true,
			showWeek: true
		});
	});
</script>

<?php
	// $idConUrl = mysql_real_escape_string($_GET['idCon']);
	$idEurl = mysql_real_escape_string($_GET['idE']);
	$upn = mysql_real_escape_string($_GET['upn']);
	if (isset($_GET['idCon'])) {
		$idCon = mysql_real_escape_string($_GET['idCon']);
	} else {
		$idCon = $idCon;
	}

	$queryCont = mysql_query("
	SELECT * FROM employees AS emp
												INNER JOIN contracts AS cont ON cont.idE = emp.idE
												WHERE cont.idCon = $idConUrl
											") or die (mysql_error());

	$contract = mysql_fetch_array($queryCont);


	$queryGroupId = mysql_query("SELECT idGroup FROM groups WHERE groupName = \"$pp_planning\"") or die (mysql_error());
	$idGroup = array_shift(mysql_fetch_array($queryGroupId));
	$queryGroupStatut = mysql_query("SELECT * FROM approvals WHERE idGroup = $idGroup AND idCon = $idConUrl") or die (mysql_error());
	$checkApprov = mysql_num_rows($queryGroupStatut);

?>




<?php if (isset($_GET['updatePlanning'])) {
	if ($_POST['startDate'] != "") {
		$startDateTS = convertTimestamp($_POST['startDate']);
	} else {
		$startDateTS = "";
	}
	if (isset ($_POST['endDate'])) {
		if ($_POST['endDate'] != "") {
			$endDateTS = convertTimestamp($_POST['endDate']);
		} else {
			$endDateTS = "";
		}
	} else {
		$endDateTS = "";
	}

	echo "POST startDate " . $_POST[startDate] . "<br>";
	echo "StartDateTS " . $startDateTS . "<br>";
	echo "POST endDate " . $_POST[endDate] . "<br>";
	echo "EndDateTS " . $endDateTS . "<br>";

	mysql_query("UPDATE contracts SET

	startDate = \"$_POST[startDate]\",
	endDate = \"$_POST[endDate]\",
	operationalEndDate = \"$_POST[endDate]\",
	disableAccountDate = \"$_POST[endDate]\",
	materialReturnDate = \"$_POST[endDate]\",
	mobilePhoneReturnDate = \"$_POST[endDate]\",
	startDateTS = \"$startDateTS\",
	endDateTS = \"$endDateTS\",
	operationalEndDateTS = \"$endDateTS\",
	disableAccountDateTS = \"$endDateTS\",
	materialReturnDateTS = \"$endDateTS\",
	mobilePhoneReturnDateTS = \"$endDateTS\"

	WHERE idCon = $idConUrl") or die (mysql_error());

	approvalUp($currentIdE, $idGroup, $idConUrl);

	echo "<h4><img src='img/ajax-loader.gif' /> Updating changes...</h4><meta http-equiv='refresh' content='1;url=index.php?p=emp&idE=$idE&idCon=$idCon&upn=$upn&tab=2'>";
} else {
	?>




	<center>
		<h2>Planning information</h2>

		<form method="POST" action='index.php?p=emp&idE=<?php echo $idEurl; ?>&idCon=<?php echo $idConUrl; ?>&upn=<?php echo $upn; ?>&updatePlanning&tab=2' name="usf1">
			<table class="userStartForm">
				<tr>
					<th>Start Date</th>
					<td>
						<input type="text" size="20" name="startDate" class="datepicker" value='<?php echo $contract['startDate']; ?>' tabindex=160/>
					</td>
				</tr>

				<?php
					if (($contract['empType'] == "Intern") || ($contract['empType'] == "Freelance")) { ?>
					<tr>
						<th>End Date</th>
						<td>
							<input type="text" size="20" name="endDate" id="endDate" class="datepicker" value='<?php echo $contract['endDate']; ?>' tabindex=170/>
						</td>
					</tr>
				<?php } else { ?>
				<tr>
					<th>End Date</th>
					<td>
						<?php echo $contract['endDate'];
							} ?>
					</td>
				</tr>
			</table>
			<p><br/>
			<button class="btn btn-default btn-sml" title="Save"><img src='img/saveXS.png'/> Save</button>
			</p>
		</form>
	</center>

<?php } ?>
