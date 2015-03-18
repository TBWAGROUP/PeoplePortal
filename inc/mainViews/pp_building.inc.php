<!--
This script lists new users for practical info, mainly needed business card, for PP building people

Included by:

Hrefs pointing here:

Requires:

Includes:

Form actions:

-->

<?php
	// display all users who need business cards and not created yet
	$queryBusinessCardNeed = mysql_query("
	SELECT firstname, lastname, email, labelName, empType, cont.businessCardNeeded, cont.businessCardCreated, cont.idE  FROM contracts AS cont
				LEFT JOIN employees AS emp ON emp.idE = cont.idE
				LEFT JOIN labels AS lab ON lab.idLab = cont.idLab
				LEFT JOIN emailAliases AS ea ON ea.idLab = lab.idLab
	WHERE ( cont.businessCardNeeded = 1 AND ( cont.businessCardCreated = 0 OR cont.businessCardCreated IS NULL) )

	UNION

	SELECT firstname, lastname, email, labelName, empType, eae.businessCardNeeded, eae.businessCardCreated, cont.idE FROM emailAliasesEmp AS eae
				LEFT JOIN contracts AS cont ON cont.idCon = eae.idCon
				LEFT JOIN employees AS emp ON emp.idE = cont.idE
				LEFT JOIN labels AS lab ON lab.idLab = cont.idLab
				LEFT JOIN emailAliases AS ea ON ea.idAliase = eae.idAliase
	WHERE ( eae.businessCardNeeded = 1 AND ( eae.businessCardCreated = 0 OR eae.businessCardCreated IS NULL) )

	ORDER BY firstname ASC, lastname ASC
				") or die(mysql_error());

	echo "<h3 class='text-success'>Users who need business cards</h3>";


	echo "<table class='table table-condensed'>";
	echo "<tr>
				<th colspan='11'><center>Business Card</center></th>
			</tr>
	";
	echo "<tr class='active'>";
	echo "<th>Needed</th>";
	echo "<th>Created</th>";
	echo "<th>Name</th>";
	echo "<th>Label</th>";
	echo "<th>Domain</th>";
	echo "<th>Type</th>";
	echo "</tr>";
	$toActivate = 0;
	while ($businessCardNeed = mysql_fetch_array($queryBusinessCardNeed)) {
		$idCon = $businessCardNeed['idCon'];
		$queryGroupId = mysql_query("SELECT idGroup FROM groups WHERE groupName = \"$pp_building\"") or die (mysql_error());
		$idGroup = array_shift(mysql_fetch_array($queryGroupId));
		$queryGroupStatut = mysql_query("SELECT * FROM approvals WHERE idGroup = \"$idGroup\" AND idCon = \"$idCon\" ") or die (mysql_error());

		if ($businessCardNeed['businessCardNeeded'] == 1) {
			$businessN = "ok";
			if ($businessCardNeed['businessCardCreated'] == 1) {
				$businessC = "<img src='img/okS.png' />";
			} else {
				$businessC = "<img src='img/noOkS.png' />";
			}
		} else {
			$businessN = "noOk";
			$businessC = " - ";
		}

		$idCon = $businessCardNeed['idCon'];
		$queryPark = mysql_query("SELECT * FROM parking WHERE contracts_idCon = \"$idCon\"") or die (mysql_error());
		$parking = mysql_fetch_array($queryPark);

		echo "<tr class='hover'>";
		echo "<td><img src='img/" . $businessN . "S.png' /></td>";
		echo "<td>" . $businessC . "</td>";
		echo "<td><a href='index.php?p=emp&tab=3&idE=" . $businessCardNeed['idE'] . "&tab=2' >" . $businessCardNeed['firstname'] . " " . $businessCardNeed['lastname'] . "</a></td>";
		echo "<td>" . $businessCardNeed['labelName'] . "</td>";
		echo "<td>" . $businessCardNeed['email'] . "</td>";
		echo "<td>" . $businessCardNeed['empType'] . "</td>";
		echo "</tr>";
	}
	echo "</table>";
?>


<hr/>

<?php
	// display all users who need business cards and not created yet
	$queryBadgeNeed = mysql_query("
SELECT * FROM contracts AS cont
				LEFT JOIN employees AS emp ON emp.idE = cont.idE
				INNER JOIN labels AS lab ON lab.idLab = cont.idLab
				LEFT JOIN parking AS park ON park.contracts_idCon = cont.idCon
				WHERE ppAccountStatut = 0 AND (endDateTS > CURRENT_DATE OR endDateTS = 0 OR endDateTS IS NULL) AND ( cont.badgeNr = '0' OR (cont.badgeNr != '' AND length(cont.badgeAccessLevel) = 0))
				ORDER BY firstname ASC, lastname ASC
") or die(mysql_error());

	echo "<h3 class='text-success'>Users with missing badge info</h3>";


	echo "<table class='table table-condensed'>";
	echo "<tr>
		<th colspan='11'><center>Badges</center></th>
	</tr>
	";
	echo "<tr class='active'>";
	echo "<th>Needed</th>";
	echo "<th>Badge Nr Assigned</th>";
	echo "<th>Name</th>";
	echo "<th>Label</th>";
	echo "<th>Type</th>";
	echo "<th>Badge Nr</th>";
	echo "<th>Badge Access</th>";
	echo "</tr>";
	$toActivate = 0;
	while ($badgeNeed = mysql_fetch_array($queryBadgeNeed)) {
		$idCon = $badgeNeed['idCon'];
		$queryGroupId = mysql_query("SELECT idGroup FROM groups WHERE groupName = \"$pp_building\"") or die (mysql_error());
		$idGroup = array_shift(mysql_fetch_array($queryGroupId));
		$queryGroupStatut = mysql_query("SELECT * FROM approvals WHERE idGroup = \"$idGroup\" AND idCon = \"$idCon\" ") or die (mysql_error());

		if (strlen($badgeNeed['badgeNr']) > 0) {
			$badgeN = "ok";
			if (strlen($badgeNeed['badgeNr']) > 1) {
				$badgeC = "<img src='img/okS.png' />";
			} else {
				$badgeC = "<img src='img/noOkS.png' />";
			}
		} else {
			$badgeN = "notNeeded";
			$badgeC = " - ";
		}

		$idCon = $badgeNeed['idCon'];
		$queryPark = mysql_query("SELECT * FROM parking WHERE contracts_idCon = \"$idCon\"") or die (mysql_error());
		$parking = mysql_fetch_array($queryPark);

		echo "<tr class='hover'>";
		echo "<td><img src='img/" . $badgeN . "S.png' /></td>";
		echo "<td>" . $badgeC . "</td>";
		echo "<td><a href='index.php?p=emp&idE=" . $badgeNeed['idE'] . "&tab=1' >" . $badgeNeed['firstname'] . " " . $badgeNeed['lastname'] . "</a></td>";
		echo "<td>" . $badgeNeed['labelName'] . "</td>";
		echo "<td>" . $badgeNeed['empType'] . "</td>";
		echo "<td>" . $badgeNeed['badgeNr'] . "</td>";
		echo "<td>" . $badgeNeed['badgeAccessLevel'] . "</td>";
		echo "</tr>";
	}
	echo "</table>";
?>


<hr/>
