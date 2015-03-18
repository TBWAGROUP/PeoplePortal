<!--
This script shows and edits an employee's AD group memberships, then saves to DB and pushes to AD

Included by:
inc/emp.inc.php

Hrefs pointing here:

Requires:

Includes:
inc/sync/pushEmployeeGroups.php

Form actions:
index.php?p=emp

-->

<?php
	$objectsid = $_GET['objectsid'];
	if (isset($_GET["up"])) {
		$idE = $_GET['idE'];
		$add = 0;
		$del = 0;
		$idCon = $_GET['idCon'];
		$upn = $_GET['upn'];

		/**
		 * empGroupDelete =  0 : default, relation group existing and wasn't changed
		 * empGroupDelete =  1 : relation group unchecked and ready for delete
		 * empGroupDelete =  2 : new relation group checked and ready for adding
		 **/

		// changer le groupeStatut à 1
		mysql_query("UPDATE employeeGroup SET empGroupDelete = 1 WHERE idE = $idE;");

		if (!empty($_POST['group'])) {
			foreach ($_POST['group'] as $check) {

				$checkGroup = mysql_query("SELECT * FROM employeeGroup WHERE idE = $idE AND idGroup = $check") or die (mysql_error());
				if (mysql_num_rows($checkGroup) == 0) {
					mysql_query("INSERT INTO employeeGroup (idE, idGroup) VALUES($idE, $check)") or die (mysql_error()); // and recreate them here
				} else {
					// si le groupe est déjà associé, repasser groupStatut à 0
					mysql_query("UPDATE employeeGroup SET empGroupDelete = 0 WHERE idE = $idE AND idGroup = $check;");
				}
			}
			// à la fin du process, les valeurs dans empGroup marquée à 1 pourront être supprimées
		}

		if (!empty($_POST['group2'])) {
			foreach ($_POST['group2'] as $check2) {
				$checkGroup = mysql_query("SELECT * FROM employeeGroup WHERE idE = $idE AND idGroup = $check2") or die (mysql_error());
				if (mysql_num_rows($checkGroup) == 0) {
					mysql_query("INSERT INTO employeeGroup (idE, idGroup, empGroupDelete) VALUES($idE, $check2, 2)") or die (mysql_error()); // and recreate them here
					$add++;
				}
			}
		}

		// effacer les relations de groupe à 1 restantes (seulement pour l'utilisateur en cours)
		mysql_query("DELETE FROM employeeGroup WHERE idE = $idE AND empGroupDelete = 1");

		//echo $add;
	if (!empty($objectsid)) {
		echo "Updating AD...<br />";
		include($_SERVER['DOCUMENT_ROOT'] . "/inc/sync/pushEmployeeGroups.php");
		echo "<h3 class='text-success'>User correctly updated</h3>";
	}
		echo "<meta http-equiv='refresh' content='1;url=index.php?p=emp&idE=$idE&idCon=$idCon&upn=$upn&objectsid=$objectsid'>";
	}

?>
<script>
	$(document).ready(function () {
		$(".syncMess").hide();
		$(".sync").click(function () {
			$(".syncMess").show();
			$(".syncBtn").hide();
		});
	});
</script>

<center>
	<span class="syncMess">
		<p align="center" class="text-default">
			<br/><strong><img src="img/ajax-loader.gif"/>
				<?php
					if (!empty($objectsid)) { ?> Updating Active Directoy...
			<?php } else { ?> Updating Database, not on Active Directory yet... <?php } ?>
			</strong>
		</p></span>
	</span>

	<form method="POST" action='index.php?p=emp&idE=<?php echo $idE; ?>&idCon=<?php echo $idCon; ?>&up&upn=<?php echo $upn; ?>&objectsid=<?php echo $objectsid; ?>' name="gm">
		<table class="table table-condensed">
			<tr>
				<th>Member of</th>
				<th>All Groups</th>
			</tr>
			<tr>
				<td>
					<?php
						// member of
						$idE = mysql_real_escape_string($_GET['idE']);

						$queryEmpGroups = mysql_query("SELECT * FROM employeeGroup AS eg INNER JOIN groups AS groups ON groups.idGroup = eg.idGroup WHERE eg.idE = $idE ORDER BY groupName ASC") or die(mysql_error());
						$nbrGroups = mysql_num_rows($queryEmpGroups);
						while ($group = mysql_fetch_array($queryEmpGroups)) {
							$idGroup = $group['idGroup'];
							?>
							<label class="groupListLab">
								<input type="checkbox" name="group[]" value="<?php echo $group['idGroup']; ?>" checked/> <?php echo $group['groupName']; ?>
							</label><br/>
						<?php } ?>
				</td>
				<td>
					<div class="scrollGroup">
						<?php
							// other groups
							if ($nbrGroups > 0) {
								$queryEmpGroups2 = mysql_query("SELECT * FROM groups  ORDER BY groupName ASC") or die(mysql_error());
							} else {
								$queryEmpGroups2 = mysql_query("SELECT * FROM groups") or die(mysql_error());
							}
							while ($group2 = mysql_fetch_array($queryEmpGroups2)) {
								?>
								<label class="groupListLab">
									<input type="checkbox" name="group2[]" value="<?php echo $group2['idGroup']; ?>"> <?php echo $group2['groupName']; ?>
								</label><br/>
							<?php } ?>
					</div>
				</td>
			</tr>
		</table>
		<p><br/>
			<button class='btn btn-success sync syncBtn'><img src='img/okWht.png'/> Assign</button>
		</p>
	</form>
	<p>
		<br/><a class="btn btn-default" href="index.php?p=groupSync&idE=<?php echo $idE; ?>&upn=<?php echo $upn; ?>&objectsid=<?php echo $objectsid; ?>" title="Pull membership"><img src="img/pull.png"/> Pull user's groups from AD to PP</a>
	</p>
