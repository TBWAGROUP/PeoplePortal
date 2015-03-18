<!--
This script shows user info and allows editing based on PP group membership

Included by:
inc/emp.inc.php

Hrefs pointing here:

Requires:
class/PHPMailer/class.phpmailer.php
inc/userStartForm/mailNotifications/mail.provisioning.all.php
inc/userStartForm/mailNotifications/mail.hr.refused.php

Includes:

Form actions:
index.php?p=saveEmp
index.php?p=saveEmpFinance

-->

<?php
	$objectsid = $emp["objectsid"];
	// ====================================================================================
	// Functions for editing fields
	// ====================================================================================

	function edit($field, $extraField)
	{
		global $emp;
		if (isset($_GET['editInfos'])) {
			return "<input type=\"text\" style=\"width:100%;\" name=\"$field\" value=\"" . $emp[$field] . "\" " . $extraField . "  onKeypress=\"return dropQuotes(event); \"/>";
		} else {
			return $emp[$field];
		}
	}

	function editMultiLine($field)
	{
		global $emp;
		if (isset($_GET['editInfos'])) {
			return "<textarea rows=6 cols=50 name=\"$field\" onKeypress=\"return dropQuotes(event); \"/>$emp[$field]</textarea>";
		} else {
			return nl2br($emp[$field]);
		}
	}

	// ====================================================================================
	// Get info about currently logged in user
	// ====================================================================================
	if (isset($_GET['idE'])){
	$idE = mysql_real_escape_string($_GET['idE']);
	$result = mysql_query("
			SELECT * FROM employees AS emp
			INNER JOIN contracts AS cont ON cont.idCon = emp.contract
			INNER JOIN departments AS dep ON cont.idDep = dep.idDep
			INNER JOIN labels AS lab ON lab.idLab = cont.idLab
			INNER JOIN functions AS func ON func.idFunc = cont.idFunc
			WHERE emp.idE=$idE
			") or die(mysql_error());;
	while ($emp = mysql_fetch_array($result)){
	$queryMailGroup = mysql_query("
				SELECT * FROM employeeGroup AS empGroup
				INNER JOIN groups AS groups ON empGroup.idGroup = groups.idGroup
				WHERE empGroup.idE = $idE AND groups.groupName LIKE '%MD %'
				") or die (mysql_error());
	$idCon = $emp['idCon'];
	$upn = $emp['upn'];

	// ====================================================================================
	// Get Requestor infos for non-validated employees
	// ====================================================================================
	if (($emp["validationStage"] == 1) || ($emp["validationStage"] == 2)) {
		$idErequestor = $emp["requestor"];
		$queryRequestor = mysql_query("
						SELECT * FROM contracts AS cont
						INNER JOIN employees AS emp ON emp.idE = cont.idE
						WHERE emp.idE = \"$idErequestor\"
						ORDER BY cont.idCon DESC 
						LIMIT 0,1
						") or die (mysql_error());
		$requestor = mysql_fetch_array($queryRequestor);
	}

	// ====================================================================================
	// Approval for PP HR only
	// Approved -> contracts.validationStage 2 + mail.provisioning.all.php
	// Refused  -> contracts.validationStage 4031, employees.ppAccountStatut 403 + mail.hr.refused.php
	// ====================================================================================
	if (memberOf($pp_hr)) {
		if (isset($_GET['approve'])) {
			$idConUrl = mysql_real_escape_string($_GET['idCon']);
			$idEurl = mysql_real_escape_string($_GET['idE']);
			mysql_query("UPDATE contracts SET validationStage = 2 WHERE idCon = $idConUrl") or die (mysql_error());
			require($_SERVER['DOCUMENT_ROOT'] . "/class/PHPMailer/class.phpmailer.php");
			$hrByPass = 0;
			require($_SERVER['DOCUMENT_ROOT'] . "/inc/userStartForm/mailNotifications/mail.provisioning.all.php");
			echo "<img src='img/ajax-loader.gif' /> Request approved. Redirecting...<meta http-equiv='refresh' content='2;url=index.php'>";
		}
		if (isset($_GET['refuse'])) {
			$idConUrl = mysql_real_escape_string($_GET['idCon']);
			$idEurl = mysql_real_escape_string($_GET['idE']);
			mysql_query("UPDATE contracts SET validationStage = 4031 WHERE idCon = $idConUrl") or die (mysql_error());
			mysql_query("UPDATE employees SET ppAccountStatut = 403 WHERE idE = $idEurl") or die (mysql_error());
			require($_SERVER['DOCUMENT_ROOT'] . "/class/PHPMailer/class.phpmailer.php");
			require($_SERVER['DOCUMENT_ROOT'] . "/inc/userStartForm/mailNotifications/mail.hr.refused.php");
			echo "<img src='img/ajax-loader.gif' /> Request refused. Redirecting...<meta http-equiv='refresh' content='2;url=index.php'>";
		}
	}

?>

<?php
	// ====================================================================================
	// Styling and scripts
	// ====================================================================================
?>
<style type="text/css">
	#profile {
		font-size: 12px;
	}

	#leftPane {
		width: 350px;
		padding-left: 10px;
		padding-right: 10px;
		float: left;
		margin: auto;
	}

	#rightPane {
		width: 700px;
		padding-left: 10px;
		padding-right: 10px;
		float: left;
		margin: auto;
	}

	#photo {
		overflow: hidden;
		width: 270px;
		border: 1px solid #CCC;
		border-radius: 10px;
	}

	#personnalInfos {
		width: 700px;
	}

	#profile th {
		color: #666;
		font-weight: normal;
		width: 150px;
	}

	#profile td {
		color: #000;
	}

	#profile h5 {
		font-weight: bold;
		text-align: left;
		color: #585858;
	}
</style>
<script>
	$(document).ready(function () {
		$("#mobilePhoneSub").hide();
	});
</script>
<script type="text/javascript">
	// prevent double quotes "
	function dropQuotes(evt) {
		var keyCode = evt.which ? evt.which : evt.keyCode;
		var interdit = '"';
		if (interdit.indexOf(String.fromCharCode(keyCode)) >= 0) {
			return false;
		}
	}
</script>
<center>
<?php
	// ====================================================================================
	// Show requestor info for PP Admin
	// ====================================================================================
	if (memberOf($pp_admin)) {
		if (($emp["validationStage"] == 1) || ($emp["validationStage"] == 2)) {
			echo "<p><strong>User requested by <a href='index.php?p=emp&idE=" . $requestor["idE"] . "&idCon=" . $requestor["idCon"] . "&upn=" . $requestor["upn"] . "&objectsid=" . $requestor["objectsid"] . "'>" . $requestor["firstname"] . " " . $requestor["lastname"] . "</a></strong> (" . $emp["ppAddDate"] . ")</p>";
		}
	}

	// ====================================================================================
	// Show General Edit Button only for PP Admin, PP HR, PP Finance, PP Carfleet
	// ====================================================================================
	if ((memberOf($pp_admin)) || (memberOf($pp_hr)) || (memberOf($pp_finance)) || (memberOf($pp_carfleet)) || (memberOf($pp_building))){

	// ====================================================================================
	// NOT in Edit mode
	// ====================================================================================
	if (!isset($_GET['editInfos'])) {
	?>
	<p>
		<br/><a class="btn btn-default" href="index.php?p=emp&idE=<?php echo $idE; ?>&upn=<?php echo $upn; ?>&idCon=<?php echo $idCon; ?>&objectsid=<?php echo $emp["objectsid"]; ?>&editInfos"><img src="img/edit.png"/>
			<strong>EDIT</strong></a>

		<?php
			// ====================================================================================
			// Show Pull from AD Button only for PP Admin
			// ====================================================================================
			if (memberOf($pp_admin)) {
				if ($emp["ppAccountStatut"] < 3) {
					?><a class="btn btn-default"
					href="index.php?p=pullEmp&idE=<?php echo $idE; ?>&upn=<?php echo $upn; ?>&objectsid=<?php echo $emp["objectsid"]; ?>" >
						<img src="img/pull.png"/> Pull from AD</a><?php
				}
			}
		?></p>

	<?php
	// ====================================================================================
	// Show Approval Button only for PP HR
	// ====================================================================================
	if (($emp["validationStage"] == 1) || ($emp["validationStage"] == 2)) {
		if (memberOf($pp_hr)) {
			?>
			<script>
				$(document).ready(function () {
					$(".appMess").hide();
					$(".refMess").hide();
					$(".app").click(function () {
						$(".appMess").show();
						$(".refMess").hide();
					});
					$(".ref").click(function () {
						$(".refMess").show();
						$(".appMess").hide();
					});
				});
			</script>
			<p><br/>
				<input type="hidden" name="idCon" value="<?php echo $_GET['idCon']; ?>">
				<input type="hidden" name="idE" value="<?php echo $_GET['idE']; ?>">
				<a class='btn usf btn-success app'><img src='img/okWht.png'/> Approve</a>
				<a class='btn usf btn-danger ref'><img src='img/noOkWht.png'/> Refuse</a>
				<span class="refMess text-danger">Refuse. Are you sure?
					<a class="btn btn-default btn-sm" href="index.php?p=emp&idE=<?php echo $idE; ?>&idCon=<?php echo $idCon; ?>&refuse" title="Refuse">YES</a></span>
				<span class="appMess text-success">Approve. Are you sure?
					<a class="btn btn-default btn-sm" href="index.php?p=emp&idE=<?php echo $idE; ?>&idCon=<?php echo $idCon; ?>&approve" title="Approve">YES</a></span>
			</p>
		<?php
		}
	}
	// ====================================================================================
	// Else, IN Edit mode, show SAVE button
	// PP Admin, PP HR -> saveEmp.ic.php (called through index.php)
	// PP Finance, PP Building -> saveEmpFinance.inc.php (called through index.php)
	// ====================================================================================         
} else {
	if ((memberOf($pp_admin)) || (memberOf($pp_hr))) {
?>
	<form action="index.php?p=saveEmp&idE=<?php echo $idE; ?>&idCon=<?php echo $idCon; ?>&upn=<?php echo $upn; ?>&first=<?php echo $emp['firstname']; ?>&objectsid=<?php echo $emp["objectsid"]; ?>&name=<?php echo $emp['lastname']; ?>" method="POST">
		<p><br/>
			<button class="btn btn-default"><img src="img/saveS.png"/> <strong>SAVE</strong></button>
		</p>
		<?php
			} elseif ((memberOf($pp_finance))) {
		?>
		<form action="index.php?p=saveEmpFinance&idE=<?php echo $idE; ?>&idCon=<?php echo $idCon; ?>&upn=<?php echo $upn; ?>&first=<?php echo $emp['firstname']; ?>&name=<?php echo $emp['lastname']; ?>" method="POST">
			<p><br/>
				<button class="btn btn-default"><img src="img/saveS.png"/> <strong>SAVE</strong></button>
			</p>
			<?php
				} elseif ((memberOf($pp_carfleet))) {
			?>
			<form action="index.php?p=saveEmpCarfleet&idE=<?php echo $idE; ?>&idCon=<?php echo $idCon; ?>&upn=<?php echo $upn; ?>&first=<?php echo $emp['firstname']; ?>&name=<?php echo $emp['lastname']; ?>" method="POST">
				<p><br/>
					<button class="btn btn-default"><img src="img/saveS.png"/> <strong>SAVE</strong></button>
				</p>
				<?php
					} elseif ((memberOf($pp_building))) {
				?>
				<form action="index.php?p=saveEmpBuilding&idE=<?php echo $idE; ?>&idCon=<?php echo $idCon; ?>&upn=<?php echo $upn; ?>&first=<?php echo $emp['firstname']; ?>&name=<?php echo $emp['lastname']; ?>" method="POST">
					<p><br/>
						<button class="btn btn-default"><img src="img/saveS.png"/> <strong>SAVE</strong></button>
					</p>
					<?php
						}
						}
						}
					?>

<?php

	// ====================================================================================
	// And now for the bulk of the Employee info
	// ====================================================================================
?>
<div id="profile">
<?php

	// ====================================================================================
	// Show ppAccountStatut icon and action buttons for PP Admin only
	// ====================================================================================
	if (memberOf($pp_admin)) {
		echo "idE: " . $idE . "<br>";
		echo "idCon: " . $idCon . "<br>";
		echo "upn: " . $upn . "<br>";
		echo "objectsid: " . $objectsid . "<br>";
		?>
		<p><img src="img/ppAS-<?php echo $emp['ppAccountStatut']; ?>.png"/></p>
		<p>
			<a class="btn btn-default btn-xs" href="index.php?p=emp&idE=<?php echo $idE; ?>&idCon=<?php echo $idCon; ?>&upn=<?php echo $upn; ?>&objectsid=<?php echo $objectsid; ?>&activate" title="Activate"><img src="img/ppAS-0_S.png"/> Activate</a>
			<a class="btn btn-default btn-xs" href="index.php?p=emp&idE=<?php echo $idE; ?>&idCon=<?php echo $idCon; ?>&upn=<?php echo $upn; ?>&objectsid=<?php echo $objectsid; ?>&freeze" title="Freeze"><img src="img/ppAS-2_S.png"/> Freeze</a>
			<a class="btn btn-default btn-xs" href="index.php?p=emp&idE=<?php echo $idE; ?>&idCon=<?php echo $idCon; ?>&upn=<?php echo $upn; ?>&objectsid=<?php echo $objectsid; ?>&stand" title="Standalone"><img src="img/ppAS-3_S.png"/> Standalone</a>
			<a class="btn btn-default btn-xs" href="index.php?p=emp&idE=<?php echo $idE; ?>&idCon=<?php echo $idCon; ?>&upn=<?php echo $upn; ?>&objectsid=<?php echo $objectsid; ?>&arch" title="Archivate"><img src="img/ppAS-4_S.png"/> Archivate</a>
			<a class="btn btn-default btn-xs" href="index.php?p=emp&idE=<?php echo $idE; ?>&idCon=<?php echo $idCon; ?>&upn=<?php echo $upn; ?>&objectsid=<?php echo $objectsid; ?>&trash" title="trash"><img src="img/ppAS-1_S.png"/> Trash</a>
			<a class="btn btn-default btn-xs" href="index.php?p=emp&idE=<?php echo $idE; ?>&idCon=<?php echo $idCon; ?>&upn=<?php echo $upn; ?>&objectsid=<?php echo $objectsid; ?>&usf" title="trash"><img src="img/ppAS-5_S.png"/> USF Flow</a>
		</p>
		<?php

		if (isset($_GET['activate'])) {
			mysql_query("UPDATE employees SET ppAccountStatut = '0' WHERE idE = $idE;");
			echo "Employee status changed to Active ";
			echo "<meta http-equiv='refresh' content='1;url=index.php?p=emp&idE=$idE&idCon=$idCon&upn=$upn&objectsid=$objectsid'></h4><img src='img/ajax-loader.gif' />";
		}
		if (isset($_GET['freeze'])) {
			mysql_query("UPDATE employees SET ppAccountStatut = '2' WHERE idE = $idE;");
			echo "Employee status changed to Frozen ";
			echo "<meta http-equiv='refresh' content='1;url=index.php?p=emp&idE=$idE&idCon=$idCon&upn=$upn&objectsid=$objectsid'></h4><img src='img/ajax-loader.gif' />";
		}
		if (isset($_GET['stand'])) {
			mysql_query("UPDATE employees SET ppAccountStatut = '3' WHERE idE = $idE;");
			echo "Employee status changed to Standalone ";
			echo "<meta http-equiv='refresh' content='1;url=index.php?p=emp&idE=$idE&idCon=$idCon&upn=$upn&objectsid=$objectsid'></h4><img src='img/ajax-loader.gif' />";
		}
		if (isset($_GET['arch'])) {
			mysql_query("UPDATE contracts SET validationStage = '4' WHERE idCon = \"$idCon\"") or die(mysql_error());
			mysql_query("UPDATE employees SET ppAccountStatut = '4' WHERE idE = \"$idE\"") or die(mysql_error());
			mysql_query("UPDATE employees SET objectsid = NULL WHERE idE = \"$idE\"") or die(mysql_error());
			mysql_query("UPDATE employees SET teamLead = '0' WHERE idE = \"$idE\"") or die(mysql_error());
			mysql_query("DELETE FROM employeeGroup WHERE idE = \"$idE\"") or die(mysql_error());
			mysql_query("DELETE FROM teamLeads WHERE employees_idE = \"$idE\"") or die(mysql_error());
			echo "Contract status changed to archived (4). Employee status changed to archived (4). ObjectsID set to NULL. Removed teamlead status (0). Group memberships deleted. Teamlead memberships deleted.";
			echo "<meta http-equiv='refresh' content='1;url=index.php?p=emp&idE=$idE&idCon=$idCon&upn=$upn&objectsid=$objectsid'></h4><img src='img/ajax-loader.gif' />";
		}
		if (isset($_GET['trash'])) {
			mysql_query("UPDATE employees SET ppAccountStatut = '1' WHERE idE = $idE;");
			echo "Employee status changed to Trash ";
			echo "<meta http-equiv='refresh' content='1;url=index.php?p=emp&idE=$idE&idCon=$idCon&upn=$upn&objectsid=$objectsid'></h4><img src='img/ajax-loader.gif' />";
		}
		if (isset($_GET['usf'])) {
			mysql_query("UPDATE employees SET ppAccountStatut = '5' WHERE idE = $idE;");
			echo "Employee status changed to User Start Form Flow";
			echo "<meta http-equiv='refresh' content='1;url=index.php?p=emp&idE=$idE&idCon=$idCon&upn=$upn&objectsid=$objectsid'></h4><img src='img/ajax-loader.gif' />";
		}

		?>
		<hr/>
	<?php
	}
?>
<div id="leftPane">
<div id="photo">
	<?php
		// ====================================================================================
		// Picture, Contact info
		// Show: All
		// Edit: PP Admin, PP HR
		// ====================================================================================
		//$urlPhoto = "http://server-united.tbwagroup.be/" . $emp['upn'] . ".jpg";
		$urlPhoto = "http://peopleportal.tbwagroup.be/pics/" . $emp['upn'] . ".png";
		if (@fopen($urlPhoto, "r")) {
			echo '<center><img src="' . $urlPhoto . '" height="400px"></center>';
		} else {
			echo '<img src="img/userProfilePhoto.png" />';
		}
	?>
</div> <!-- closes photo -->
<br/>
<h5>Contact</h5>
<table class="table table-condensed">
	<tr>
		<th>Internal</th>
		<td>
			<?php
				if ((memberOf($pp_admin)) || (memberOf($pp_hr)) || (memberOf($pp_building))) {
					echo edit("internalPhone", "style=\"width:25%;\" ");
				} else {
					echo $emp['internalPhone'];
				}
			?>
		</td>
	</tr>
	<tr>
		<th>Tel Number</th>
		<td>
			<?php
				if ((memberOf($pp_admin)) || (memberOf($pp_hr)) || (memberOf($pp_building))) {
					echo edit("phoneNumber", "");
				} else {
					echo $emp['phoneNumber'];
				}
			?>
		</td>
	</tr>
	<tr>
		<th>Mobile</th>
		<td>
			<?php
				if ((memberOf($pp_admin)) || (memberOf($pp_hr)) || (memberOf($pp_building))) {
					echo edit("mobile", "");
				} else {
					echo $emp['mobile'];
				}
			?>
		</td>
	</tr>
	<tr>
		<th>Email</th>
		<td colspan=5>
			<?php
				if ((memberOf($pp_admin)) || (memberOf($pp_hr))) {
					echo edit("primaryEmail", " ");
				} else {
					echo $emp['primaryEmail'];
				}
			?>
		</td>
	</tr>
</table>

<?php
	// ====================================================================================
	// Manager info
	// Show: all
	// ====================================================================================
		?>
		<h5>Managers <?php
				if (!isset($_GET['editInfos'])) {
					$target = "_self";
				} else {
					$target = "_blank";
				}

				// ====================================================================================
				// Manager info
				// Edit: PP Admin, PP HR
				// ====================================================================================
				if ((memberOf($pp_admin)) || (memberOf($pp_hr))) {
					?><a class="btn btn-default btn-xs"
					href="index.php?p=empTeamLeadHollidayApp&idE=<?php echo $idE; ?>&upn=<?php echo $upn; ?>&idCon=<?php echo $idCon; ?>&objectsid=<?php echo $objectsid; ?>&editInfos"
					target="<?php echo $target; ?>"><img src="img/editS.png"/> Edit Managers</a><?php } ?></h5>
		<table class="table table-condensed">
			<tr>
				<th><?php if ($emp['empType'] == "Intern") {
						echo "Mentor";
					} else {
						echo "Manager";
					}?>
				</th>
			</tr>
			<tr>
				<td>
					<ul>
						<?php
							$teamLeadquery = mysql_query("SELECT * FROM teamLeads AS tl INNER JOIN employees AS emp ON tl.employees_idE = emp.idE WHERE contracts_idCon = $idCon AND tl.appType = 0 AND (emp.ppAccountStatut = 0 OR emp.ppAccountStatut = 3)") or die(mysql_error());
							while ($teamLead = mysql_fetch_array($teamLeadquery)) {
								echo "<li>" . $teamLead['firstname'] . " " . $teamLead['lastname'] . "</li>";
							}
						?>
					</ul>
				</td>
			</tr>
			<?php if ($emp['empType'] != "Intern") { ?>
			<tr>
				<th>Holiday Approver(s)</th>
			</tr>
			<tr>
				<td>
					<ul>
						<?php
							$holidayAppquery = mysql_query("SELECT * FROM teamLeads AS tl INNER JOIN employees AS emp ON tl.employees_idE = emp.idE WHERE contracts_idCon = $idCon AND tl.appType = 1 AND (emp.ppAccountStatut = 0 OR emp.ppAccountStatut = 3)") or die(mysql_error());
							while ($holidayApp = mysql_fetch_array($holidayAppquery)) {
								echo "<li>" . $holidayApp['firstname'] . " " . $holidayApp['lastname'] . "</li>";
							}
						?>
					</ul>
				</td>
			</tr>
			<?php }?>
		</table>

<?php
	// ====================================================================================
	// IT info
	// Show: PP Admin, PP HR, PP Finance
	// ====================================================================================
	if ((memberOf($pp_admin)) || (memberOf($pp_hr)) || (memberOf($pp_finance))) {
		?>
		<h5>IT Info</h5>
		<table class="table table-condensed">
			<?php
				// ====================================================================================
				// UPN
				// Edit: PP Admin, PP HR
				// ====================================================================================
				if ((memberOf($pp_admin)) || (memberOf($pp_hr))) {
					?>
					<tr>
						<th>UPN (AD login)</th>
						<td><?php echo edit("upn", ""); ?></td>
					</tr>
				<?php }
				if (memberOf($pp_finance)) {
			?>
			<tr>
				<th>UPN (AD login)</th>
				<td><?php echo $upn; ?></td>
			</tr>
			<?php } ?>
			<tr>
				<th>Workstation</th>
				<td><?php
						// ====================================================================================
						// Workstation
						// Edit: PP Admin, PP HR
						// ====================================================================================
						if (!isset($_GET['editInfos'])) {
							if (strpos($emp['workstation'], "Windows")) {
								$img = "windowsos.png";
							} else {
								if (strpos($emp['workstation'], "Mac")) {
									$img = "macos.png";
								} else {
									$img = "noOkS.png";
								}
							} ?>
							<img src="img/<?php echo $img; ?>"/> <?php  echo $emp['workstation'];
						} else {
							if ((memberOf($pp_admin)) || (memberOf($pp_hr))) {
								?>
								<select name="workstation">
									<option
									="<?php echo $emp['workstation']; ?>"><?php echo $emp['workstation']; ?></option>
									<option value="Not needed">Not needed</option>
									<option value="Laptop Mac">Laptop Mac</option>
									<option value="Desktop Mac">Desktop Mac</option>
									<option value="Laptop Windows">Laptop Windows</option>
									<option value="Desktop Windows">Desktop Windows</option>
								</select>
							<?php
							} else {
								echo $emp['workstation'];
							}
						}
					?>
				</td>
			</tr>
			<tr>
				<th>Network Admin</th>
				<td>
					<?php
						// ====================================================================================
						// Network Admin
						// Edit: PP Admin, PP HR
						// ====================================================================================
						if (!isset($_GET['editInfos'])) {
							if ($emp['itNetworkAdmin'] == 1) {
								echo "Yes";
							} else {
								echo "No";
							}
						} else {
							if ((memberOf($pp_admin)) || (memberOf($pp_hr))) {
								?>
								<select name="itNetworkAdmin">
									<option value="<?php echo $emp['itNetworkAdmin']; ?>"><?php
											if ($emp['itNetworkAdmin'] == 1) {
												echo "Yes";
											} else {
												echo "No";
											}
										?></option>
									<option value="1">Yes</option>
									<option value="0">No</option>
								</select>
							<?php
							}
						}
					?>
				</td>
			</tr>
			<?php
				// ====================================================================================
				// Other IT info
				// Edit: PP Admin, PP HR
				// ====================================================================================
				if ((memberOf($pp_admin)) || (memberOf($pp_hr))) {
					?>
					<tr>
						<th>Other Hardware</th>
						<td><?php echo edit("itOtherHardware", ""); ?></td>
					</tr>
					<tr>
						<th>Sofware Profile</th>
						<td><?php echo edit("softwareNeeded", ""); ?></td>
					</tr>
					<tr>
						<th>Special Software Requests</th>
						<td><?php echo edit("softwareSpecialRequest", ""); ?></td>
					</tr>
				<?php } ?>
		</table>
	<?php } ?>
<?php
	// ====================================================================================
	// PP Workflow Note
	// Show: PP Admin, PP HR, PP Finance, PP Carfleet
	// Edit: PP Admin, PP HR, PP Finance, PP Carfleet
	// ====================================================================================
	if ((memberOf($pp_admin)) || (memberOf($pp_hr)) || (memberOf($pp_finance)) || (memberOf($pp_carfleet))) {
		?>
		<table class="table table-condensed">
			<tr>
				<th>Internal People Portal Workflow Note</th>
			</tr>
			<tr>
				<td><?php echo editMultiline("note", ""); ?></td>
			</tr>
		</table>
	<?php } ?>
<?php
	// ====================================================================================
	// Bio
	// Show: all
	// Edit: PP Admin, PP HR, PP Carfleet
	// ====================================================================================
?>
<table class="table table-condensed">
	<tr>
		<th>Personal Bio</th>
	</tr>
	<tr>
		<td><?php
				if ((memberOf($pp_admin)) || (memberOf($pp_hr)) || (memberOf($pp_carfleet))) {
					echo editMultiline("bio", "");
				} else {
					echo nl2br($emp['bio']);
				}
			?>
		</td>
	</tr>
</table>
<?php ?>
</div> <!-- closes leftpane -->
<div id="rightPane">
<div id="personnalInfos">
	<h5>Personal Information</h5>
	<table class="table table-condensed">
		<!--
		// ====================================================================================
		// Names, Language
		// Show: all
		// Edit: PP Admin PP HR
		// ====================================================================================
		-->
		<tr>
			<th>Firstname</th>
			<td><?php

					if ((memberOf($pp_admin)) || (memberOf($pp_hr))) {
						echo edit("firstname", "");
					} else {
						echo $emp['firstname'];
					}

				?></td>
		</tr>
		<tr>
			<th>Lastname</th>
			<td><?php

					if ((memberOf($pp_admin)) || (memberOf($pp_hr))) {
						echo edit("lastname", "");
					} else {
						echo $emp['lastname'];
					}

				?></td>
		</tr>
		<tr>
			<th>Language</th>
			<td><?php

					if ((memberOf($pp_admin)) || (memberOf($pp_hr))) {
						echo edit("language", "");
					} else {
						echo $emp['language'];
					}

				?></td>
		</tr>
		<!--
		// ====================================================================================
		// Private Adress
		// Show: PP Admin, PP HR, PP Finance, PP Car
		// Edit: PP Admin, PP HR
		// ====================================================================================
		-->
		<?php if ((memberOf($pp_admin)) || (memberOf($pp_hr)) || (memberOf($pp_finance)) || (memberOf($pp_carfleet))) { ?>
			<tr>
				<th>Address</th>
				<td><?php

						if ((memberOf($pp_admin)) || (memberOf($pp_hr)) || (memberOf($pp_carfleet))) {
							echo editMultiLine("address", "style=\"width:100%;\"  ");
						} else {
							echo nl2br($emp['address']);
						}

					?></td>
			</tr>
		<?php } ?>
		<tr>
			<!--
			// ====================================================================================
			// Birthdate
			// Show: all in no-year version
			// Edit: PP Admin, PP HR, PP Car (full info)
			// ====================================================================================
			-->
			<th>Birthdate</th>
			<td><?php

					if ((memberOf($pp_admin)) || (memberOf($pp_hr)) || (memberOf($pp_carfleet))) {
						echo edit("birthdate", "class='datepicker' style=\"width:80%;\"");
					} elseif  ((memberOf($pp_building)) || (memberOf($pp_finance))) {
						echo $emp['birthdate'];
					} else  {
						echo date_format(date_create_from_format('d/m/Y', $emp['birthdate']), 'j F');
					}

				?></td>
		</tr>
	</table>
</div> <!-- Closes PersonalInfo Name Language etc -->
<?php

	// ====================================================================================
	// Use Start/End Date Add-to-gCal button (below) only to PP Admin, PP HR, PP Planning
	// ====================================================================================

/**	if ((memberOf($pp_admin)) || (memberOf($pp_hr)) || (memberOf($pp_planning))) {
		if (isset($_GET['pushCal'])) {
			echo "This event will be added on the People Portal Calendar. <br /><strong>Are you sure?</strong><br />";
			echo "- <a href='index.php?p=emp&idE=$idE&upn=$upn&idCon=$idCon&pushCal&push'>Yes</a> -<br />";
			echo "- <a href='index.php?p=emp&idE=$idE&upn=$upn&idCon=$idCon'>No, cancel</a> -";
			if (isset ($_GET['push'])) {
				$userName = $emp['firstname'] . " " . $emp['lastname'];
				if ($emp['startDate'] != "") {
					addGcal($emp['startDate'], $userName, 0);
				} else {
					echo "No start date, skipped<br>";
				}
				if ($emp['endDate'] != "") {
					addGcal($emp['endDate'], $userName, 1);
				} else {
					echo "No end date, skipped";
				}
				echo "<img src='img/ajax-loader.gif' />";
				echo "<meta http-equiv='refresh' content='2;url=index.php?p=emp&idE=$idE&upn=$upn&idCon=$idCon'></h4>";
			}
		}
	}
	**/
?>
<div id="personnalInfos">
	<?php
	// ====================================================================================
	// Dates
	// Show: PP Admin, PP HR, PP Finance, PP Car Fleet
	// Edit: PP Admin, PP HR
	// ====================================================================================
?>

<?php
	if ((memberOf($pp_admin)) || (memberOf($pp_hr)) || (memberOf($pp_finance)) || (memberOf($pp_carfleet)) || (memberOf($pp_planning))) {
		?>
			<h5>Contract informations
				<!-- <a href="index.php?p=emp&idE=<?php echo $idE; ?>&upn=<?php echo $upn; ?>&idCon=<?php echo $idCon; ?>&pushCal" class="btn btn-default btn-xs" title="Add to Google Calendar"><strong>+</strong>
					<img src="img/gCalendarS.png"></a>
					-->
			</h5>
			<table class="table table-condensed">
				<tr>
					<th>Start Date</th>
					<td><?php

							if ((memberOf($pp_admin)) || (memberOf($pp_hr))) {
								echo edit("startDate", "class='datepicker' style=\"width:80%;\"");
							} else {
								echo $emp['startDate'];
							}

						?></td>
				</tr>
				<tr>
					<th>Contractual End Date</th>
					<td><?php

							if ((memberOf($pp_admin)) || (memberOf($pp_hr))) {
								echo edit("endDate", "class='datepicker' style=\"width:80%;\"");
							} else {
								echo $emp['endDate'];
							}

						?></td>
				</tr>
				<tr>
					<th>Operational End Date</th>
					<td><?php

							if ((memberOf($pp_admin)) || (memberOf($pp_hr))) {
								echo edit("operationalEndDate", "class='datepicker' style=\"width:80%;\"");
							} else {
								echo $emp['operationalEndDate'];
							}

						?></td>
				</tr>
				<tr>
					<th>Disable Account Date</th>
					<td><?php

							if ((memberOf($pp_admin)) || (memberOf($pp_hr))) {
								echo edit("disableAccountDate", "class='datepicker' style=\"width:80%;\"");
							} else {
								echo $emp['disableAccountDate'];
							}

						?></td>
				</tr>
				<tr>
					<th>Material Return Date</th>
					<td width="45%">
						<img src="img/computer.png" title="Computer"/> <?php

							if ((memberOf($pp_admin)) || (memberOf($pp_hr))) {
								echo edit("materialReturnDate", "class='datepicker'");
							} else {
								echo $emp['materialReturnDate'];
							}

						?>
						<br><img src="img/mobilePhone.png" title="Mobile Phone"/> <?php

							if ((memberOf($pp_admin)) || (memberOf($pp_hr))) {
								echo edit("mobilePhoneReturnDate", "class='datepicker'");
							} else {
								echo $emp['mobilePhoneReturnDate'];
							}

						?>
						<br><img src="img/car.png" title="Car"/> <?php

							if ((memberOf($pp_admin)) || (memberOf($pp_hr)) || (memberOf($pp_carfleet))) {
								echo edit("carReturnDate", "class='datepicker'");
							} else {
								echo $emp['carReturnDate'];
							}

						?></td>
				</tr>
			</table>
	<?php } ?>
</div> <!-- Closes PersonalInfo Contract -->

<div id="personnalInfos">
<h5>HR</h5>
<table class="table table-condensed">
<?php
	// ====================================================================================
	// Type
	// Show: PP Admin, PP HR, PP Finance
	// Edit: PP Admin, PP HR (through General Edit button)
	// ====================================================================================
	if ((memberOf($pp_admin)) || (memberOf($pp_hr))) {
		?>
		<tr>
			<th width="5%">Type</th>
			<td colspan=4>
				<?php

					if (!isset($_GET['editInfos'])) {
						echo $emp['empType'];
					} else {
						?>
						<select name="empType">
							<option
							="<?php echo $emp['empType']; ?>"><?php echo $emp['empType']; ?></option>
							<option value="Employee">Employee</option>
							<option value="Employee temporary">Employee temporary</option>
							<option value="Contractor timebased">Contractor timebased</option>
							<option value="Contractor fixed">Contractor fixed</option>
							<option value="Freelance">Freelance</option>
							<option value="Intern">Intern</option>
							<option value="Administrator">Administrator</option>
						</select>
					<?php } ?>
			</td>
		</tr>
	<?php
	} else {
		if (memberOf($pp_finance)) {
			?>
			<tr>
				<th width="5%">Type</th>
				<td colspan=4><?php echo $emp['empType']; ?></td>
			</tr>
		<?php
		}
	} ?>

<!--
		// ====================================================================================
		// Label
		// Show: All
		// Edit: PP Admin, PP HR (through General Edit button)
		// ====================================================================================
-->
<tr>
	<th>Label</th>
	<td>
		<?php if (!isset($_GET['editInfos'])) {
			echo $emp['labelName'];
		} else {
			if ((memberOf($pp_admin)) || (memberOf($pp_hr))) {
				?>
				<select name="label" id="label" tabindex=70>
					<option value="<?php echo $emp['labelName']; ?>"> <?php echo $emp['labelName']; ?></option>
					<?php
						$queryComp = mysql_query("SELECT * FROM labels  WHERE hidden=0 ORDER BY labelName ASC") or die(mysql_error());
						$queryNbr = mysql_num_rows($query);
						while ($company = mysql_fetch_array($queryComp)) {
							?>
							<option value="<?php echo $company['labelName']; ?>"><?php echo $company['labelName']; ?></option>
						<?php } ?>
				</select>
			<?php
			} else {
				echo $emp['labelName'];
			}
		}
		?>
	</td>
	<!--
			// ====================================================================================
			// Payroll
			// Show: All
			// Edit: PP Admin, PP HR (through General Edit button)
			// ====================================================================================
	-->
	<th>Payroll</th>
	<td width="20%">
		<?php

			if (!isset($_GET['editInfos'])) {
			echo $emp['financePayroll'];
			} else {
				if ((memberOf($pp_admin)) || (memberOf($pp_hr))) {
					if ($emp['financePayroll'] != "") {
						$labToPayroll = $emp['financePayroll'];
					} else {
						$labToPayroll = "";
					}

					?>
					<script>
						$(document).ready(function () {
							$(".payrollFunc").load("inc/userStartForm/0-payrollFunc.inc.php?label=<?php  echo $labToPayroll; ?>");
						});
						$("#label").change(function () {
							var str = "";
							str = this.value;
							str = encodeURIComponent(str.trim())
							$(".payrollFunc").load("inc/userStartForm/0-payrollFunc.inc.php?label=" + str + "");
						}).change();
					</script>
					<div class="payrollFunc"></div>
					<?php

					if (!isset($_POST['payroll'])) {
						$_POST['payroll'] = "";
					}
				} else {
					echo $emp['financePayroll'];
				}
			}
		?>
	</td>
</tr>
<tr>
	<!--
			// ====================================================================================
			// Function
			// Show: All
			// Edit: PP Admin, PP HR (through General Edit button)
			// ====================================================================================
	-->
	<th>Function</th>
	<td>
		<?php
			if (!isset($_GET['editInfos'])) {
				if ($emp['empType'] == "Intern") {
					echo "Intern " . $emp['functionName'];
				} else {
					echo $emp['functionName'];
				}
			} else {
				if ((memberOf($pp_admin)) || (memberOf($pp_hr))) {
						?>
						<?php $queryFunc = mysql_query("SELECT * FROM functions WHERE hidden=0 ORDER BY functionName ASC") or die(mysql_error()); ?>
						<select name="function" tabindex=150>
							<option value="<?php echo $emp['idFunc']; ?>"><?php echo $emp['functionName']; ?></option>
							<?php while ($function = mysql_fetch_array($queryFunc)) { ?>
								<option value="<?php echo $function['idFunc']; ?>"><?php echo $function['functionName']; ?></option>
							<?php } ?>
						</select>
					<?php
					} else {
						if ($emp['empType'] == "Intern") {
							echo "Intern " . $emp['functionName'];
						} else {
							echo $emp['functionName'];
						}
					}
				}
		?>
	</td>
	<!--
			// ====================================================================================
			// Department
			// Show: All
			// Edit: PP Admin, PP HR (through General Edit button)
			// ====================================================================================
	-->
	<th width="10%">Department</th>
	<td width="20%">
		<?php
			if (!isset($_GET['editInfos'])) {
				echo $emp['nameDepartment'];
			} else {
				if ((memberOf($pp_admin)) || (memberOf($pp_hr))) {
					?>
					<?php $queryDep = mysql_query("SELECT * FROM departments WHERE hidden=0 ORDER BY nameDepartment ASC") or die(mysql_error()); ?>
					<select name="department" tabindex=150>
						<option value="<?php echo $emp['idDep']; ?>"><?php echo $emp['nameDepartment']; ?></option>
						<?php while ($department = mysql_fetch_array($queryDep)) { ?>
							<option value="<?php echo $department['idDep']; ?>"><?php echo $department['nameDepartment']; ?></option>
						<?php } ?>
					</select>
				<?php
				} else {
					echo $emp['nameDepartment'];
				}
			} ?>
	</td>
	<th></th>
</tr>
<?php
	// ====================================================================================
	// Time Regime and remarks
	// Show: PP Admin, PP HR, PP Finance
	// Edit: PP Admin, PP HR
	// ====================================================================================
	if ((memberOf($pp_admin)) || (memberOf($pp_hr)) || (memberOf($pp_finance))) {
		?>
		<tr>
			<th>Time Regime</th>
			<td><?php if ((memberOf($pp_admin)) || (memberOf($pp_hr))) { ?>
					<?php

					if (!isset($_GET['editInfos'])) {
						echo $emp['timeRegime'];
					} else {
						?>
						<select name="timeRegime">
							<option
							="<?php echo $emp['timeRegime']; ?>"><?php echo $emp['timeRegime']; ?></option>
							<option value="5/5">5/5</option>
							<option value="4.5/5">4.5/5</option>
							<option value="4/5">4/5</option>
							<option value="3/5">3/5</option>
							<option value="2.5/5">2.5/5</option>
						</select>
					<?php
					}
				} else {
					if (memberOf($pp_finance)) {
						echo $emp['timeRegime'];
					}
				}

				?>
			</td>
			<th>Remarks</th>
			<td colspan=2><?php

					if ((memberOf($pp_admin)) || (memberOf($pp_hr))) {
						echo editMultiLine("timeRegimeRmk");
					} else {
						echo nl2br($emp['timeRegimeRmk']);
					}

				?></td>
		</tr>
		<tr>
			<!--
					// ====================================================================================
					// Cell Phone subscription, remarks, model, owner, 3G, purchase date
					// Show: PP Admin, PP HR, PP Finance
					// Edit: PP Admin, PP HR
					// ====================================================================================
			-->
			<th>Cell Phone Subscribe</th>
			<td><?php if ((memberOf($pp_admin)) || (memberOf($pp_hr))) { ?>
					<?php

					if (!isset($_GET['editInfos'])) {
						echo $emp['mobilePhoneSub'];
					} else {
						?>
						<select name="mobilePhoneSubSelect" id="mobilePhoneSubSelect">
							<option
							="<?php echo $emp['mobilePhoneSub']; ?>"><?php echo $emp['mobilePhoneSub']; ?></option>
							<option value="Proximus">Proximus</option>
							<option value="King">King</option>
							<option value="Kong">Kong</option>
							<option value="Other">Other</option>
						</select>
						<script>
							$("#mobilePhoneSubSelect").change(function () {
								$("#mobilePhoneSub").val($(this).val());
								var mobSub = $(this).val();
								// alert (lang);
								if (mobSub == "Other") {
									$("#mobilePhoneSub").show();
								} else {
									$("#mobilePhoneSub").hide();
								}
							}).change();
						</script>
						<input type="text" name="mobilePhoneSub" id="mobilePhoneSub" value="<?php echo $emp['mobilePhoneSub']; ?>" onClick="value='' ">
					<?php
					}
				} else {
					if (memberOf($pp_finance)) {
						echo $emp['mobilePhoneSub'];
					}
				}

				?>
			</td>
			<th>Remarks</th>
			<td colspan=3><?php

					if ((memberOf($pp_admin)) || (memberOf($pp_hr))) {
						echo edit("mobilePhoneSubRmk", "");
					} else {
						echo $emp['mobilePhoneSubRmk'];
					}

				?></td>
		</tr>
		<tr>
			<th>Model</th>
			<td><?php

					if ((memberOf($pp_admin)) || (memberOf($pp_hr))) {
						echo edit("mobilePhoneModel", "");
					} else {
						echo $emp['mobilePhoneModel'];
					}

				?></td>
			<th>Mobile Phone Owner</th>
			<td><?php if ((memberOf($pp_admin)) || (memberOf($pp_hr))) { ?>
					<?php

					if (!isset($_GET['editInfos'])) {
						echo $emp['mobilePhoneOwner'];
					} else {
						?>
						<select name="mobilePhoneOwner">
							<option value="<?php echo $emp['mobilePhoneOwner']; ?>"><?php echo $emp['mobilePhoneOwner']; ?></option>
							<option value="Personal">Personal</option>
							<option value="TBWA">TBWA</option>
							<option value="E-GRAPHICS">E-GRAPHICS</option>
							<option value="Headline">Headline</option>
						</select>
					<?php
					}
				} else {
					if (memberOf($pp_finance)) {
						echo $emp['mobilePhoneOwner'];
					}
				}

				?>
			</td>
		</tr>
		<tr>
			<th>3G Data</th>
			<td><?php if ((memberOf($pp_admin)) || (memberOf($pp_hr))) { ?>
					<?php

					if (!isset($_GET['editInfos'])) {
						echo $emp['3gData'];
					} else {
						?>
						<select name="tGdata" tabindex=310>
							<option value="<?php echo $emp['3gData']; ?>"><?php echo $emp['3gData']; ?></option>
							<option value="National">National</option>
							<option value="Roaming">Roaming</option>
						</select>
					<?php
					}
				} else {
					if (memberOf($pp_finance)) {
						echo $emp['3gData'];
					}
				}

				?>
			</td>
			<th>Purchase Date</th>
			<td><?php

					if ((memberOf($pp_admin)) || (memberOf($pp_hr))) {
						echo edit("mobilePhonePurchaseDate", "class='datepicker'  style=\"width:100%;\"");
					} else {
						echo $emp['mobilePhonePurchaseDate'];
					}

				?></td>
			<td></td>
		</tr>
	<?php } ?>
</table>
</div> <!-- Closes PersonalInfo HR -->
<!--
		// ====================================================================================
		// Practical
		// Show: All
		// Edit: PP Admin, PP HR (through General Edit button)
		// ====================================================================================
-->
<?php $parkQueryComes = mysql_query("SELECT contracts_idCon, comesBy FROM parking WHERE contracts_idCon = $idCon ORDER BY idPark ASC") or die(mysql_error()); ?>
<?php $parkQueryPlaat = mysql_query("SELECT contracts_idCon, nrPlaat FROM parking WHERE contracts_idCon = $idCon ORDER BY idPark ASC") or die(mysql_error()); ?>
<?php $parkQueryParking = mysql_query("SELECT * FROM parking WHERE contracts_idCon = $idCon ORDER BY idPark ASC") or die(mysql_error()); ?>
<div id="personnalInfos">
	<h5>Practical  <?php

			if (!isset($_GET['editInfos'])) {
				$target = "_self";
			} else {
				$target = "_blank";
			}


			if ((memberOf($pp_admin)) || (memberOf($pp_hr))) {
				?><a class="btn btn-default btn-xs"
				href="index.php?p=fields_pp_building&idE=<?php echo $idE; ?>&upn=<?php echo $upn; ?>&idCon=<?php echo $idCon; ?>&editInfos"
				target="<?php echo $target; ?>"><img src="img/editS.png"/> Edit Parking Infos</a><?php } ?>
		<?php

			if (!isset($_GET['editInfos'])) {
				if ((memberOf($pp_building))) {
					?><a class="btn btn-default btn-xs"
					href="index.php?p=emp&idE=<?php echo $idE; ?>&upn=<?php echo $upn; ?>&idCon=<?php echo $idCon; ?>&tab=1">
						<img src="img/editS.png"/> Edit Parking Infos</a><?php
				}
			}

		?></h5>
	<table class="table table-condensed">
		<?php if ((memberOf($pp_admin)) || (memberOf($pp_hr)) || (memberOf($pp_building)) || (memberOf($pp_finance)) || (memberOf($pp_carfleet))) { ?>
			<tr>
				<th>Comes By</th>
				<td><?php
						while ($comesBy = mysql_fetch_array($parkQueryComes)) {
							echo $comesBy['comesBy'] . "";
						}

					?></td>
				<td></td>
				<td></td>
			</tr>
		<?php } ?>
		<tr>
			<th>Plate Number</th>
			<td><?php
					while ($comesBy1 = mysql_fetch_array($parkQueryPlaat)) {
						if ($comesBy1['nrPlaat'] != "") {
							echo $comesBy1['nrPlaat'] . " ";
						}
					}

				?></td>
			<th>Parking</th>
			<td><?php
					while ($comesBy2 = mysql_fetch_array($parkQueryParking)) {
						if ($comesBy2['parking'] != "") {
							echo $comesBy2['parking'] . " - nr. " . $comesBy2['parkingNr'];
						}
					}

				?></td>
		</tr>
		<?php if ((memberOf($pp_admin)) || (memberOf($pp_hr)) || (memberOf($pp_building)) || (memberOf($pp_finance)) || (memberOf($pp_carfleet))) { ?>
			<tr>
				<th>Parking Remarks</th>
				<td colspan=4><?php

						if ((memberOf($pp_admin)) || (memberOf($pp_hr)) || (memberOf($pp_building)) || (memberOf($pp_carfleet))) {
							echo edit("parkingRemarks", "");
						} else {
							if (memberOf($pp_finance)) {
								echo $emp['parkingRemarks'];
							}
						}

					?></td>
			</tr>
			<tr>
				<th>Badge Nr.</th>
				<td><?php

						if ((memberOf($pp_admin)) || (memberOf($pp_hr)) || (memberOf($pp_building))) {
							echo edit("badgeNr", "");
						} else {
							echo $emp['badgeNr'];
						}

					?></td>
				<th>Badge Access</th>
				<td><?php

						if ((memberOf($pp_admin)) || (memberOf($pp_hr)) || (memberOf($pp_building))) {
							echo edit("badgeAccessLevel", "");
						} else {
							echo $emp['badgeAccessLevel'];
						}

					?></td>
			</tr>
		<?php } ?>
		<?php if ((memberOf($pp_admin)) || (memberOf($pp_hr))) { ?>
			<tr>
				<th>Kensington Lock Nr.</th>
				<td colspan=4><?php echo edit("kensingtonLockNr", ""); ?></td>
			</tr>
		<?php } ?>
		<?php if ((memberOf($pp_admin)) || (memberOf($pp_hr)) || (memberOf($pp_building)) || (memberOf($pp_finance))) { ?>
			<tr>
				<th>Who to contact in case of emergency?</th>
				<?php $emergencyContactsQuery = mysql_query("SELECT * FROM emergencyContacts WHERE idE = $idE") or die(mysql_error()); ?>
				<td colspan=4><?php // while($ice = mysql_fetch_array ($emergencyContactsQuery)) {echo $ice['ICEname']." (".$ice['ICErelation']."), ".$ice['ICEphoneNumber'].". "; } echo $emp['contactEmergency']; ?>
					<?php

						if ((memberOf($pp_admin)) || (memberOf($pp_hr)) || (memberOf($pp_building))) {
							echo editMultiLine("contactEmergency", "");
						} else {
							echo nl2br($emp['contactEmergency']);
						}

					?>
				</td>
			</tr>
		<?php } ?>
	</table>
</div> <!-- Closes PersonalInfo Practical -->
<?php if ((memberOf($pp_admin)) || (memberOf($pp_hr)) || (memberOf($pp_building)) || (memberOf($pp_finance))) { ?>
	<div id="personnalInfos">
		<h5>Email & Business Cards <?php

				if (!isset($_GET['editInfos'])) {
					$target = "_self";
				} else {
					$target = "_blank";
				}


				if ((memberOf($pp_admin)) || (memberOf($pp_hr))) {
					?><a class="btn btn-default btn-xs"
					href="index.php?p=empEmailDomain&idE=<?php echo $idE; ?>&upn=<?php echo $upn; ?>&idCon=<?php echo $idCon; ?>&editInfos"
					target="<?php echo $target; ?>"><img src="img/editS.png"/> Add other Email Domains
					</a><?php } ?>
		</h5>
		<table class="table table-condensed">
			<tr>
				<th>Principal Email</th>
				<td><?php echo $emp["primaryEmail"]; ?></td>
				<th>Business Cards</th>
				<td><?php if ((memberOf($pp_admin)) || (memberOf($pp_hr))) { ?>
						<?php

						if (!isset($_GET['editInfos'])) {
							if ($emp['businessCardNeeded']) {
								echo "Yes";
							} else {
								echo "No";
							}
						} else {
							?>
							<select name="businessCardNeeded">
								<option value="<?php echo $emp['businessCardNeeded']; ?>"><?php

										if ($emp['businessCardNeeded']) {
											echo "Yes";
										} else {
											echo "No";
										}

									?></option>
								<option value="1">Yes</option>
								<option value="0">No</option>
							</select>
						<?php
						}
						// end if isset editInfo
					} else {
						if ($emp['businessCardNeeded']) {
							echo "Yes";
						} else {
							echo "No";
						}
					}

					?>
				</td>
				<th>Logo</th>
				<td>
					<?php if ((memberOf($pp_admin)) || (memberOf($pp_hr))) { ?>
						<?php if (isset($_GET['editInfos'])) { ?>
							<select name="emailSignatureLogo" tabindex=70>
								<option value="<?php echo $emp['emailSignatureLogo']; ?>"> <?php echo $emp['emailSignatureLogo']; ?></option>
								<?php
									$queryComp = mysql_query("SELECT * FROM labels  WHERE hidden=0 ORDER BY labelName ASC") or die(mysql_error());
									while ($company = mysql_fetch_array($queryComp)) {
										?>
										<option value="<?php echo $company['labelName']; ?>"><?php echo $company['labelName']; ?></option>
									<?php } ?>
							</select>
						<?php
						} else {
							echo $emp['emailSignatureLogo'];
						}
					} else {
						echo $emp['emailSignatureLogo'];
					}

					?>
				</td>
			</tr>
			<tr>
				<th>Other Email</th>
				<?php $emailAliasesQuery = mysql_query("SELECT * FROM emailAliasesEmp AS eae INNER JOIN emailAliases AS ea ON eae.idAliase = ea.idAliase WHERE eae.idCon = $idCon") or die (mysql_error()); ?>
				<td colspan=5>
					<?php
						while ($otherEmail = mysql_fetch_array($emailAliasesQuery)) {
							if ($otherEmail['businessCardNeeded']) {
								$bcn = "cardNeeded";
							} else {
								$bcn = "cardNotNeeded";
							}
							echo $emp['upn'] . $otherEmail['email'] . " (<img title = \"Business card\" src=\"img/" . $bcn . ".png\" height=\"15px\">); ";
						}

					?></td>
			</tr>
			<tr>
				<th>Email Distribution List</th>
				<td colspan=5>
					<ul>
						<?php while ($mailGroup = mysql_fetch_array($queryMailGroup)) { ?>
							<li><?php echo $mailGroup['groupName']; ?></li>
						<?php } ?>
					</ul>
				</td>
			</tr>
		</table>
	</div> <!-- closes PersonalInfo Email and Business card-->
<?php } ?>
<?php if ((memberOf($pp_admin)) || (memberOf($pp_hr)) || (memberOf($pp_finance)))  { ?>
<div id="personnalInfos">
	<h5>System & Standard Access
		<?php /** if (!isset($_GET['editInfos'])) { if (memberOf($pp_hr))  {  ?><a class="btn btn-default btn-xs" href="index.php?p=accessRequest&idE=<?php echo $idE; ?>&upn=<?php echo $upn; ?>&idCon=<?php echo $idCon; ?>&tab=2"><img src="img/editS.png" /> Access Request</a><?php } } **/ ?></h5>
	<table class="table table-condensed">
		<tr>
			<th>Job Portal</th>
			<?php $empGroupJRquery = mysql_query("SELECT * FROM employeeGroup AS empGroup INNER JOIN groups AS groups ON empGroup.idGroup = groups.idGroup WHERE empGroup.idE = $idE AND groups.groupName LIKE 'JR %'  AND empGroupDelete != 11 ORDER BY groups.groupName DESC ") or die(mysql_error()); ?>
			<td colspan=2><?php

					if (mysql_num_rows($empGroupJRquery) > 0) {
						echo "Yes : ";
					} else {
						echo "No";
					}

				?>
				<?php
					while ($groupJR = mysql_fetch_array($empGroupJRquery)) {
						echo $groupJR['groupName'] . " / ";
					}

				?></td>
			<th>Job Portal Server</th>
			<td colspan=1>
				<?php

					if (!isset($_GET['editInfos'])) {
						echo $emp['jobPortalRole'];
					} else {
						?>
						<select name="jobPortalRole">
							<option value="<?php echo $emp['jobPortalRole']; ?>"><?php echo $emp['jobPortalRole']; ?></option>
							<option value="TBWA">TBWA</option>
							<option value="Digital Craftsmen">Digital Craftsmen</option>
							<option value="TBWA Antwerp">TBWA Antwerp</option>
							<option value="">None</option>
						</select>
					<?php } ?>
			</td>
		</tr>
		<tr>
			<th>People Portal</th>
			<?php $empGroupPPquery = mysql_query("SELECT * FROM employeeGroup AS empGroup INNER JOIN groups AS groups ON empGroup.idGroup = groups.idGroup WHERE empGroup.idE = $idE AND (groups.groupName LIKE 'PP %' OR groups.groupName = 'NA All' ) AND empGroupDelete != 11  ORDER BY groups.groupName DESC ") or die(mysql_error()); ?>
			<td colspan=4><?php

					if (mysql_num_rows($empGroupPPquery) > 0) {
						echo "Yes : ";
					} else {
						echo "No";
					}

				?>
				<?php
					while ($groupPP = mysql_fetch_array($empGroupPPquery)) {
						echo $groupPP['groupName'] . " / ";
					}

				?>
			</td>
		</tr>
		<tr>
			<th>ESS Holiday System</th>
			<td>
				<?php

					if (!isset($_GET['editInfos'])) {
						echo $emp['holidayApp'];
					} else {
						?>
						<select name="holidayApp">
							<option value="<?php echo $emp['holidayApp']; ?>"><?php echo $emp['holidayApp']; ?></option>
							<option value="Requestor">Requestor</option>
							<option value="Approver">Approver</option>
							<option value="Admin">Admin</option>
							<option value="">None</option>
						</select>
					<?php } ?>
			</td>
			<th>Team Lead</th>
			<td><?php if ((memberOf($pp_admin)) || (memberOf($pp_hr))) { ?>
					<?php

					if (!isset($_GET['editInfos'])) {
						if ($emp['teamLead']) {
							echo "Yes";
						} else {
							echo "No";
						}
					} else {
						?>
						<select name="teamLead">
							<option value="<?php echo $emp['teamLead']; ?>"><?php

									if ($emp['teamLead']) {
										echo "Yes";
									} else {
										echo "No";
									}

								?></option>
							<option value="1">Yes</option>
							<option value="0">No</option>
						</select>
					<?php
					}
					// end if isset editInfo
				}

					// end if member of else
					if (memberOf($pp_finance)) {
						if ($emp['teamLead']) {
							echo "Yes";
						} else {
							echo "No";
						}
					}

				?>
			</td>
			<td colspan=2>&nbsp;</td>
		</tr>
		<tr>
			<th>File Server</th>
			<?php $fileServerQuery = mysql_query("SELECT * FROM fileServers AS fl INNER JOIN contFileServers AS cfs ON cfs.idServ = fl.idServ WHERE cfs.idCon = $idCon") or die(mysql_error()); ?>
			<td colspan=3>
				<?php

					if (!isset($_GET['editInfos'])) {
						while ($fileServer = mysql_fetch_array($fileServerQuery)) {
							echo $fileServer['serverName'] . " / ";
						}
					} else {
						if ((memberOf($pp_admin)) || (memberOf($pp_hr))) {
							$fileServerAllQuery = mysql_query("SELECT * FROM fileServers ORDER BY serverName ASC") or die(mysql_error());
							while ($fileServer = mysql_fetch_array($fileServerAllQuery)) {
								$idServ = $fileServer['idServ'];
								$checkServer = mysql_query("SELECT * FROM contFileServers WHERE idServ = $idServ AND idCon=$idCon") or die(mysql_error());
								if (mysql_num_rows($checkServer)) {
									$checked = "checked";
								} else {
									$checked = "";
								}
								echo "<label><input type=\"checkbox\" $checked  name=\"fileServer[]\" value=\"" . $fileServer['idServ'] . "\" /> " . $fileServer['serverName'] . "</label><br />";
							}
						} else {
							while ($fileServer = mysql_fetch_array($fileServerQuery)) {
								echo $fileServer['serverName'] . " / ";
							}
						}
					}

				?>
			</td>
			<td colspan=1>&nbsp;</td>
		</tr>
		<tr>
			<th>VPN</th>
			<td colspan=3>
				<?php

					if (!isset($_GET['editInfos'])) {
						if ($emp['vpn']) {
							echo "Yes";
						} else {
							echo "No";
						}
					} else {
						?>
						<?php

						if ($emp['vpn']) {
							$checked = "checked";
						} else {
							$checked = "";
						}

						?>
						<?php if ((memberOf($pp_admin)) || (memberOf($pp_hr))) { ?>
							<input type="checkbox" name="vpn" <?php echo $checked; ?> tabindex=330/>
						<?php } ?>
					<?php } ?>
			</td>
			<td colspan=1>&nbsp;</td>
		</tr>
	</table>
</div> <!-- closes personalInfos System and Standard access-->
<?php } ?>
<?php if ((memberOf($pp_admin)) || (memberOf($pp_hr)) || (memberOf($pp_finance))) { ?>
<div id="personnalInfos">
<h5>Finance Access</h5>
<table class="table table-condensed">
<tr>
	<th>Timesheets</th>
	<td>
		<?php

			if (!isset($_GET['editInfos'])) {
				if ($emp['timesheets']) {
					echo "Yes";
				} else {
					echo "No";
				}
			} else {
				?>
				<?php

				if ($emp['timesheets']) {
					$checked = "checked";
				} else {
					$checked = "";
				}

				?>
				<input type="checkbox" name="timesheets" <?php echo $checked; ?> tabindex=330/>
			<?php } ?>
	</td>
	<th>Timesheets Blocking</th>
	<td>
		<?php

			if (!isset($_GET['editInfos'])) {
				if ($emp['timesheetblocking']) {
					echo "Yes";
				} else {
					echo "No";
				}
			} else {
				?>
				<?php

				if ($emp['timesheetblocking']) {
					$checked = "checked";
				} else {
					$checked = "";
				}

				?>
				<input type="checkbox" name="timesheetblocking" <?php echo $checked; ?> tabindex=330/>
			<?php } ?>
	</td>
	<!--		<th><?php

		if ($emp['maconomy'] == 1) {
			echo "Maconomy Role";
		} else {
			if (isset($_GET['editInfos'])) {
				echo "Maconomy Role";
			}
		}

	?></th>
		<td colspan=2><?php

		if ($emp['maconomy'] == 1) {
			echo edit("maconomyRol", "");
		}

	?></td> -->
</tr>
<tr>
	<th>Job Cost</th>
	<td width="10%">
		<?php

			if (!isset($_GET['editInfos'])) {
				if ($emp['financeJobCost']) {
					echo "Yes";
				} else {
					echo "No";
				}
			} else {
				?>
				<?php

				if ($emp['financeJobCost']) {
					$checked = "checked";
				} else {
					$checked = "";
				}

				?>
				<input type="checkbox" name="jobcost" <?php echo $checked; ?> tabindex=340/> <?php } ?>
	</td>
	<th>Account Payable</th>
	<td width="10%">
		<?php

			if (!isset($_GET['editInfos'])) {
				if ($emp['financeAccountsPayable']) {
					echo "Yes";
				} else {
					echo "No";
				}
			} else {
				?>
				<?php

				if ($emp['financeAccountsPayable']) {
					$checked = "checked";
				} else {
					$checked = "";
				}

				?>
				<input type="checkbox" name="accountspayable" <?php echo $checked; ?> tabindex=350 /><?php } ?>
	</td>
	<th>Accounts Receivable</th>
	<td width="10%">
		<?php

			if (!isset($_GET['editInfos'])) {
				if ($emp['financeAccountReceivable']) {
					echo "Yes";
				} else {
					echo "No";
				}
			} else {
				?>
				<?php

				if ($emp['financeAccountReceivable']) {
					$checked = "checked";
				} else {
					$checked = "";
				}

				?>
				<input type="checkbox" name="accountsreceivable" <?php echo $checked; ?> tabindex=360><?php } ?>
	</td>
	<th>Fixed Assets</th>
	<td>
		<?php

			if (!isset($_GET['editInfos'])) {
				if ($emp['financeFixedAssets']) {
					echo "Yes";
				} else {
					echo "No";
				}
			} else {
				?>
				<?php

				if ($emp['financeFixedAssets']) {
					$checked = "checked";
				} else {
					$checked = "";
				}

				?>
				<input type="checkbox" name="fixedassets" <?php echo $checked; ?> tabindex=370 /><?php } ?>
	</td>
</tr>
<tr>
	<th>Purchase Orders</th>
	<td width="10%">
		<?php

			if (!isset($_GET['editInfos'])) {
				if ($emp['financePurchaseOrders']) {
					echo "Yes";
				} else {
					echo "No";
				}
			} else {
				?>
				<?php

				if ($emp['financePurchaseOrders']) {
					$checked = "checked";
				} else {
					$checked = "";
				}

				?>
				<input type="checkbox" name="purchaseorders" <?php echo $checked; ?> tabindex=380 /><?php } ?>
	</td>
	<th>Invoicing</th>
	<td>
		<?php

			if (!isset($_GET['editInfos'])) {
				if ($emp['financeInvoicing']) {
					echo "Yes";
				} else {
					echo "No";
				}
			} else {
				?>
				<?php

				if ($emp['financeInvoicing']) {
					$checked = "checked";
				} else {
					$checked = "";
				}

				?>
				<input type="checkbox" name="invoicing" <?php echo $checked; ?> tabindex=390 /><?php } ?>
	</td>
	<th>General Ledger</th>
	<td>
		<?php

			if (!isset($_GET['editInfos'])) {
				if ($emp['financeGeneralLedger']) {
					echo "Yes";
				} else {
					echo "No";
				}
			} else {
				?>
				<?php

				if ($emp['financeGeneralLedger']) {
					$checked = "checked";
				} else {
					$checked = "";
				}

				?>
				<input type="checkbox" name="generalledger" <?php echo $checked; ?> tabindex=400 /><?php } ?>
	</td>
	<th>HR</th>
	<td>
		<?php

			if (!isset($_GET['editInfos'])) {
				if ($emp['financeHR']) {
					echo "Yes";
				} else {
					echo "No";
				}
			} else {
				?>
				<?php

				if ($emp['financeHR']) {
					$checked = "checked";
				} else {
					$checked = "";
				}

				?>
				<input type="checkbox" name="hr"  <?php echo $checked; ?> tabindex=410 /><?php } ?>
	</td>
</tr>
</table>
</div> <!-- closes PersonalInfos Finance access -->
<?php } ?>
</div> <!-- closes rightpane -->
</div> <!-- closes profile -->
</form>

<?php
	} // While emp
	} // isset id
?>

</center>
