<!--
This script shows email domain and business card info for a user

Included by:
index.php (case ?p=empEmailDomain), through employeeProfile.inc.php

Hrefs pointing here:
index.php?p=emp

Requires:

Includes:

Form actions:
index.php?p=empEmailDomain

-->

<?php
if (isset($_GET['idE']))
{ 
	$idE = $_GET['idE'];
	$idCon = $_GET['idCon'];
	$upn = $_GET['upn'];
	$queryCont = mysql_query("
												SELECT * FROM contracts AS cont
													INNER JOIN employees AS emp ON cont.idE = emp.idE
												WHERE idCon = $idCon
											") or die (mysql_error() );

	$contract = mysql_fetch_array($queryCont);

	
	if (isset($_GET['up']))
	{
		mysql_query("UPDATE emailAliasesEmp SET emailAlEmpDelete = 1 WHERE idCon = $idCon ")or die(mysql_error());
		if (!empty($_POST['emailAliases']))
		{
			foreach($_POST['emailAliases'] as $check) 
			{
				$checkAliase = mysql_query ("SELECT * FROM emailAliasesEmp WHERE idCon = $idCon AND idAliase = $check") or die (mysql_error());
				if (mysql_num_rows($checkAliase) == 0)
				{
					mysql_query("INSERT INTO emailAliasesEmp (idCon, idAliase) VALUES($idCon, $check)") or die (mysql_error()); // and recreate them here
				}
				else
				{
					mysql_query("UPDATE emailAliasesEmp SET emailAlEmpDelete = 0 WHERE idCon = $idCon AND idAliase = $check;");
				}
			}
		}
		mysql_query("DELETE FROM emailAliasesEmp WHERE emailAlEmpDelete = 1 AND idCon = $idCon ")or die(mysql_error());
		
		
		
		
		echo "<meta http-equiv='refresh' content='0;url=index.php?p=empEmailDomain&idE=$idE&idCon=$idCon&upn=$upn&bc'>";

	}
	
	else	if (isset($_GET['updateBC']))
	{
		mysql_query ("UPDATE emailAliasesEmp SET businessCardNeeded = 0 WHERE idCon = $idCon ;") or die (mysql_error() );
		if (!empty($_POST['bc']))
		{
			foreach($_POST['bc'] as $check) 
			{
				mysql_query("UPDATE emailAliasesEmp SET businessCardNeeded = 1 WHERE idCon = $idCon AND idAliase = $check;");
			}
		}
		
		
		$checkAliaseBCN = mysql_query("SELECT * FROM emailAliasesEmp WHERE idCon= \"$idCon\" AND businessCardNeeded = 1")or die (mysql_error());
		if (mysql_num_rows($checkAliaseBCN) > 0)
		{
			mysql_query("UPDATE contracts SET businessCardNeeded = 1 WHERE idCon = $idCon");
		} else {mysql_query("UPDATE contracts SET businessCardNeeded = 0 WHERE idCon = $idCon"); }

		
		echo "<meta http-equiv='refresh' content='0;url=index.php?p=emp&idE=$idE&idCon=$idCon&upn=$upn'>";

	}
	else if (isset($_GET['bc']))
	{	
?>
<center>
<h2>Business Card</h2>
<h4><?php echo $contract['firstname']." ".$contract['lastname']; ?></h4>

<form action ="index.php?p=empEmailDomain&idE=<?php echo $idE; ?>&upn=<?php echo $upn; ?>&idCon=<?php echo $idCon; ?>&updateBC" method="POST">

<table class="userStartForm" width="700px">	
		<tr>
			<th class="title"><center>Select wich domain need a Business Card</center></th>
		</tr>	
			<tr class="emailHide">
				<td>
					<div class="scrollGroup" id="usfEmailDom">
					<?php	
							 $checkAliase2 = mysql_query("SELECT * FROM emailAliasesEmp AS eae INNER JOIN emailAliases AS ea ON eae.idAliase=ea.idAliase INNER JOIN labels AS lab ON ea.idLab = lab.idLab WHERE eae.idCon= $idCon")or die (mysql_error());
							 while ($listAliases = mysql_fetch_array($checkAliase2)) { 
								if ($listAliases['businessCardNeeded'] == 1) { $checked="checked"; } else { $checked=""; }  
							 ?>
							 		<label><input type="checkbox" id="bc" name="bc[]" value="<?php echo $listAliases['idAliase']; ?>" <?php echo $checked; ?> /> <?php echo $listAliases['email']; ?></label><br />
						<?php	} ?>
					</div>
				</td>
			</tr>
	</table>
		<p><br /><button class="btn btn-default">Apply</button></p>
	       <p><br /><a  href="index.php?p=emp&idE=<?php echo $idE; ?>&idCon=<?php echo $idCon; ?>&upn=<?php echo $upn; ?>&update&tab=0" class="btn btn-default btn-sm" title="Back"> Back</a></p>
</form>
</center>
<?php
	}
	else
	{	
?>
<center>
<h2>Email Domains</h2>
<h4><?php echo $contract['firstname']." ".$contract['lastname']; ?></h4>

<form action ="index.php?p=empEmailDomain&idE=<?php echo $idE; ?>&upn=<?php echo $upn; ?>&idCon=<?php echo $idCon; ?>&up" method="POST">

<table class="userStartForm" width="700px">	
		<tr>
			<th class="title"><center>Email domain</center></th>
		</tr>	
			<tr class="emailHide">
				<td>
					<div class="scrollGroup" id="usfEmailDom">
					<?php	
							 $checkAliase2 = mysql_query("SELECT * FROM emailAliasesEmp AS eae INNER JOIN emailAliases AS ea ON eae.idAliase=ea.idAliase INNER JOIN labels AS lab ON ea.idLab = lab.idLab WHERE eae.idCon= $idCon")or die (mysql_error());
							 while ($listAliases = mysql_fetch_array($checkAliase2)) { ?>
							 		<label><input type="checkbox" id="managerCB" name="emailAliases[]" value="<?php echo $listAliases['idAliase']; ?>" checked /> <?php echo $listAliases['email']; ?></label><br />
						<?php	 }
							$queryEmailAll = mysql_query("SELECT * FROM emailAliases AS em INNER JOIN labels AS lab ON em.idLab = lab.idLab ORDER BY email ASC") or die(mysql_error());
							 while($emailAll =mysql_fetch_array($queryEmailAll)) { 
							 ?>
								<label><input type="checkbox" id="managerCB" name="emailAliases[]" value="<?php echo $emailAll['idAliase']; ?>" /> <?php echo $emailAll['email']; ?></label><br />
							<?php } ?>
					</div>
				</td>
			</tr>
	</table>
		<p><br /><button class="btn btn-default">Apply</button></p>
	       <p><br /><a  href="index.php?p=emp&idE=<?php echo $idE; ?>&idCon=<?php echo $idCon; ?>&upn=<?php echo $upn; ?>&update&tab=0" class="btn btn-default btn-sm" title="Back"> Back</a></p>
</form>
</center>
<?php
	}
}
?>