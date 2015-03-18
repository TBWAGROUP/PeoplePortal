<!--
This script shows tabs, who's contents are defined in subdir pp_admin, who parameterizes jtable/usersStatutQueries.php

Included by:

Hrefs pointing here:

Requires:

Includes:

Form actions:

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
<span class="syncMess">
	<p align="center" class="text-default"><br /><strong><img src="img/ajax-loader.gif" /> Update Active Directoy... This process can take a while...</strong></p></span>
</span>



<?php
	if (isset($_GET['tab'])) { $defaultTab = $_GET['tab']; } else { $defaultTab = 0; }

	// count all users
	$queryAll = mysql_query("SELECT * FROM employees");
	$allUsersCount = mysql_num_rows($queryAll);
	// count active users
	$queryActive = mysql_query("SELECT * FROM employees WHERE ppAccountStatut = 0;");
	$activeUsersCount = mysql_num_rows($queryActive);	
	// count trashed users
	$queryTrashed = mysql_query("SELECT * FROM employees WHERE ppAccountStatut = 1 OR  ppAccountStatut = 403;");
	$trashedUsersCount = mysql_num_rows($queryTrashed);
	// count frozen users
	$queryFreeze = mysql_query("SELECT * FROM employees WHERE ppAccountStatut = 2;");
	$freezedUsersCount = mysql_num_rows($queryFreeze);	
	// count stand users
	$queryStand = mysql_query("SELECT * FROM employees WHERE ppAccountStatut = 3;");
	$standUsersCount = mysql_num_rows($queryStand);
	// count archived users
	$queryArch = mysql_query("SELECT * FROM employees WHERE ppAccountStatut = 4;");
	$archUsersCount = mysql_num_rows($queryArch);
	// count USF users
	$queryUsf = mysql_query("SELECT * FROM employees WHERE ppAccountStatut = 5;");
	$usfUsersCount = mysql_num_rows($queryUsf);

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



	// search filters :
	if (!isset($_GET['filter']))
	{
		$filter =  "";
	}
	else {
		$filter =  $_GET['filter'];
	}
	
?>




<center>
<div >
	<form method="GET" width="100px" action="index.php?p=empList&action=search" name="searchForm" class="form-inline">
		<input type="text"  name="searchF" class="form-control" size=50 value="<?php if (isset ($_GET['searchF'])) { echo $_GET['searchF']; } ?>"  placeholder="Global user search" autofocus />
		<input type="hidden" name="p" value="empList" />
		<a class="btn btn-default" href="index.php?p=globalSync" title="Global Synchronisation" ><img src="img/pull.png" /> Global pull from AD</a>
</form>
</div>
</center>
<br />
<?php 

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

			<ul class="TabbedPanelsTabGroup">
				<li class="TabbedPanelsTab" tabindex="0"><img src="img/allS.png" /> Admin View </li>
				<li class="TabbedPanelsTab" tabindex="0"><img src="img/allS.png" /> PP View </li>
				<li class="TabbedPanelsTab" tabindex="0"><img src="img/allS.png" /> User View </li>
				<div style="clear: both; "></div>
			</ul>


			<div class=""TabbedPanelsContentGroup">

				<div class="TabbedPanelsContent">
					<div id="TabbedPanelsAdminView" class="TabbedPanels">
						<ul class="TabbedPanelsTabGroup">
							<li class="TabbedPanelsTab" tabindex="10"><img src="img/allS.png" /> Employees (<?php echo $allUsersCount; ?>)</li>
							<li class="TabbedPanelsTab" tabindex="10"><img src="img/userS.png" /> Active (<?php echo $activeUsersCount; ?>)</li>
							<li class="TabbedPanelsTab" tabindex="10"><img src="img/hiddenS.png" /> Standalone (<?php echo $standUsersCount; ?>)</li>
							<li class="TabbedPanelsTab" tabindex="10"><img src="img/frozenS.png" /> Frozen (<?php echo $freezedUsersCount; ?>)</li>
							<li class="TabbedPanelsTab" tabindex="10"><img src="img/archiveS.png" /> Archived  (<?php echo $archUsersCount; ?>)</a></li>
							<li class="TabbedPanelsTab" tabindex="10"><img src="img/trashS.png" /> Users trash (<?php echo $trashedUsersCount; ?>)</li>
							<li class="TabbedPanelsTab" tabindex="10"><img src="img/userformS.png" /> User start form flow  (<?php echo $usfUsersCount; ?>)</li>
						</ul>
						<div class="TabbedPanelsContentGroup">
							<div class="TabbedPanelsContent"><?php include ("inc/employeesLists/pp_admin/usersAll.inc.php"); ?></div>
							<div class="TabbedPanelsContent"><?php include ("inc/employeesLists/pp_admin/usersActive.inc.php"); ?></div>
							<div class="TabbedPanelsContent"><?php include ("inc/employeesLists/pp_admin/usersStandalone.inc.php"); ?></div>
							<div class="TabbedPanelsContent"><?php include ("inc/employeesLists/pp_admin/usersFrozen.inc.php"); ?></div>
							<div class="TabbedPanelsContent"><?php include ("inc/employeesLists/pp_admin/usersArchived.inc.php"); ?></div>
							<div class="TabbedPanelsContent"><?php include ("inc/employeesLists/pp_admin/usersTrash.inc.php"); ?></div>
							<div class="TabbedPanelsContent"><?php include ("inc/employeesLists/pp_admin/usersStartForm.inc.php"); ?></div>
						</div>
					</div>
				</div>

				<div class="TabbedPanelsContent">
					<div id="TabbedPanelsPPView" class="TabbedPanels">
						<ul class="TabbedPanelsTabGroup">
							<li class="TabbedPanelsTab" tabindex="10"><img src="img/allS.png" /> HR View</li>
							<li class="TabbedPanelsTab" tabindex="10"><img src="img/allS.png" /> Planning View</li>
							<li class="TabbedPanelsTab" tabindex="10"><img src="img/allS.png" /> Finance View</li>
							<li class="TabbedPanelsTab" tabindex="10"><img src="img/allS.png" /> Building View</li>
							<li class="TabbedPanelsTab" tabindex="10"><img src="img/allS.png" /> Carfleet View</li>
							<li class="TabbedPanelsTab" tabindex="10"><img src="img/allS.png" /> FaceBook View</li>
						</ul>
						<div class="TabbedPanelsContentGroup">
							<div class="TabbedPanelsContent"><?php include ("inc/employeesLists/pp_specific/pp_hr-actionList.inc.php"); ?></div>
							<div class="TabbedPanelsContent"><?php include ("inc/employeesLists/pp_specific/pp_planning-actionList.inc.php"); ?></div>
							<div class="TabbedPanelsContent"><?php include ("inc/employeesLists/pp_specific/pp_finance-actionList.inc.php"); ?></div>
							<div class="TabbedPanelsContent"><?php include ("inc/employeesLists/pp_specific/pp_building-actionList.inc.php"); ?></div>
							<div class="TabbedPanelsContent"><?php include ("inc/employeesLists/pp_specific/pp_carfleet-actionList.inc.php"); ?></div>
							<div class="TabbedPanelsContent"><?php include ("inc/employeesLists/pp_specific/pp_facebook-actionList.inc.php"); ?></div>
						</div>
					</div>
				</div>

				<div class="TabbedPanelsContent">
					<div id="TabbedPanelsUserView" class="TabbedPanels">
						<ul class="TabbedPanelsTabGroup">
							<li class="TabbedPanelsTab" tabindex="10"><img src="img/allS.png"/> All Staff (<?php echo $activeUsersCount; ?>)</li>
							<li class="TabbedPanelsTab" tabindex="20"><img src="img/userS.png"/> Permanent Staff (<?php echo $permanentUsersCount; ?>)</li>
							<li class="TabbedPanelsTab" tabindex="20"><img src="img/userTemporaryS.png"/> Temporary Staff (<?php echo $temporaryUsersCount; ?>)</li>
							<li class="TabbedPanelsTab" tabindex="20"><img src="img/userFreelanceS.png"/> Freelance Staff (<?php echo $freelanceUsersCount; ?>)</li>
							<li class="TabbedPanelsTab" tabindex="20"><img src="img/userInternS.png"/> Interns (<?php echo $internUsersCount; ?>)</li>
							<li class="TabbedPanelsTab" tabindex="20"><img src="img/userInternS.png"/> PHD (<?php echo $PHDUsersCount; ?>)</li>
						</ul>
						<div class="TabbedPanelsContentGroup">
							<div class="TabbedPanelsContent"><?php include("inc/employeesLists/na_all/usersAllStaff.inc.php"); ?></div>
							<div class="TabbedPanelsContent"><?php include("inc/employeesLists/na_all/usersPermanentStaff.inc.php"); ?></div>
							<div class="TabbedPanelsContent"><?php include("inc/employeesLists/na_all/usersTemporaryStaff.inc.php"); ?></div>
							<div class="TabbedPanelsContent"><?php include("inc/employeesLists/na_all/usersFreelanceStaff.inc.php"); ?></div>
							<div class="TabbedPanelsContent"><?php include("inc/employeesLists/na_all/usersInternStaff.inc.php"); ?></div>
							<div class="TabbedPanelsContent"><?php include("inc/employeesLists/na_all/usersPHDStaff.inc.php"); ?></div>
						</div>
					</div>
				</div>

			</div>
	</div>


<script type="text/javascript">
	var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1",  {defaultTab:<?php echo $defaultTab; ?>});
	var TabbedPanelsAdminView = new Spry.Widget.TabbedPanels("TabbedPanelsAdminView",  {defaultTab:<?php echo $defaultTab; ?>});
	var TabbedPanelsUserView = new Spry.Widget.TabbedPanels("TabbedPanelsUserView",  {defaultTab:<?php echo $defaultTab; ?>});
	var TabbedPanelsPPView = new Spry.Widget.TabbedPanels("TabbedPanelsPPView",  {defaultTab:<?php echo $defaultTab; ?>});
</script>
