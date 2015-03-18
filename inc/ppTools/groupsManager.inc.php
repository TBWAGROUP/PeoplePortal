<!--
This script lists AD groups and their membership of employees

Included by:
index.php (case ?p=groupsManager)
inc/emp.inc.php

Hrefs pointing here:

Requires:

Includes:

Form actions:
index.php?p=groupsManager

-->

<script>
	$(document).ready(function () {
		$(".syncMess").hide();
		$(".sync").click(function () {
			$(".syncMess").show();
			$(".syncBtn").hide();
		});
	});
</script>

<span class="syncMess">
	<p align="center" class="text-default">
		<br/><strong><img src="img/ajax-loader.gif"/> Synchronisation... This process can take a while...</strong></p>
</span>

<?php
	// Groups NA & JR
	$queryGroups = mysql_query("SELECT * FROM groups ORDER BY groupName ASC") or die(mysql_error());
	$nbrGrp = mysql_num_rows($queryGroups);
	$nbrGrpCol = ceil($nbrGrp / 2);
	$nbrGrpCol2 = $nbrGrpCol + 1;
	$queryGroups1 = mysql_query("SELECT * FROM groups ORDER BY groupName ASC LIMIT 0, $nbrGrpCol") or die(mysql_error());
	$queryGroups2 = mysql_query("SELECT * FROM groups ORDER BY groupName ASC LIMIT $nbrGrpCol2, $nbrGrp") or die(mysql_error());

	if (!isset($_GET['idGroup'])) {
		?>
		<center>
			<h1><img src="img/setupBig.png"/> Groups Manager</h1>
		</center>

		<hr/>
		<center>
			<table class="float tableEmp">
				<tr>
					<th>
						<center>Groups</center>
					</th>
				</tr>
				<!-- Col 1 -->
				<?php while ($group1 = mysql_fetch_array($queryGroups1)) { ?>
					<tr class="hover">
						<td>
							<span align="right"><a href="index.php?p=groupsManager&idGroup=<?php echo $group1['idGroup']; ?>"><?php echo $group1['groupName']; ?></a></span>
						</td>
					</tr>
				<?php } ?>
			</table>

			<table class="float tableEmp">
				<tr>
					<th>
						<center>Groups</center>
					</th>
				</tr>
				<!-- Col 2 -->
				<?php while ($group2 = mysql_fetch_array($queryGroups2)) { ?>
					<tr class="hover">
						<td>
							<span align="right"><a href="index.php?p=groupsManager&idGroup=<?php echo $group2['idGroup']; ?>"><?php echo $group2['groupName']; ?></a></span>
						</td>
					</tr>
				<?php } ?>
			</table>

		</center>
	<?php
	} else if (!isset($_GET['up'])) {
		$idGroup = mysql_real_escape_string($_GET['idGroup']);
		$queryEmpGroupsUp = mysql_query("
															SELECT * FROM groups WHERE idGroup = $idGroup
														") or die(mysql_error());
		while ($group = mysql_fetch_array($queryEmpGroupsUp)) {
			$queryEmpGroup = mysql_query("
											SELECT * FROM employeeGroup AS empGroup
											INNER JOIN groups AS groups ON empGroup.idGroup = groups.idGroup
											INNER JOIN employees AS emp ON empGroup.idE = emp.idE
											WHERE empGroup.idGroup = $idGroup	
										") or die(mysql_error());
			?>


			<hr/>

			<form method="POST" action='index.php?p=groupsManager&idGroup=<?php echo $idGroup; ?>&up' name="gm">
				<h3><?php echo $group['groupName'] . " (idGroup : " . $group['idGroup'] . ")"; ?></h3>

				<div class="scrollGroup" height="500px">
					<table class="table table-condensed">
						<tr>
							<th>Members</th>
						</tr>
						<?php while ($emp = mysql_fetch_array($queryEmpGroup)) { ?>
							<tr>
								<td>
									<label><input type="checkbox" name="group[]" value="<?php echo $emp['idE']; ?>" checked> <?php echo $emp['firstname'] . " " . $emp['lastname']; ?>
									</label>
								</td>
							</tr>
						<?php } ?>
					</table>
				</div>
				<p><br/>
					<button class='btn btn-success'><img src='img/okWht.png'/> Update</button>
				</p>
			</form>
			<p><br/><br/><a class='btn btn-info' href="index.php?p=groupsManager">Back</a></p>
		<?php } ?>

	<?php
	} // !isset up
	if (isset($_GET["up"])) {
		$idGroup = $_GET['idGroup'];
		mysql_query("DELETE FROM employeeGroup WHERE idGroup = $idGroup"); // delete all groups association
		if (!empty($_POST['group'])) {
			foreach ($_POST['group'] as $check) {
				mysql_query("INSERT INTO employeeGroup (idGroup, idE) VALUES($idGroup, $check)") or die (mysql_error()); // and recreate them here
			}
		}
		echo "<h2 class='text-success'>Group correctly updated</h2>";
		echo "<meta http-equiv='refresh' content='1;url=index.php?p=groupsManager'>";
	}
?>




