<center>
<?php
// Groups NA & JR
	$querySec = mysql_query("SELECT * FROM securityAccess ORDER BY securityName ASC") or die(mysql_error());

if (!isset($_GET['idE']))
{
?>
<h1><img src="img/setupBig.png" /> Security Access</h1>

	<table class="table table-condensed">
		<tr>
				<th>Fields</th>
				<th>Description</th>
		</tr>
			<?php while($sec=mysql_fetch_array($querySec)) {
				$queryEmpGroups = mysql_query("SELECT * FROM securityAccess") or die(mysql_error());
				$nbrGroup = mysql_num_rows($queryEmpGroups); ?>
				
		<tr class="hover">
			<td><img src="img/lockS.png" /> <span align="right"><a href="index.php?p=securityAccessManagor&idSec=<?php echo $sec['idSecAcc']; ?>" ><?php echo $sec['securityName']; ?></a></span></td>
			<td><span align="right"><?php echo $sec['securityDescription']; ?></span></td>
		</tr>
		<?php } ?>
	</table>

</center>
<?php
}
else if (!isset($_GET['up']))
{ //while($group=mysql_fetch_array($queryGroups)) { 
	$idE = $_GET['idE'];
	$queryGroupsUp = mysql_query("SELECT * FROM employees WHERE idE = $idE") or die(mysql_error());
	while($emp=mysql_fetch_array($queryGroupsUp)) {
	$queryGroups = mysql_query("SELECT * FROM groups ORDER BY groupName") or die(mysql_error());
	
?>				


<form method="POST" action='index.php?p=groupsManager&idE=<?php echo $idE; ?>&up' name="gm">
<h3><?php echo $emp['firstname']." ".$emp['lastname']; ?></h3>
	<table class="table table-condensed">
		<tr>
				<th><center>Groups</center></th>
		</tr>
		<tr>
			<td>
					<?php while($group=mysql_fetch_array($queryGroups)) { 
					// checkecd or not
					$queryEmpGroups = mysql_query("SELECT * FROM employeeGroup WHERE idE = $idE AND idGroup = $group[idGroup]") or die(mysql_error()); 
					$nbr = mysql_num_rows($queryEmpGroups);
					if ($nbr > 0)  { $checked = "checked"; } else { $checked = ""; }
					?>
					<label><input type="checkbox" name="group[]" value="<?php echo $group['idGroup']; ?>" <?php echo $checked; ?>> <?php echo $group['groupName']; ?></label>
					<?php } ?>
			</td>
		</tr>
	</table>
<p><br /><button class='btn btn-success' ><img src='img/okWht.png' /> Assign</button></p>
</form>				
<p><br /><br /><a class='btn btn-info' href="index.php?p=functionsManagor" >Back</a></p>
<?php } ?>
<?php } ?>

<?php
 if(isset($_GET["up"]))
{
	$idE = $_GET['idE'];
	mysql_query("DELETE FROM employeeGroup WHERE idE = $idE"); // delete all groups association
	if(!empty($_POST['group'])) 
	{
		foreach($_POST['group'] as $check) 
		{
            //echo $check;
			//Update record in database
			//$result = mysql_query("update functions SET r = '" . $_POST["r"] . "', w = " . $_POST["w"] ." WHERE idFunc = " . $_POST["idE"] . ";")
			
			mysql_query("INSERT INTO employeeGroup (idE, idGroup) VALUES($idE, $check)") or die (mysql_error()); // and recreate them here
		}
		echo "<h2 class='text-success'>User correctly updated</h2>";
		echo "<meta http-equiv='refresh' content='1;url=index.php?p=groupsManager'>";
	}
}
?>




