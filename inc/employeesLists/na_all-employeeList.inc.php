<!--
This script shows the active employee list to all john does directly from jtable/employeesListQueries.php

Included by:
index.php

Hrefs pointing here:

Requires:

Includes:
jtable/employeesListQueries.php

Form actions:
index.php?p=empList&action=search
index.php?p=empList&action=list

-->

<script>
	$(document).ready(function(){
		$(".cleanMess").hide();
		$(".clean").click(function()
		{
			$(".cleanMess").show();
			$(".cleanBtn").hide();
		});
	});
</script>
<script src="../../SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="../../SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />


<center>
<div >
	<form method="GET" width="100px" action="index.php?p=empList&action=search" name="searchForm" class="form-inline">
		<input type="text"  name="searchF" class="form-control" size=50 value="<?php if (isset ($_GET['searchF'])) { echo $_GET['searchF']; } ?>"  placeholder="User search" autofocus/>
		<input type="hidden" name="p" value="empList" />
	</form>
</div>
</center>
<br />
<?php
	if (isset($_GET['tab'])) { $defaultTab = $_GET['tab']; } else { $defaultTab = 0; }

	// count all users
	$queryActive = mysql_query("SELECT * FROM employees WHERE ppAccountStatut = 0;");
	$activeUsersCount = mysql_num_rows($queryActive);
	// count permanent users
	$queryPermanent = mysql_query("SELECT * FROM employees INNER JOIN contracts AS cont ON employees.contract = cont.idCon WHERE ppAccountStatut = 0 AND ( cont.empType = 'Administrator' OR cont.empType = 'Employee' OR cont.empType = 'Contractor Fixed' OR empType = 'Contractor Timebased' );");
	$permanentUsersCount = mysql_num_rows($queryPermanent);
	// count temporary users
	$queryTemporary = mysql_query("SELECT * FROM employees INNER JOIN contracts AS cont ON employees.contract = cont.idCon WHERE ppAccountStatut = 0 AND cont.empType = 'Employee temporary';");
	$temporaryUsersCount = mysql_num_rows($queryTemporary);
	// count freelance users
	$queryFreelance = mysql_query("SELECT * FROM employees INNER JOIN contracts AS cont ON employees.contract = cont.idCon WHERE ppAccountStatut = 0 AND cont.empType = 'Freelance';");
	$freelanceUsersCount = mysql_num_rows($queryFreelance);
	// count intern users
	$queryIntern = mysql_query("SELECT * FROM employees INNER JOIN contracts AS cont ON employees.contract = cont.idCon WHERE ppAccountStatut = 0 AND cont.empType = 'Intern';");
	$internUsersCount = mysql_num_rows($queryIntern);
	$queryPHD = mysql_query("SELECT * FROM employees INNER JOIN contracts AS cont ON employees.contract = cont.idCon WHERE ppAccountStatut = 3 AND cont.empType = 'PHD';");
	$PHDUsersCount = mysql_num_rows($queryPHD);

if (isset ($_GET['searchF'])) 
{ 
	$action="search";
	echo "<p align='center'><a href='index.php?p=empList'>View All</a></p>";
} 
else { 
	$action="listAllStaff";
}
?>

<div id="TabbedPanels1" class="TabbedPanels">
	<div id="TabbedPanelsUserLists" class="TabbedPanels">
		<ul class="TabbedPanelsTabGroup">
			<li class="TabbedPanelsTab" tabindex="10"><img src="img/allS.png"/> All Staff (<?php echo $activeUsersCount; ?>)</li>
			<li class="TabbedPanelsTab" tabindex="20"><img src="img/userS.png"/> Permanent Staff (<?php echo $permanentUsersCount; ?>)</li>
			<li class="TabbedPanelsTab" tabindex="30"><img src="img/userTemporaryS.png"/> Temporary Staff (<?php echo $temporaryUsersCount; ?>)</li>
			<li class="TabbedPanelsTab" tabindex="40"><img src="img/userFreelanceS.png"/> Freelance Staff (<?php echo $freelanceUsersCount; ?>)</li>
			<li class="TabbedPanelsTab" tabindex="50"><img src="img/userInternS.png"/> Interns (<?php echo $internUsersCount; ?>)</li>
			<li class="TabbedPanelsTab" tabindex="50"><img src="img/userInternS.png"/> PHD (<?php echo $PHDUsersCount; ?>)</li>
			<li class="TabbedPanelsTab" tabindex="50"><img src="img/userInternS.png"/> Labels and Departments</li>
		</ul>

		<div class="TabbedPanelsContentGroup">
			<div class="TabbedPanelsContent"><?php include("inc/employeesLists/na_all/usersAllStaff.inc.php"); ?></div>
			<div class="TabbedPanelsContent"><?php include("inc/employeesLists/na_all/usersPermanentStaff.inc.php"); ?></div>
			<div class="TabbedPanelsContent"><?php include("inc/employeesLists/na_all/usersTemporaryStaff.inc.php"); ?></div>
			<div class="TabbedPanelsContent"><?php include("inc/employeesLists/na_all/usersFreelanceStaff.inc.php"); ?></div>
			<div class="TabbedPanelsContent"><?php include("inc/employeesLists/na_all/usersInternStaff.inc.php"); ?></div>
			<div class="TabbedPanelsContent"><?php include("inc/employeesLists/na_all/usersPHDStaff.inc.php"); ?></div>
			<div class="TabbedPanelsContent">

				<div id="TabbedPanelsLabels" class="TabbedPanels">

					<ul class="TabbedPanelsTabGroup">
						<li class="TabbedPanelsTab" tabindex="50"><img src="img/userInternS.png"/> Labels</li>
						<li class="TabbedPanelsTab" tabindex="50"><img src="img/userInternS.png"/> Departments</li>
					</ul>

					<div class="TabbedPanelsContentGroup">
						<div class="TabbedPanelsContent"><?php echo "Here be Labels"; ?></div>
						<div class="TabbedPanelsContent"><?php echo "Here be Departments"; ?></div>
					</div>

				</div>

			</div>
		</div>
	</div>

</div>




<script type="text/javascript">
	var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1", {defaultTab:<?php echo $defaultTab; ?>});
	var TabbedPanelsUserLists = new Spry.Widget.TabbedPanels("TabbedPanelsUserLists", {defaultTab:<?php echo $defaultTab; ?>});
	var TabbedPanelsLabels = new Spry.Widget.TabbedPanels("TabbedPanelsLabels", {defaultTab:<?php echo $defaultTab; ?>});
</script>
