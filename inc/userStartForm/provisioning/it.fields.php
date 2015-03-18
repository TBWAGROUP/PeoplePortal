<script>
	$(document).ready(function(){
		$(".syncMess").hide();
		$(".sync").click(function()
		{
			$(".syncMess").show();
			$(".syncBtn").hide();
		});
		
		$(".changeOUtab").hide();
		$(".changeOUbtn").click(function()
		{
			$(".changeOUtab").show();
		});
	});
</script>

	<span class="syncMess">
	<p align="center" class="text-default"><br /><strong><img src="img/ajax-loader.gif" /> Synchronisation... This process can take a while...</strong></p></span>

	
	
	
	
	
<!--
************************************************************************************************************************
************************************************************************************************************************
************************************************************************************************************************
************************************************************************************************************************
************************************************************************************************************************

Activation Fields
-->

<?php
	if (isset($_GET['idCon'])) { $activeThis = "AND cont.idCon=\"$_GET[idCon]\" AND (cont.validationStage = 2 OR cont.validationStage = 1)  "; } else { $activeThis = ""; } 
	$idConUrl = mysql_real_escape_string($_GET['idCon']);
	$idEurl = mysql_real_escape_string($_GET['idE']);
	$queryCont = mysql_query("
												SELECT * FROM contracts AS cont
												INNER JOIN employees AS emp ON cont.idCon = emp.contract
												INNER JOIN labels AS lab ON lab.idLab = cont.idLab
												INNER JOIN departments AS dep ON dep.idDep = cont.idDep
												INNER JOIN functions AS func ON func.idFunc = cont.idFunc
												WHERE emp.idE = $idEurl AND (cont.validationStage = 2 OR cont.validationStage = 1)
												ORDER BY cont.startDateTS ASC
											") or die (mysql_error() );
	$queryDomain = mysql_query("
															SELECT * FROM emailAliasesEmp AS eae
															INNER JOIN emailAliases AS ea ON eae.idAliase = ea.idAliase
															WHERE eae.idCon = $idConUrl
														")or die (mysql_error());										
	
	$queryMailGroup = mysql_query("
															SELECT * FROM employeeGroup AS empGroup
															INNER JOIN groups AS groups ON empGroup.idGroup = groups.idGroup
															WHERE empGroup.idE = $idE AND groups.groupName LIKE '%MD %'
														")or die (mysql_error());										
											
if (isset($_GET['adding']))
{
	$contract = mysql_fetch_array($queryCont);
	$idCon = $contract['idCon'];
	mysql_query ("UPDATE contracts SET internalPhone = \"$_POST[internalPhone]\", validationStage=0 WHERE idCon = \"$idCon\" ") or die (mysql_error());
	mysql_query ("UPDATE employees SET ppAccountStatut=0 WHERE idE = \"$idEurl\" ") or die (mysql_error());
	// updating approvals here
	$queryGroupId = mysql_query("SELECT idGroup FROM groups WHERE groupName = \"$pp_admin\"") or die (mysql_error());
	$idGroup = array_shift(mysql_fetch_array($queryGroupId));
	$queryGroupStatut = mysql_query("SELECT * FROM approvals WHERE idGroup = $idGroup AND idCon = $idCon") or die (mysql_error());
	$checkApprov = mysql_num_rows($queryGroupStatut );
	
	

	$fullCn = $_POST['fullCn'];
	$upn = $contract['upn'];
	$cn = $contract["firstname"]." ".$contract["lastname"];
	include ("inc/ADcheck.inc.php");
	// above include will result in setting $addingAD and $correctlyAdded (TRUE or FALSE) for use in includes below
	
	if ($addingAD) {
		include ("inc/addToAD.inc.php");
		approvalUp ($currentIdE, $idGroup, $idConUrl);
	}

	if ($correctlyAdded)
	{
		include ($_SERVER['DOCUMENT_ROOT'] . "/inc/sync/pushTeamLead.php");
		include($_SERVER['DOCUMENT_ROOT'] . "/inc/sync/pushEmployeeGroups.php");
		approvalUp ($currentIdE, $idGroup, $idConUrl);
	}
}
else {
	
	while ($contract = mysql_fetch_array($queryCont)) {

	if ($contract['empType'] == "Intern") { $OU = "Interns"; }
	else if ($contract['empType']  == "Contractor timebased") { $OU = "Contractors"; } 
	else if ($contract['empType']  == "Contractor fixed") { $OU = "Contractors"; }
	else if ($contract['empType']  == "Freelance") { $OU = "Freelancers"; }
	else if ($contract['empType']  == "Administrator") { $OU = "Administrators"; }
	else { $OU=$contract['empType']; }
	
?>


	
	 
<center>
<p>
	<form method="POST" action='index.php?p=emp&idE=<?php echo $idEurl; ?>&idCon=<?php echo $contract['idCon']; ?>&activation&adding' name="usf1">

	<table class="userStartForm" width="465">
	  <?php if ($contract['validationStage'] >= 1 && $contract['validationStage'] != 2){ ?>
	  <tr>
	    <td colspan="3" class="btn-danger"><center>
	      <span class='text-default'>Contract not yet approved by HR</span>
	      </center></td>
      </tr>
	  <?php } ?>
	  <tr>
	    <td colspan="3"><center>
	      Contract <?php echo $contract['idCon']; ?>, start date: <?php echo $contract['startDate']; ?> - end date: <?php echo $contract['endDate']; ?>
	      </center></td>
      </tr>
		<tr>
			<th colspan="3" class="title"><center>Special IT Access</center></th>
		</tr>	
		<tr>
			<?php if ($contract['itComputerAdmin']) { $checked = "checked"; } else { $checked = "";}   ?>
			<td><input type="checkbox" name="computeradmin" <?php echo $checked; ?> tabindex = 320 disabled /> Computer Admin</td>
				<?php if ($contract['itNetworkAdmin']) { $checked = "checked"; } else  { $checked = ""; }   ?>
			<td><input type="checkbox" name="networkadmin" <?php echo $checked; ?> tabindex = 330 disabled /> Network Admin</td>
			<td>
					<strong>Workstation :</strong>
					<br />
					<?php if (strpos($contract['workstation'], "Windows")) { $img="windowsos.png"; } else if (strpos($contract['workstation'], "Mac")) { $img="macos.png"; } else { $img="noOkS.png"; } ?> 
					<img src="img/<?php echo $img; ?>" /> <?php echo $contract['workstation']; ?>
			</td>
		</tr>
		<tr>
			<?php if ($contract['vpn']) { $checked = "checked"; } else { $checked = "";}   ?>
			<td><input type="checkbox" name="vpn" <?php echo $checked; ?> tabindex = 320 disabled /> VPN</td>
				<?php if ($contract['fileserver']) { $checked = "checked"; } else  { $checked = ""; }   ?>
			<td><input type="checkbox" name="fileserver" <?php echo $checked; ?> tabindex = 330 disabled /> File Server</td>
			<td>
				<?php if ($contract['internalPhone']) { $checked = "checked"; } else { $checked = "";}   ?>
				<input type="checkbox" name="internalPhoneCB" <?php echo $checked; ?> tabindex = 320 disabled /> Internal Phone
				<input type="text" name="internalPhone" size=3 value="<?php echo $contract['internalPhone']; ?>" />
			</td>
		</tr>
		<tr>
			<?php if ($contract['3gData'] == "National") { $img3G="national.png"; } else if ($contract['3gData'] == "" ) { $img3G="noOkS.png"; } else { $img3G="international.png"; } ?> 
		  <td colspan="2"><strong>3G Data : </strong> <img src="img/<?php echo $img3G; ?>" /> <?php echo $contract['3gData']; ?></td>
		  <td>				
				<?php if ($contract['maconomy']) { $checked = "checked"; } else { $checked = "";}   ?>
				<input type="checkbox" name="vpn" <?php echo $checked; ?> tabindex = 320 disabled /> Maconomy
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<a class="changeOUbtn" href="#"><span style="font-size:12px;">
					<?php if (($contract['empType'] == "Employee") || ($contract['empType'] == "Employee temporary") || ($contract['empType'] == "Administrator")) { ?>
					<?php echo "<ul><li>Employees</li><ul><li><strong>".$contract['companyCode']." ".$contract['labelName']."</strong></ul></ul>"; ?>
					<?php } else { ?>
					<?php echo  "<ul><li>Employees</li><ul><li>".$contract['companyCode']." ".$contract['labelName']. "</li><ul><li><strong>". $contract['companyCode']." ".$contract['labelName']." ".$OU."</strong></li></ul></ul></ul>"; ?>
					<?php } ?></span>								
			</td>

	  </tr>
	  <tr>
	    <th colspan="3" class="title"><center>
	      <img src="img/windowsos.png" alt="" /> AD &amp; <img src="img/gmailS.png" />
        </center></th>
      </tr>
	  <tr>	    
	  <th>Email(s)</th>
	    <td colspan="2">				
		<ul>
					<?php while ($gMail = mysql_fetch_array($queryDomain)) { ?>
					<li><?php echo $contract['upn']; ?><?php echo $gMail['email']; ?></li>
					<?php } ?>
				</ul>
		</td>
      </tr>
	  <tr>
	    <th>Mailgroup(s)</th>
	    <td colspan="2">
		<ul>
					<?php while ($mailGroup = mysql_fetch_array($queryMailGroup)) { ?>
					<li><?php echo $mailGroup['groupName']; ?></li>
					<?php } ?>
				</ul>			
		</td>
      </tr>
                <tr>
                        <th colspan="3" class="title"><center>Remarks</center></th>
                </tr>
		<tr>
			<td colspan=3><?php echo nl2br($contract['note']); ?></td>
		</tr>
	  </table>
	  

	<br />
	
	
	<span class="changeOUtab">
	<table width="465" class="userStartForm">
	  <?php if ($contract['validationStage'] >= 1 && $contract['validationStage'] != 2){ ?>
	  <?php } ?>
	  <tr>
		<th class="title"><center>
		  Change OU :
		</center></th>
	  </tr>
		<tr>
			<?php 
					$egraphicsSubOu = "";
					if ($contract['labelName'] == "E-GRAPHICS Print" || $contract['labelName'] == "E-GRAPHICS AV" || $contract['labelName'] == "E-GRAPHICS Digital")
						{ $egraphicsSubOu = ", OU=03032016 E-GRAPHICS"; }
				if (($contract['empType'] == "Employee") || ($contract['empType'] == "Employee temporary") || ($contract['empType'] == "Administrator")) {
			?>
			<td><input type="text" size="60" name="fullCn" value="<?php echo "OU=".$contract['companyCode']." ".$contract['labelName'].$egraphicsSubOu; ?>" /></td>
			<?php } else { ?>
			<td><input type="text" size="60" name="fullCn" value="<?php echo "OU=".$contract['companyCode']." ".$contract['labelName']." ".$OU.", OU=".$contract['companyCode']." ".$contract['labelName'].$egraphicsSubOu; ?>" /></td>
			<?php } ?>
		</tr>
	  </table>
	 </span>
	  
	<p>&nbsp;</p>
	<span class="syncBtn">
<?php if (isset($_GET['idCon'])) { ?><button class="btn btn-default sync" title="Add the user on the Active Directory"><img src="img/userAddS.png" /> Add user</button><?php } ?>
</span>
	</form>	
<p>&nbsp;</p>
<?php }

} // !isset adding
 ?>
</center>


