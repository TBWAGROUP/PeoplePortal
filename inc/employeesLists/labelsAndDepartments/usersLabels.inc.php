<!--
This script prepares and shows Permanent Employees (Employees, Contractor Fixed, Contractor Timebased, Administrator)

Included by:

Hrefs pointing here:

Requires:

Includes:
index.php?p=emp
jtable/employeeListQueries.php?action=listInternStaff

Form actions:

-->
<?php

	$queryLabels = mysql_query("
		SELECT * FROM labels AS lab
		WHERE ( lab.hidden = 0 AND lab.labelName = 'TBWA')
		ORDER BY lab.labelName ASC
	") or die (mysql_error());

	while ($label = mysql_fetch_array($queryLabels)) {

		<
		div id = "PeopleTableContainer" style = "width: 100%;" ></div >

		echo "<br" . $queryLabels . "<br>"
		echo "<br" . $label . "<br>"

		?>


	<?php
	}
?>
