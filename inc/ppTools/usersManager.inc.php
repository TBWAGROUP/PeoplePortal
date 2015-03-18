<!--
This script lists all users under PP admin tools tab, with total number of group memberships alongside

Included by:
index.php (case ?p=usersManager)

Hrefs pointing here:

Requires:

Includes:

Form actions:

-->

<script>
	$(document).ready(function(){
		$(".syncMess").hide();
		$(".sync").click(function()
		{
			$(".syncMess").show();
			$(".syncBtn").hide();
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
	// count trashed users
	$queryTrashed = mysql_query("SELECT * FROM employees WHERE ppAccountStatut = 1;");
	$trashedUsersCount = mysql_num_rows($queryTrashed);
	// count frozen users
	$queryFreeze = mysql_query("SELECT * FROM employees WHERE ppAccountStatut = 2;");
	$freezedUsersCount = mysql_num_rows($queryFreeze);




	// search filters :
	if (!isset($_GET['filter']))
	{
		$filter =  "";
	}
	else {
		$filter =  "WHERE ppAccountStatut = 0";
	}
	
	$queryEmp = mysql_query("SELECT * FROM employees ORDER BY firstname ASC") or die(mysql_error());
	$nbrEmp = mysql_num_rows($queryEmp);
	$nbrEmpCol = ceil($nbrEmp / 2);
	$nbrEmpCol2 = $nbrEmpCol+1;
	$queryEmp1 = mysql_query("SELECT * FROM employees ORDER BY firstname ASC $filter LIMIT 0,$nbrEmpCol") or die(mysql_error());
	$queryEmp2 = mysql_query("SELECT * FROM employees ORDER BY firstname ASC $filter LIMIT $nbrEmpCol2,$nbrEmp") or die(mysql_error());
	$queryGroups = mysql_query("SELECT * FROM groups ORDER BY groupName ASC") or die(mysql_error());


?>
<h1><img src="img/setupBig.png" /> Users Manager</h1>
</center>
	<p>
		<a class="btn btn-default" href="index.php?p=usersStandalone" title="User not found in AD but need to be listed in PP"><img src="img/hiddenS.png" /> Standalone users</a>
		<a class="btn btn-default" href="index.php?p=usersArchived" title="Archived users. Save all his data but nothing is listed in the PP"><img src="img/archiveS.png" /> Archived users</a>
		<a class="btn btn-default" href="index.php?p=usersFrozen" title="User not found in AD but on PP or found in AD but not in PP (not listed in PP)"><img src="img/frozenS.png" /> Frozen users (<?php echo $freezedUsersCount; ?>)</a>
		<a class="btn btn-default" href="index.php?p=usersTrash" title="Users not found in AD, ready for delete from PP"><img src="img/trashS.png" /> Users trash (<?php echo $trashedUsersCount; ?>)</a>
	</p>
<hr />		
		
		
<center>
	<table class="float tableEmp">
		<tr>
				<th><center>User</center></th>
				<th><center>Groups</center></th>
		</tr>
		<!-- Col 1 -->
			<?php while($emp1=mysql_fetch_array($queryEmp1)) { 
				$queryEmpGroups = mysql_query("SELECT * FROM employeeGroup WHERE idE = $emp1[idE]") or die(mysql_error());
				$nbrGroup = mysql_num_rows($queryEmpGroups); 
				$upn = $emp1['upn'];
			?>
		
		<tr class="hover">
			<td><span align="right"><a href="index.php?p=emp&idE=<?php echo $emp1['idE']; ?>&upn=<?php echo $upn; ?>" ><img src="img/ppAS-<?php echo $emp1['ppAccountStatut']; ?>_S.png" /> <?php echo $emp1['firstname']." ".$emp1['lastname']; ?></a></span></td>
			<td>
					in <?php if ($nbrGroup > 0) { echo "<strong>"; } echo $nbrGroup; ?></strong> group(s)
			</td>
		</tr>
		<?php } ?>
		</table>
		
		
		<table class="tableEmp">
		<tr>
				<th><center>User</center></th>
				<th><center>Groups</center></th>
		</tr>
		<!-- Col 2 -->
		<?php while($emp2=mysql_fetch_array($queryEmp2)) { 
				$queryEmpGroups = mysql_query("SELECT * FROM employeeGroup WHERE idE = $emp2[idE]") or die(mysql_error());
				$nbrGroup = mysql_num_rows($queryEmpGroups); 
				$upn = $emp2['upn'];
				?>
				
		<tr class="hover">
			<td><span align="right"><a href="index.php?p=emp&idE=<?php echo $emp2['idE']; ?>&upn=<?php echo $upn; ?>" ><img src="img/ppAS-<?php echo  $emp2['ppAccountStatut']; ?>_S.png" /> <?php echo $emp2['firstname']." ".$emp2['lastname']; ?></a></span></td>
			<td>
					in <?php if ($nbrGroup > 0) { echo "<strong>"; } echo $nbrGroup; ?></strong> group(s)
			</td>
		</tr>
		<?php } ?>
	</table>

</center>



<script type="text/javascript">
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
</script>
