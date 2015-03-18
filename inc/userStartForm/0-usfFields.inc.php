

<script>

  $(function() {
    $( ".datepicker" ).datepicker({dateFormat: 'dd/mm/yy', firstDay:1, changeMonth:true, changeYear:true, yearRange: "1900:2100", constrainInput:true, showWeek:true  });
  });

  
				// check if a checkbox is checked
				$(document).ready(function(){
					$(".nextMessCB").show();
					$(".nextBtnCB").hide();
										
					$("#language").hide();
				
				    var $checkboxes = $('#usfMentor [type="radio"]');
				    var $emailDomain = $('#usfEmailDom [type="checkbox"]');
						
					$checkboxes.change(function(){
						var countCheckedCheckboxes = $checkboxes.filter(':checked').length;
						
						if (countCheckedCheckboxes > 0) 
						{
							$(".nextMessCB").hide();
							$(".nextBtnCB").show();
						}
						else
						{
							$(".nextMessCB").show();
							$(".nextBtnCB").hide();
						}
					});
					
					$emailDomain.change(function(){
						var countCheckedCheckboxes = $emailDomain.filter(':checked').length;
						
						if (countCheckedCheckboxes > 0)
						{
							$(".nextMessCB").hide();
							$(".nextBtnCB").show();
						}
						else
						{
							$(".nextMessCB").show();
							$(".nextBtnCB").hide();
						}
					});					
					
				});
				
</script>


<center>



<?php 
// initialisation
$hrApproval = FALSE;
$stage=FALSE; 
$resume=FALSE; // all data


if (isset($_GET['stage']))
{
	$stage = TRUE;
	$title = "<h1><img src='img/user.png' />  User Start Form</h1>";
	$action = 0; // add fields file
}
if (isset($_GET["resume"]))
{
	$resume = TRUE;
	$title = "<h1><img src='img/user.png' />  User Start Form</h1>";
	$action = 0;
}	

else
{
	$title = "<h1><img src='img/user.png' />  User Start Form</h1>";
	$action = 0;
}
echo $title;
?>
  <?php if (!$resume) { ?>
  <script>

	$(document).ready(function(){	

		$(".phoneField").hide();
		$(".phoneCb").click(function()
		{
			$(".phoneField").show();
		});
		$(".wsField").hide();
		$(".wsCb").click(function()
		{
			$(".wsField").show();
		});

		$(".maconomyField").hide();
		$(".maconomy").click(function()
		{
			$(".maconomyField").show();
		});
	});
	</script>

<?php } ?>

<hr />

<!--
**********************************************************************************************************************************
**********************************************************************************************************************************
**********************************************************************************************************************************
**********************************************************************************************************************************
**********************************************************************************************************************************
-->
<?php if ($resume)
{
?>
	<!-- if the user is on the RESUME page (end) -->
	<h2>Overview</h2>
	<form method="POST" action='index.php?p=<?php echo $action; ?>-usfSend' name="usf1">
<?php
}
if ( ($resume) || (!$stage) )
{

?>

	<h4>Identity Info</h4>
	
	<form method="POST" action='index.php?p=0-usfFields&stage=10' name="usf1">

		<table class="userStartForm">
			<tr>
					<th colspan="2" class="title"><center>Requestor</center></th>
					<th colspan="2" class="title"><center>Requested Info</center></th>
			</tr>
			<tr>
				<td colspan=2></td>
				<td colspan=2><span class="text-danger"><center><strong>Please check the correct spelling (as on ID card)</strong></center></span>
				</td>
			</tr>
				<th>First Name</th>
				<td><input  type="hidden" size="20"   name="firstnameRF" <?php if ($resume) { ?>value="<?php echo $_POST['firstnameRF']; } else { echo "value='$_SESSION[firstname]' "; }  ?>"  /> 
						<?php if ($resume) { echo $_POST['firstnameRF']; } else { echo "$_SESSION[firstname] "; }  ?>
				</td>
				<th>First Name</th>
				<td><input  type="text" size="20" name="firstnamePF" <?php if ($resume) { ?>value="<?php echo $_POST['firstnamePF']; }  ?>" tabindex = 10 required  autofocus /></td>
			</tr>
			<tr>
				<th>Last Name</th>
				<td><input size="20" type="hidden" name="lastnameRF" <?php if ($resume) { ?>value="<?php echo $_POST['lastnameRF']; } else if ($hrApproval) { echo "value='$requestor[lastname]' "; } else { echo "value='$_SESSION[lastname]' "; } ?>"  /> 
						<?php if ($resume) { echo $_POST['lastnameRF']; } else { echo "$_SESSION[lastname] "; } ?>
				</td>
				<th>Last Name</th>
				<td><input size="20" type="text" name="lastnamePF" <?php if ($resume) { echo "value=\"".$_POST['lastnamePF']."\""; }  ?>  tabindex = 20 require required />			
				</td>
			</tr>
			<tr>
				<th>Email</th>
				<td><input size="20" type="hidden" name="email" <?php if ($resume) { echo $_POST['email']; } ?>" />
						<?php if ($resume) { echo $currentUser['primaryEmail']; } else { echo $currentUser['primaryEmail']; } ?>
				</td>
					<?php if (isset($_GET["resume"])) { if ($_POST['createEmail'] == 1) { $checked = "checked"; } else  { $checked = ""; }  } else { $checked = "checked"; }   ?>
				<td colspan=2><center><label><input type="checkbox" name="createEmail" class="createEmail" <?php echo $checked; ?> tabindex = 40/> <img src="img/email12x12.png" /> <strong>This user needs an email address</strong></label></center></td>
			</tr>
		</table>
		
		<p>&nbsp;</p>
		
	<table  class="userStartForm">
			<tr>
					<th class="title" style="width:100px;"><center>Language</center></th>
					<th class="title"><center>Type</center></th>
					<th class="title"><center>Label</center></th>
					<th class="title"style="width:100px;"><center>Payroll</center></th>
			</tr>
		<tr>
			<td><center>
				<select name="languageSelect" id="languageSelect" tabindex = 50>
					<?php if ($resume) { ?><option value="<?php echo $_POST['language']; ?>"><?php echo $_POST['language']; ?></option><?php } ?>
					<option value="NL">NL</option>
					<option value="FR">FR</option>
					<option value="ENG">ENG</option>
					<option value="Other">Other</option>
				</select>
				<script>
					$( "#languageSelect" ).change( function () {
							$("#language").val($(this).val());
							var lang = $(this).val();
							// alert (lang);
							if (lang == "Other") { $("#language").show(); } else { $("#language").hide(); }
						}).change();
				</script>
				<input type="text" name="language"  size=10 id="language" value="NL" onClick = "value='' ">
				
				</center>
			</td>
				<td><center>
					<select name="type" tabindex = 60>
						<?php if ($resume) { ?><option value="<?php echo $_POST['type']; ?>"><?php echo $_POST['type']; ?></option><?php } ?>
						<option value="Employee">Employee</option>
                                                <option value="Employee temporary">Employee temporary</option>
						<option value="Contractor timebased">Contractor timebased</option>
						<option value="Contractor fixed">Contractor fixed</option>
						<option value="Freelance">Freelance</option>
						<option value="Intern">Intern</option>
						<option value="Administrator">Administrator</option>
					</select>
					</center>
				</td>
				<td>
					<select name="label" id="label" tabindex = 70>
					<?php if ($resume) { ?><option value="<?php echo $_POST['label']; ?>"><?php echo $_POST['label']; ?></option><?php } ?>
					<option value=""></option>
					<?php
						$queryComp = mysql_query("SELECT * FROM labels  WHERE hidden=0 ORDER BY labelName ASC") or die(mysql_error());
						$queryNbr = mysql_num_rows($queryComp);
						while($company=mysql_fetch_array($queryComp))
						{
						?>
						<option value="<?php echo $company['labelName']; ?>"><?php echo $company['labelName']; ?></option>
					<?php } ?>
						</select>
				</label>
			<td>
				<script>
				<?php if ($resume) { ?>
					$(document).ready(function(){
						$(".payrollFunc").load("inc/userStartForm/0-payrollFunc.inc.php?label=<?php echo $_POST['label']; ?>");
					  });	
					  <?php }   
					  else { ?>
						$(document).ready(function(){
						$(".payrollFunc").load("inc/userStartForm/0-payrollFunc.inc.php?label=");
					  });
					<?php } ?>
					$( "#label" ).change( function () {
						var str = "";
						  str = this.value;
							str = encodeURIComponent(str.trim())
						$(".payrollFunc").load("inc/userStartForm/0-payrollFunc.inc.php?label=" + str + "");
					  }).change();
				</script>
				<div class="payrollFunc"></div>
				<?php 
					if (!isset($_POST['payroll']))
					{ $_POST['payroll'] = ""; }
					?>
			  </td>
				</td>
			</tr>
	</table>
	
<p>&nbsp;</p>
	
	<?php if ( (memberOf($pp_hr)) || (memberOf($pp_admin)) ) { ?>
	<table class="userStartForm" width="650px">
			<tr>
					<th colspan="4" class="title"><center>HR Info</center></th>
			</tr>
			<tr>
				<th>Time Regime</th>
					<td>
						<select name="timeRegime">
							<?php if ($resume) { echo "<option value=\"".$_POST['timeRegime']. " \">".$_POST['timeRegime']."</option>"; } ?>
							<option value="5/5">5/5</option>
							<option value="4.5/5">4.5/5</option>
							<option value="4/5">4/5</option>
							<option value="3/5">3/5</option>
							<option value="2.5/5">2.5/5</option>
						</select>
					</td>
				<th>Birth Date</th>
				<td><input  type="text" size="20" name="birthdate" class="datepicker" <?php if ($resume) { ?>value="<?php echo $_POST['birthdate']; } ?>" tabindex = 130 /></td>
			</tr>
<tr>
	<th>Home Address</th>
				<td colspan="4"><textarea cols="75" rows="3" type="text" name="address" tabindex = 140 placeholder="Street and Number on first line&#10;Zip Code and City on second line"><?php if ($resume) { echo $_POST['address']; } ?></textarea></td>
<tr>
			<th>Emergency Contact(s)</th>
		<td colspan=4><textarea cols="75" rows="3" type="text" name="emergencyContact" tabindex = 120 placeholder="Full Name, relation, language (NL/FR/EN): phone number (international format +32123456789)&#10;start a new line for every additional entry"><?php if ($resume) { echo $_POST['emergencyContact']; }  ?></textarea></td>
		</tr>
			</tr>
		</table>
	<?php } // member of hr approval
			else
			{
	?>
		<!-- HR FIELDS -->
	<input type="hidden" name="transportEntry" value="">
	<input type="hidden" name="timeRegime" value="">
	<input type="hidden" name="statuut" value="">
	<input type="hidden" name="emergencyContact" value="">
	<input type="hidden" name="birthdate" value="">
	<input type="hidden" name="address" value="">
	<!-- HR FIELDS -->
	<?php } // end if member of hr approval ?>

	<?php if (!$resume){ ?>
	<p><br /><button class='btn usf btn-primary' ><img src='img/next24x24Wht.png' /> Next</button></p>
	</form>
	<?php } ?>

	<!--
**********************************************************************************************************************************
**********************************************************************************************************************************
**********************************************************************************************************************************
**********************************************************************************************************************************
**********************************************************************************************************************************
-->




<?php 
} // if !isset get stage
if (($stage) || ($resume) )
{
if (($_GET['stage'] == 10) || ($resume) )
{
?>
<h4>Department Info</h4>

	<form method="POST" action='index.php?p=0-usfFields&stage=20' name="usf1">
	<?php if (!$resume) { 
	echo "<p><i>".$_POST['type']." @ ".$_POST['label']."</i></p>";
	}

		$searchField = $_POST['label'];
		$upn = genUpn($_POST['firstnamePF'],$_POST['lastnamePF']);

		$search = strtolower(substr($searchField, 0, 4)); //search with the 4 first char	
		$queryDep = mysql_query("SELECT * FROM departments WHERE nameDepartment LIKE '%".$search."%' AND hidden=0 ORDER BY nameDepartment ASC") or die(mysql_error());
		$queryDepAll = mysql_query("SELECT * FROM departments WHERE nameDepartment NOT LIKE '%".$search."%' AND hidden=0 ORDER BY nameDepartment ASC") or die(mysql_error());
	?>

	<table  class="userStartForm">
			<tr>
					<th class="title"><center>Login AD</center></th>
					<th class="title"><center>Department</center></th>
			</tr>
			<tr>
				<td><center><input  type="text" size="20" name="loginAD" <?php if ($resume) {  echo "value='$_POST[loginAD]'"; } else { echo "value='$upn'"; } ?> required/></center></td>
				<td>
					<select name="department" tabindex = 150>
					<?php if ($resume) { ?><option value="<?php echo $_POST['department']; ?>"><?php echo $_POST['department']; ?></option><?php } ?>
					<?php if (mysql_num_rows($queryDep) > 0) { ?>
						<optgroup label="Suggestion(s)">
							<?php while($department=mysql_fetch_array($queryDep)) { ?>
								<option value="<?php echo $department['nameDepartment']; ?>"><?php echo $department['nameDepartment']; ?></option>
							<?php }  ?>
						</optgroup>
						<?php } // no suggestions ?>
						
						<optgroup label="Other">
							<?php while($department=mysql_fetch_array($queryDepAll)) { ?>
								<option value="<?php echo $department['nameDepartment']; ?>"><?php echo $department['nameDepartment']; ?></option>
							<?php } ?>
						</optgroup>
						
					</select>
				</td>
			</tr>				
	</table>
	
	<p>&nbsp;</p>
	
	<table  class="userStartForm">
			<tr>
				<th>Start Date</th>
				<td>
					<input  type="text" size="20" name="startDate" class="datepicker" value='<?php if ($resume) {  echo "$_POST[startDate]"; } ?>' tabindex = 160 />
				</td>
			</tr>
			
			<tr>
				<th>End Date</th>
				<td>
					<input  type="text" size="20" name="endDate" id="endDate" class="datepicker" value='<?php if ($resume) {  echo "$_POST[endDate]'"; }  ?>'  tabindex = 170 />
					<input type="hidden" name="businessCardNeeded" value="0">
				</td>
		</tr>

		<tr>	
				<?php if ($resume) { if ($_POST['businessCardNeeded']) { $checked = "checked value=1"; } else  { $checked = ""; }  } else { $checked = "checked value=1"; }   ?>
				<?php if (($_POST["type"] != "Employee") && ($_POST["type"] != "Contractor fixed")) { $checked = ""; } ?>
				<td colspan="2"><label><input type="checkbox" name="businessCardNeeded" <?php echo $checked; ?> tabindex = 380 /> <strong>This user needs a business card</strong></label></td>
		</tr>
	</table>
	

	
	<?php if (isset($_POST['createEmail'])) { ?>
	<input type="hidden" name="createEmail" value="1">
	<?php } else { ?>
	<input type="hidden" name="createEmail="0">
	<?php } ?>
	
	
	<!-- HR FIELDS -->
	<input type="hidden" name="transportEntry" value="<?php echo $_POST['transportEntry']; ?>">
	<input type="hidden" name="timeRegime" value="<?php echo $_POST['timeRegime']; ?>">
	<input type="hidden" name="essApprovers" value="<?php echo $_POST['essApprovers']; ?>">
	<input type="hidden" name="emergencyContact" value="<?php echo $_POST['emergencyContact']; ?>">
	<input type="hidden" name="birthdate" value="<?php echo $_POST['birthdate']; ?>">
	<input type="hidden" name="address" value="<?php echo $_POST['address']; ?>">
	<!-- HR FIELDS -->
	
	<input type="hidden" name="language" value="<?php echo $_POST['language']; ?>">
	<input type="hidden" name="lastnameRF" value="<?php echo $_POST['lastnameRF']; ?>">
	<input type="hidden" name="firstnameRF" value="<?php echo $_POST['firstnameRF']; ?>">
	<input type="hidden" name="lastnamePF" value="<?php echo $_POST['lastnamePF']; ?>">
	<input type="hidden" name="firstnamePF" value="<?php echo $_POST['firstnamePF']; ?>">
	<input type="hidden" name="email" value="<?php echo $_POST['email']; ?>">
	<input type="hidden" name="type" value="<?php echo $_POST['type']; ?>">
	<input type="hidden" name="label" value="<?php echo $_POST['label']; ?>">
	<input type="hidden" name="payroll" value="<?php echo $_POST['payroll']; ?>">
	<?php if (!isset($_GET["resume"])) { ?>
	<p><br /><button class='btn usf btn-primary' ><img src='img/next24x24Wht.png' /> Next</button></p>
	</form>
	<?php } ?>
	
<!--
**********************************************************************************************************************************
**********************************************************************************************************************************
**********************************************************************************************************************************
**********************************************************************************************************************************
**********************************************************************************************************************************
-->

	
	
<?php
} // if stage == 10
if (($_GET['stage'] == 20 || (isset($_GET["resume"]))) ) 
{
?>
<h4>Function</h4>

	<form method="POST" action='index.php?p=0-usfFields&stage=30' name="usf1">
	<?php
	echo $emailName;
	if (!$resume) {
		echo "<p><i>".$_POST['type']." @ ". $_POST['label']." -> ".$_POST['department']."</p></i>";
		$search = strtolower(substr($_POST['department'], 0, 6)); //search with the 8 first char
		$queryFuncSuggest = mysql_query("SELECT * FROM functions WHERE functionName LIKE '%".$search."%' OR functionTags LIKE '%".$_POST['label']."%' OR functionTags LIKE '%".$search."%' AND hidden=0 ORDER BY functionName ASC") or die(mysql_error());
		$queryFunc = mysql_query("SELECT * FROM functions WHERE functionName NOT LIKE '%".$search."%' AND hidden=0 ORDER BY functionName ASC") or die(mysql_error());
	}
	else
	{
			$queryFunc = mysql_query("SELECT * FROM functions ORDER BY functionName ASC") or die(mysql_error());
	}
	?>
	<table  class="userStartForm">
			<tr>
					<th class="title"><center>Select a Function or Job Title</center></th>
			</tr>
		<tr>
				<td>
					<select name="function"  tabindex = 180>
						<?php if ($resume) { ?><option value="<?php echo $_POST['function']; ?>"><?php echo $_POST['function']; ?></option><?php } ?>
						<?php if (mysql_num_rows($queryFuncSuggest) > 0) { ?>
						<optgroup label="Suggestion(s)  ">
							<?php while($function=mysql_fetch_array($queryFuncSuggest)) { ?>
								<option value="<?php echo $function['functionName']; ?>"><?php echo $function['functionName']; ?></option>
							<?php }  ?>
						</optgroup>
						<?php } // no suggestions ?>
						
						<optgroup label="Other">
							<?php while($function=mysql_fetch_array($queryFunc)) { ?>
								<option value="<?php echo $function['functionName']; ?>"><?php echo $function['functionName']; ?></option>
							<?php } ?>
						</optgroup>
					</select>
				</td>
			</tr>
	</table>
	
	
	
	<input type="hidden" name="transportEntry" value="<?php echo $_POST['transportEntry']; ?>">
	<input type="hidden" name="startDate" value="<?php echo $_POST['startDate']; ?>">
	<input type="hidden" name="endDate" value="<?php echo $_POST['endDate']; ?>">
	<input type="hidden" name="language" value="<?php echo $_POST['language']; ?>">
	<input type="hidden" name="timeRegime" value="<?php echo $_POST['timeRegime']; ?>">
	<input type="hidden" name="essApprovers" value="<?php echo $_POST['essApprovers']; ?>">
	<input type="hidden" name="emergencyContact" value="<?php echo $_POST['emergencyContact']; ?>">
	<input type="hidden" name="birthdate" value="<?php echo $_POST['birthdate']; ?>">
	<input type="hidden" name="address" value="<?php echo $_POST['address']; ?>">
	<input type="hidden" name="loginAD" value="<?php echo $_POST['loginAD']; ?>">
	<input type="hidden" name="createEmail" value="<?php echo $_POST['createEmail']; ?>">
	<input type="hidden" name="lastnameRF" value="<?php echo $_POST['lastnameRF']; ?>">
	<input type="hidden" name="firstnameRF" value="<?php echo $_POST['firstnameRF']; ?>">
	<input type="hidden" name="lastnamePF" value="<?php echo $_POST['lastnamePF']; ?>">
	<input type="hidden" name="firstnamePF" value="<?php echo $_POST['firstnamePF']; ?>">
	<input type="hidden" name="email" value="<?php echo $_POST['email']; ?>">
	<input type="hidden" name="type" value="<?php echo $_POST['type']; ?>">
	<input type="hidden" name="label" value="<?php echo $_POST['label']; ?>">
	<input type="hidden" name="payroll" value="<?php echo $_POST['payroll']; ?>">
	<input type="hidden" name="department" value="<?php echo $_POST['department']; ?>">
	<?php if (isset($_POST['businessCardNeeded'])) { ?><input type="hidden" name="businessCardNeeded" value="<?php echo $_POST['businessCardNeeded']; ?>"><?php } else { ?><input type="hidden" name="businessCardNeeded" value="0"> <?php } ?>
	<?php if (!isset($_GET["resume"])) { ?>
	<p><br /><button class='btn usf btn-primary' ><img src='img/next24x24Wht.png' /> Next</button></p>
	</form>
	<?php } ?>

	
<!--
**********************************************************************************************************************************
**********************************************************************************************************************************
**********************************************************************************************************************************
**********************************************************************************************************************************
**********************************************************************************************************************************
-->

<?php
} // if stage == 20
if ($_GET['stage'] == 30 || (isset($_GET["resume"])) )
{
?>
	<h4>
		<?php if ($_POST['type'] == "Intern") {
			echo "Mentor";
		} else {
			echo "Manager";
			if (($_POST['type'] == "Employee") || ($_POST['type'] == "Contractor fixed")) {
				echo " & Holiday Approver";
	}
		}?>
	</h4>

	<form method="POST" action='index.php?p=0-usfFields&stage=40' name="usf1" id="usfMentor">
	<?php
	
		echo "<p><i>".$_POST['type']." @ ".$_POST['label']." -> ".$_POST['department']." -> ".$_POST['function']."</p></i>";
		$search = strtolower(substr($_POST["function"],0, 4));
		$searchDep = $_POST['department'];
	
	if ($_POST['type'] == "Intern") { //list all active accounts as possible Mentors
		$querymanager = mysql_query("
				SELECT * FROM employees AS emp 
				INNER JOIN contracts AS cont ON cont.idCon = emp.contract
				INNER JOIN functions AS func ON cont.idFunc = func.idFunc
				INNER JOIN departments AS dep ON cont.idDep = dep.idDep
				WHERE emp.ppAccountStatut = 0
				AND 
				( func.functionName LIKE '%" . $search . "%'
				OR func.functionTags LIKE '%" . $search . "%'
				OR dep.nameDepartment = \"$searchDep\"
				)
				ORDER BY firstname ASC
		") or die(mysql_error());
		$querymanagerOther = mysql_query("
				SELECT * FROM employees 
				WHERE ppAccountStatut = 0
				ORDER BY firstname ASC
		") or die(mysql_error());
		$queryManNbr = mysql_num_rows($querymanager);
	} else { //for non-interns, limit list to real teamleads
		$querymanager = mysql_query("
				SELECT * FROM employees AS emp
				INNER JOIN contracts AS cont ON cont.idCon = emp.contract
				INNER JOIN functions AS func ON cont.idFunc = func.idFunc
				INNER JOIN departments AS dep ON cont.idDep = dep.idDep
				WHERE emp.ppAccountStatut = 0
				AND
				( func.functionName LIKE '%" . $search . "%'
				OR func.functionTags LIKE '%" . $search . "%'
				OR dep.nameDepartment = \"$searchDep\"
				) AND teamLead=1
				ORDER BY firstname ASC
		") or die(mysql_error());
		$querymanagerOther = mysql_query("
				SELECT * FROM employees
				WHERE teamLead = 1 AND ppAccountStatut = 0
				ORDER BY firstname ASC
		") or die(mysql_error());
		$queryManNbr = mysql_num_rows($querymanager);
	}
		?>
	<table  class="userStartForm" width="250px" <?php if (($_POST['type'] == "Employee") || ($_POST['type'] == "Employee temporary") || ($_POST['type'] == "Contractor fixed") ) { ?>style="float:left;margin-left:250px;"<?php } ?>>
			<tr>
				<th class="title">
					<center>
						<?php if ($_POST['type'] == "Intern") {
							echo "Mentor";
						} else {
							echo "Manager";
						}
						?>
					</center>
				</th>
			</tr>
		<tr>
				<td>
					<div class="scrollGroup">
					<?php if ($resume) 
							{
								echo "<strong>Your selection :</strong><br />";
									$managerName = $_POST["manager"];
									$upn = genUPNCN ($managerName);
									$idEteamLead = mysql_query("SELECT idE, upn FROM employees WHERE upn = \"$upn\" "); 
									$managerBDD = mysql_fetch_array($idEteamLead);
									$managerIdE = $managerBDD["idE"];
					?>
									<label><input type="radio" id="managerCB" name="managerFinal" value="<?php echo $managerIdE; ?>" checked /> <?php echo $managerName ?></label><br />
						<?php 	
								
								echo "<p><strong>All :</strong></p>";
									$upn = genUPNCN($managerName);
									while ($allmanager = mysql_fetch_array($querymanagerOther)) {
					?>
									<label><input type="radio" id="manager" name="managerFinal" value="<?php echo $allmanager["idE"]; ?>" /> <?php echo $allmanager["firstname"]." ".$allmanager["lastname"]; ?></label><br />
						<?php 		
									}
							} // end if isset resum
							else {
						// -------------------------------------------
						 if (mysql_num_rows($querymanager) > 0) { ?>
					<strong>Suggestion(s)</strong><br />
						<?php while($manager=mysql_fetch_array($querymanager)) { ?>
							<label><input type="radio" name="manager" value="<?php echo $manager['firstname']." ".$manager['lastname']; ?>" /> <?php echo $manager['firstname']." ".$manager['lastname']; ?></label><br />
						<?php } } ?>
						<strong>All</strong><br />
							<?php while($manager=mysql_fetch_array($querymanagerOther)) { ?>
								<label><input type="radio" id="manager" name="manager" value="<?php echo $manager['firstname']." ".$manager['lastname']; ?>"  /> <?php echo $manager['firstname']." ".$manager['lastname']; ?></label><br />
							<?php }
								} // else resum
								?>
					</div>
				</td>
			</tr>
	</table>
	
	<!-- HOLIDAY APPROVER -->
<?php if (( $_POST['type'] == "Employee") || ($_POST['type'] == "Contractor fixed") ) { 
		

		$searchDep = $_POST['department'];
		$queryHolidayApp = mysql_query("
				SELECT * FROM employees AS emp 
				INNER JOIN contracts AS cont ON cont.idCon = emp.contract
				INNER JOIN functions AS func ON cont.idFunc = func.idFunc
				INNER JOIN departments AS dep ON cont.idDep = dep.idDep
				WHERE (emp.ppAccountStatut = 0 OR emp.ppAccountStatut = 3)
				AND 
				( func.functionName LIKE '%".$search."%' 
				OR func.functionTags LIKE '%".$search."%' )
				AND (emp.holidayApp=\"Admin\" OR emp.holidayApp=\"Approver\") 
				ORDER BY firstname ASC
		") or die(mysql_error());
		
		$queryHolidayAppOther = mysql_query("
				SELECT * FROM employees 
				WHERE (holidayApp=\"Admin\" OR holidayApp=\"Approver\")  AND (ppAccountStatut = 0 OR ppAccountStatut = 3)
				ORDER BY firstname ASC
		") or die(mysql_error());
		$queryHolidayAppNbr = mysql_num_rows($queryHolidayApp);
		?>
	<table  class="userStartForm" width="250px"style="margin-left:-100px;">
			<tr>
					<th class="title"><center>Holiday Approver(s)</center></th>
			</tr>
			<tr>
				<td>
					<div class="scrollGroup">
					<?php 
					if ($resume) 
							{
								if ($_POST['holidayAppNbr'] > 0)
								{
									echo "<strong>Your selection :</strong><br />";
									foreach($_POST['holidayAppSaved'] as $holidayAppName) 
									{	
										$upn = genUPNCN ($holidayAppName);
										$idEHolidayApp = mysql_query("SELECT idE FROM employees WHERE upn = \"$upn\" "); 
										$holidayAppIdE = array_shift(mysql_fetch_array($idEHolidayApp));
									?>
										<label><input type="checkbox" id="holidayCB" name="holidayApp[]" value="<?php echo $holidayAppIdE; ?>" checked /> <?php echo $holidayAppName; ?></label><br />
						<?php 		}
									echo "<p><strong>All :</strong></p>";
									$upn = genUPNCN($holidayAppName);
									while ($allHolidayApp = mysql_fetch_array($queryHolidayAppOther)) {
					?>
									<label><input type="checkbox" id="holidayCB" name="holidayApp[]" value="<?php echo $allHolidayApp["idE"]; ?>" /> <?php echo $allHolidayApp["firstname"]." ".$allHolidayApp["lastname"]; ?></label><br />
						<?php 		
									}		

								} // edn if holidayAppNbr >0
							} // end if isset resum
							else 
							{
						// -------------------------------------------
						 if (mysql_num_rows($queryHolidayApp) > 0) { ?>
					<strong>Suggestion(s)</strong><br />
						<?php while($HolidayApp=mysql_fetch_array($queryHolidayApp)) { ?>
							<label><input type="checkbox" name="holidayApp[]" value="<?php echo $HolidayApp['firstname']." ".$HolidayApp['lastname']; ?>" /> <?php echo $HolidayApp['firstname']." ".$HolidayApp['lastname']; ?></label><br />
						<?php } } ?>
						<strong>All</strong><br />
							<?php while($HolidayApp=mysql_fetch_array($queryHolidayAppOther)) { ?>
								<label><input type="checkbox" id="holidayAppCB"name="holidayApp[]" value="<?php echo $HolidayApp['firstname']." ".$HolidayApp['lastname']; ?>"  /> <?php echo $HolidayApp['firstname']." ".$HolidayApp['lastname']; ?></label><br />
							<?php } 
							} // end if isset reum
							?>
					</div>
				</td>
			</tr>
	</table>
	<?php } ?>
	<input type="hidden" name="transportEntry" value="<?php echo $_POST['transportEntry']; ?>">
	<input type="hidden" name="startDate" value="<?php echo $_POST['startDate']; ?>">
	<input type="hidden" name="endDate" value="<?php echo $_POST['endDate']; ?>">
	<input type="hidden" name="language" value="<?php echo $_POST['language']; ?>">
	<input type="hidden" name="timeRegime" value="<?php echo $_POST['timeRegime']; ?>">
	<input type="hidden" name="essApprovers" value="<?php echo $_POST['essApprovers']; ?>">
	<input type="hidden" name="emergencyContact" value="<?php echo $_POST['emergencyContact']; ?>">
	<input type="hidden" name="birthdate" value="<?php echo $_POST['birthdate']; ?>">
	<input type="hidden" name="address" value="<?php echo $_POST['address']; ?>">
	<input type="hidden" name="loginAD" value="<?php echo $_POST['loginAD']; ?>">
	<input type="hidden" name="createEmail" value="<?php echo $_POST['createEmail']; ?>">
	<input type="hidden" name="lastnameRF" value="<?php echo $_POST['lastnameRF']; ?>">
	<input type="hidden" name="firstnameRF" value="<?php echo $_POST['firstnameRF']; ?>">
	<input type="hidden" name="lastnamePF" value="<?php echo $_POST['lastnamePF']; ?>">
	<input type="hidden" name="firstnamePF" value="<?php echo $_POST['firstnamePF']; ?>">
	<input type="hidden" name="email" value="<?php echo $_POST['email']; ?>">
	<input type="hidden" name="type" value="<?php echo $_POST['type']; ?>">
	<input type="hidden" name="label" value="<?php echo $_POST['label']; ?>">
	<input type="hidden" name="payroll" value="<?php echo $_POST['payroll']; ?>">
	<input type="hidden" name="department" value="<?php echo $_POST['department']; ?>">
	<input type="hidden" name="function" value="<?php echo $_POST['function']; ?>">
	<?php if (isset($_POST['businessCardNeeded'])) { ?><input type="hidden" name="businessCardNeeded" value="<?php echo $_POST['businessCardNeeded']; ?>"><?php } else { ?><input type="hidden" name="businessCardNeeded" value="0"> <?php } ?>
	

	<?php if (!isset($_GET["resume"]) ) { ?>
		<span class="nextMessCB"><p align="center" class="text-danger"><br />At least one Manager must be chosen</p></span>

		<span class="nextBtnCB">	
					<p><br /><button class='btn usf btn-primary' ><img src='img/next24x24Wht.png' /> Next</button></p>
		</span>
	</form>
	<?php } ?>
	
<!--
**********************************************************************************************************************************
**********************************************************************************************************************************
**********************************************************************************************************************************
**********************************************************************************************************************************
**********************************************************************************************************************************
-->


<?php

} // if stage == 30
if ($_GET['stage'] == 40 || (isset($_GET["resume"])))
{
?>
	<h4>Email and Systems Access Info</h4>

	<form method="POST" action='index.php?p=0-usfFields&stage&resume' name="usf2">
	<?php
	$emailName = genEmailName($_POST['firstnamePF'],$_POST['lastnamePF']);
	$emailField = $_POST['createEmail']; $labelField = $_POST['label'];
	if (($emailField == 1 && ($resume))  || ($emailField == 1) ) {
	
	$search = strtolower(substr($labelField, 0, 7)); //search with the 8 first char

	$queryEmailSuggest = mysql_query("
								SELECT * FROM emailAliases AS em
								INNER JOIN labels AS lab ON em.idLab = lab.idLab
								WHERE labelName LIKE '%".$search."%' AND em.hidden = 0
								ORDER BY lab.level ASC, em.email ASC") or die(mysql_error());
	
	$queryEmail = mysql_query("
								SELECT * FROM emailAliases
								WHERE hidden = 0
								ORDER BY email ASC") or die(mysql_error());

	$queryEmailDomain = mysql_query("
								SELECT * FROM emailAliases AS em
								INNER JOIN labels AS lab ON em.idLab = lab.idLab
								WHERE email LIKE '%".$search."%' AND em.hidden = 0
								ORDER BY lab.level ASC, em.email ASC") or die(mysql_error());

	$queryEmailAll = mysql_query("
								SELECT * FROM emailAliases AS em
								INNER JOIN labels AS lab ON em.idLab = lab.idLab
								WHERE em.hidden = 0
								ORDER BY lab.level ASC, em.email ASC") or die(mysql_error());

?>
	<table class="userStartForm" width="500px">	
		<tr>
			<th class="title"><center>Email domain and aliases (other domains)</center></th>
				<th class="title"><center>Email distribution list</center></th>
		</tr>	
			<tr class="emailHide">
				<td><label><strong>Primary Email:</strong>
					<select name="primaryEmail">
						<?php if ($resume) { ?><option value="<?php echo $_POST['primaryEmail']; ?>"><?php echo $_POST['primaryEmail']; ?></option><?php } ?>
						<optgroup label="Suggestions">
							<?php while($PrimemailAll =mysql_fetch_array($queryEmailSuggest)) { ?>
								<option value="<?php echo $emailName.$PrimemailAll['email']; ?>"><?php echo $emailName.$PrimemailAll['email']; ?></option>
							<?php } ?>
						</optgroup>
						<optgroup label="All">
							<?php while($PrimemailAll =mysql_fetch_array($queryEmail)) { ?>
								<option value="<?php echo $PrimemailAll['email']; ?>"><?php echo $PrimemailAll['email']; ?></option>
							<?php } ?>
						</optgroup>
					</select></label>
					<strong><br />Other Email Aliases needed:</strong>
					<div class="scrollGroup" id="usfEmailDom">
								<?php	
							if (isset($_POST['emailAliases']))
							{
								if ($resume) 
								{
									foreach($_POST['emailAliases'] as $emailAliases) 
									{
										$queryEmailAliaseCheckedName = mysql_query("SELECT * FROM emailAliases WHERE hidden=0 AND idAliase = \"$emailAliases\" "); 
										$emailAliaseCheckedName = mysql_fetch_array($queryEmailAliaseCheckedName);
									?>
										<label><input type="checkbox"  id="managerCB" name="emailAliases[]" value="<?php echo $emailAliases; ?>" checked /> <?php echo $emailAliaseCheckedName['email']; ?></label><br />
					<?php 		}
								} // end if isset resum
							} // end if isset $_POST['emailAliases'] 
						// -------------------------------------------
						?>						
							<strong>List of Aliases</strong><br />
							<?php while($emailAll =mysql_fetch_array($queryEmailAll)) { ?>
								<label><input type="checkbox" id="managerCB" name="emailAliases[]" value="<?php echo $emailAll['idAliase']; ?>" /> <?php echo $emailAll['email']; ?></label><br />
							<?php } ?>
					</div>

				</td>
				<td>
					<div class="scrollGroup" id="usfEmalDL">
					<?php	
						$queryGroupsMD = mysql_query("
																					SELECT * FROM groups
																					WHERE groupName LIKE 'MD %' 
																					ORDER BY groupName ASC
																					"); 
								if ($resume) 
								{ 
									if (isset($_POST['emailDL'])) {
									foreach($_POST['emailDL'] as $groupsMD) 
									{
										$queryEmailDLCheckedName = mysql_query("SELECT * FROM groups WHERE idGroup = \"$groupsMD\" "); 
										$emailDLCheckedName = mysql_fetch_array($queryEmailDLCheckedName);

									?>
										<label><input type="checkbox" id="emailDLCB" name="emailDL[]" value="<?php echo $groupsMD; ?>" checked /> <?php echo $emailDLCheckedName['groupName']; ?></label><br />
					<?php 		}
									echo "<strong>Others</strong><br />";
								}  // end if iset $_POST['emailDL']
								}// end if isset resum
						// -------------------------------------------
						if (!$resume)
						{
							 if (mysql_num_rows($queryGroupsMD) > 0) { ?>
							<?php while($groupsMD = mysql_fetch_array($queryGroupsMD)) { 										
							if ($groupsMD['groupName'] == "MD tbwagroup ALL") { $tbwagroupAll = "checked"; } else { $tbwagroupAll = ""; }
							?>
								<label><input type="checkbox" id="emailDLCB"name="emailDL[]" value="<?php echo $groupsMD['idGroup']; ?>" <?php echo $tbwagroupAll; ?>  /> <?php echo $groupsMD['groupName']; ?></label><br />
						<?php } } } 
						else	{ while($groupsMD = mysql_fetch_array($queryGroupsMD)) { ?>
								<label><input type="checkbox" id="emailDLCB"name="emailDL[]" /> <?php echo $groupsMD['groupName']; ?></label><br />
						<?php } } ?>
		</div>
					
				</td>
			</tr>
	</table>
	<?php } else if (($_POST['createEmail'] == 0 && (isset($_GET["resume"])) ) || ($_POST['createEmail'] == 0) ) { echo "<h5 class='text-danger'>No email address</h5>"; } ?>

	<p>&nbsp;</p>
	
<table class="userStartForm">
		<tr>
			<th class="title"><center>Hardware Needed</h4></center></th>
			<th class="title"><center></h4></center></th>
		</tr>
		<tr>
			<td><label><input type="checkbox" name="workstation" class="wsCb" <?php echo $checked; ?> tabindex = 240 /> Workstation</label>
				<select name="wsType" class="wsField"  tabindex = 250 >
					<?php if ($resume) { ?><option value="<?php echo $_POST['wsType']; ?>"><?php echo $_POST['wsType']; ?></option><?php } ?>
					<option value="Not needed">Not needed</option>
					<option value="Laptop Mac">Laptop Mac</option>
					<option value="Desktop Mac">Desktop Mac</option>
					<option value="Laptop Windows">Laptop Windows</option>
					<option value="Desktop Windows">Desktop Windows</option>
				</select>
			</td>
			<td>
			<label>Kensington Lock
				<select name="kensingtonLockNr" tabindex = 310>
						<?php if ($resume) { ?><option value="<?php echo $_POST['kensingtonLockNr']; ?>"><?php echo $_POST['kensingtonLockNr']; ?></option><?php } ?>
						<option value="">No</option>
						<option value="Yes">Yes</option>
					</select></label>
			</td>
		</tr>
		<tr>	
			<?php if ($resume) { if (isset($_POST['mobilephoneCb'])) { $checked = "checked value=1";  $value="value='$_POST[mobilephone]'"; } else  { $checked = ""; $value=""; }  }   else { $checked = ""; $value=""; }   ?>
			<td>
			<label><input type="checkbox" name="mobilephoneCb"  class="phoneCb" <?php echo $checked; ?> tabindex = 270 /> Mobile Phone</label>
			<input  type="text" size="25" name="mobilephone" <?php echo $value; ?>" placeholder="Phone number to adopt" class="phoneField"/>
			<?php if ($resume) { if (isset($_POST['mobilephoneCb'])) { $checked = "checked value=1";  $value="value='$_POST[mobilePhoneModel]'"; } else  { $checked = ""; $value=""; }  }  else { $checked = ""; $value=""; }   ?>
			<input  type="text" size="25" name="mobilePhoneModel" <?php echo $value; ?> placeholder="Brand and Model to buy" class="phoneField"/>
			</td>
			<?php if ($resume) { if (isset($_POST['internalphone'])) { $checked = "checked value=1"; } else  { $checked = ""; }  } else { $checked = ""; }   ?>
			<td><label>3G Data
				<select name="3Gdata" tabindex = 310>
					<?php if ($resume) { ?><option value="<?php echo $_POST['3Gdata']; ?>"><?php echo $_POST['3Gdata']; ?></option><?php } ?>
					<option value="">None</option>
					<option value="National">National</option>
					<option value="Roaming">Roaming</option>
				</select></label>
			</td>
		</tr>
		<tr>
			<td><label><input type="checkbox" name="internalphone" <?php echo $checked; ?> tabindex = 290 /> Internal Phone</label>
			</td>
			<td ><label>Badge Needed
					<select name="badgeNr" tabindex = 310>
						<?php if ($resume) { ?><option value="<?php echo $_POST['badgeNr']; ?>"><?php if ($_POST['badgeNr'] == "") { echo "No"; } else if ($_POST['badgeNr'] == "0") { echo "Yes"; } ?></option><?php } ?>
						<option value="">No</option>
						<option value="0">Yes</option>
					</select></label>
			</td>

		</tr>
	</table>

<p>&nbsp;</p>
	
	<table class="userStartForm">
		<tr>
			<th class="title"><center>Access Needed</h4></center></th>
			<th class="title"><center></h4></center></th>
		</tr>
		<tr>
			<?php if ($resume) { if (isset($_POST['fileserver'])) { $checked = "checked value=1"; } else  { $checked = ""; }  } else { $checked = ""; }   ?>
			<td><label><input type="checkbox" name="fileserver" <?php echo $checked; ?> tabindex = 230 /> File server</label></td>
			<?php if ($resume) { if (isset($_POST['vpn'])) { $checked = "checked value=1"; } else  { $checked = ""; }  }  else { $checked = ""; }   ?>
			<td><label><input type="checkbox" name="vpn" <?php echo $checked; ?> tabindex = 260 /> VPN</label></td>
		</tr>
		<tr>	
			<?php if ($resume) { if (isset($_POST['maconomy'])) { $checked = "checked value=1"; } else  { $checked = ""; }  }  else { $checked = ""; }   ?>
			<td><label><input type="checkbox" name="maconomy" class="maconomy" <?php echo $checked; ?> tabindex = 260 /> Maconomy</label></td>		
		</tr>
	</table>

	
        <p>&nbsp;</p>
	<table class="userStartForm maconomyField">
		<tr>
			<th colspan="4" class="title"><center>Maconomy Access</center></th>
		</tr>	
			<!-- FINANCE FIELDS -->
		<tr>
					<?php if ($resume) { if (isset($_POST['timesheets'])) { $checked = "checked value=1"; } else  { $checked = ""; }  } else { $checked = ""; }   ?>
			<td><label><input type="checkbox" name="timesheets" <?php echo $checked; ?> tabindex = 280 /> Time Sheets</label></td>

			<?php if ($resume) { if (isset($_POST['timesheetsblocking'])) { $checked = "checked value=1"; } else  { $checked = ""; }  }  else { $checked = ""; }   ?>
			<td><label><input type="checkbox" name="timesheetsblocking" <?php echo $checked; ?> tabindex = 300 /> Time Sheet Blocking</label></td>

				<?php if ($resume) { if (isset($_POST['jobcost'])) { $checked = "checked value=1"; } else  { $checked = ""; }  } else { $checked = ""; }   ?>
			<td><label><input type="checkbox" name="jobcost" <?php echo $checked; ?> tabindex = 340 /> Job Cost</label></td>
				<?php if ($resume) { if (isset($_POST['accountspayable'])) { $checked = "checked value=1"; } else  { $checked = ""; }  } else { $checked = ""; }   ?>
			<td><label><input type="checkbox" name="accountspayable" <?php echo $checked; ?> tabindex = 350 /> Account Payable</label></td>
		</tr>
		
		<tr>
				<?php if ($resume) { if (isset($_POST['purchaseorders'])) { $checked = "checked value=1"; } else  { $checked = ""; }  } else { $checked = ""; }   ?>
			<td><label><input type="checkbox" name="purchaseorders" <?php echo $checked; ?> tabindex = 380 /> Purchase Orders</label></td>
				<?php if ($resume) { if (isset($_POST['invoicing'])) { $checked = "checked value=1"; } else  { $checked = ""; }  }else { $checked = ""; }   ?>
			<td><label><input type="checkbox" name="invoicing" <?php echo $checked; ?> tabindex = 390 /> Invoicing</label></td>
				<?php if ($resume) { if (isset($_POST['generalledger'])) { $checked = "checked value=1"; } else  { $checked = ""; }  } else { $checked = ""; }   ?>
			<td><label><input type="checkbox" name="generalledger" <?php echo $checked; ?> tabindex = 400 /> General Ledger</label></td>
				<?php if ($resume) { if (isset($_POST['hr'])) { $checked = "checked value=1"; } else  { $checked = ""; }  } else { $checked = ""; }   ?>
			<td><label><input type="checkbox" name="hr"  <?php echo $checked; ?> tabindex = 410 /> HR</label></td>
		</tr>
		<tr>	
			<?php if ($resume) { if (isset($_POST['payrollAccess'])) { $checked = "checked value=1"; } else  { $checked = ""; }  }  else { $checked = ""; }   ?>
			<td><label><input type="checkbox" name="payrollAccess"  <?php echo $checked; ?> tabindex = 410 /> Payroll</label></td>
							<?php if ($resume) { if (isset($_POST['accountsreceivable'])) { $checked = "checked value=1"; } else  { $checked = ""; }  } else { $checked = ""; }   ?>
			<td><label><input type="checkbox" name="accountsreceivable" <?php echo $checked; ?> tabindex = 360>Accounts Receivable</label></td>
				<?php if ($resume) { if (isset($_POST['fixedassets'])) { $checked = "checked value=1"; } else  { $checked = ""; }  } else { $checked = ""; }   ?>
			<td><label><input type="checkbox" name="fixedassets" <?php echo $checked; ?> tabindex = 370 /> Fixed Assets</label></td>

		</tr>
	</table>

        <p>&nbsp;</p>
        <?php
        $depField = $_POST['department'];
        if ($depField == "Operations") { ?>
        <table class="userStartForm">
                <tr>
                        <th colspan="2" class="title"><center>Admin IT Access</center></th>
                </tr>   
                
                        <!-- IT FIELDS -->
                <tr>
                                <?php if ($resume) { if (isset($_POST['computeradmin'])) { $checked = "checked value=1"; } else  { $checked = ""; }  } else { $checked = ""; }   ?>
                        <td><label><input type="checkbox" name="computeradmin" <?php echo $checked; ?> tabindex = 320 /> Computer Admin</label></td>
                                <?php if ($resume) { if (isset($_POST['networkadmin'])) { $checked = "checked value=1"; } else  { $checked = ""; }  } else { $checked = ""; }   ?>
                        <td><label><input type="checkbox" name="networkadmin" <?php echo $checked; ?> tabindex = 330 /> Network Admin</label></td>
                </tr>
        </table>
        <?php } ?>
        
	<br />
	
		<?php if ($resume) { ?>
	
		<table class="userStartForm">
		<tr>
			<th colspan="4" class="title"><center>Internal People Portal Workflow Note</center></th>
		</tr>	
			
		<tr>
			<td>
				<textarea rows=10 cols=100 name=\"note\"></textarea>
			</td>
		</tr>
	</table>
		<?php } ?>
	<?php echo $_POST['email']; ?>
	<input type="hidden" name="transportEntry" value="<?php echo $_POST['transportEntry']; ?>">
	<input type="hidden" name="startDate" value="<?php echo $_POST['startDate']; ?>">
	<input type="hidden" name="endDate" value="<?php echo $_POST['endDate']; ?>">
	<input type="hidden" name="language" value="<?php echo $_POST['language']; ?>">
	<input type="hidden" name="timeRegime" value="<?php echo $_POST['timeRegime']; ?>">
	<input type="hidden" name="essApprovers" value="<?php echo $_POST['essApprovers']; ?>">
	<input type="hidden" name="emergencyContact" value="<?php echo $_POST['emergencyContact']; ?>">
	<input type="hidden" name="birthdate" value="<?php echo $_POST['birthdate']; ?>">
	<input type="hidden" name="address" value="<?php echo $_POST['address']; ?>">
	<input type="hidden" name="loginAD" value="<?php echo $_POST['loginAD']; ?>">
	<input type="hidden" name="createEmail" value="<?php echo $_POST['createEmail']; ?>">
	<input type="hidden" name="lastnameRF" value="<?php echo $_POST['lastnameRF']; ?>">
	<input type="hidden" name="firstnameRF" value="<?php echo $_POST['firstnameRF']; ?>">
	<input type="hidden" name="lastnamePF" value="<?php echo $_POST['lastnamePF']; ?>">
	<input type="hidden" name="firstnamePF" value="<?php echo $_POST['firstnamePF']; ?>">
	<input type="hidden" name="email" value="<?php echo $_POST['email']; ?>">
	<input type="hidden" name="type" value="<?php echo $_POST['type']; ?>">
	<input type="hidden" name="label" value="<?php echo $_POST['label']; ?>">
	<input type="hidden" name="payroll" value="<?php echo $_POST['payroll']; ?>">
	<input type="hidden" name="department" value="<?php echo $_POST['department']; ?>">
	<input type="hidden" name="function" value="<?php echo $_POST['function']; ?>">
	<input type="hidden" name="manager" value="<?php echo $_POST['manager']; ?>">
	<?php if (isset($_POST['businessCardNeeded'])) { ?><input type="hidden" name="businessCardNeeded" value="<?php echo $_POST['businessCardNeeded']; ?>"><?php } else { ?><input type="hidden" name="businessCardNeeded" value="0"> <?php } ?>

	<?php
		
	
		
		
		$holidayAppNbr = 0;
		// Saving HOLIDAY APPROVERS
		if(!empty($_POST['holidayApp'])) 
		{
			if (is_array($_POST['holidayApp']))
			{
				foreach($_POST['holidayApp'] as $checkHA) 
				{
				?>
					<input type="hidden" name="holidayAppSaved[]" value="<?php echo $checkHA; ?>">
				<?php
					$holidayAppNbr++;
				}	
			}
		}
?>	
		<input type="hidden" name="holidayAppNbr" value="<?php echo $holidayAppNbr; ?>">
		
		
		
		
	<?php if (!$resume) { ?>
			<?php  if (($_POST['createEmail'] != 0 && (!isset($_GET["resume"])) ) || ($_POST['createEmail'] != 0) ) {  ?> 
			<?php } ?>
			<p><br /><button class='btn usf btn-info' ><img src='img/next24x24Wht.png' /> Request overview</button></p>
	</form>
	<?php } ?>

<!--
**********************************************************************************************************************************
**********************************************************************************************************************************
**********************************************************************************************************************************
**********************************************************************************************************************************
**********************************************************************************************************************************
-->

 <?php $requestor = $currentUser['primaryEmail']; ?>
	
<?php 

	} // end if ($_GET['stage'] == 2)
	 if ($resume) { ?>
	<p><br /><button class='btn usf btn-success' ><img src='img/okWht.png' /> Send</button></p>
	</form>
	<?php }
	
} // end iif isset $_GET['stage']
?>
</form>

</center>
