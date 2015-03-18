<center>
<?php
// Groups NA & JR
	$queryAliase = mysql_query("SELECT * FROM emailAliases AS ea INNER JOIN labels AS lab ON ea.idLab = lab.idLab ORDER BY lab.labelName ASC") or die(mysql_error());

if (!isset($_GET['idAliase']))
{
?>
<h1><img src="img/setupBig.png" /> Email Labels and Domains Manager</h1>

	<table class="tableEmp">
	<tr>
			<th><center>Label</center></th>
			<th><center>Domain</center></th>
	</tr>
			<?php while($Aliase=mysql_fetch_array($queryAliase)) { ?>				
		<tr class="hover">
			<td><span align="right"><a href="index.php?p=emailAliasesManagor&idAliase=<?php echo $Aliase['idAliase']; ?>" ><?php echo $Aliase['labelName']; ?></a></span></td>
			<td><span align="right"><a href="index.php?p=emailAliasesManagor&idAliase=<?php echo $Aliase['idAliase']; ?>" ><?php echo $Aliase['email']; ?></a></span></td>
		</tr>
		<?php } ?>
	</table>

</center>
<?php
}
else 
{ //while($group=mysql_fetch_array($queryGroups)) { 
	$idAliase = $_GET['idAliase'];
	$queryAliaseUp = mysql_query("SELECT * FROM emailAliases WHERE idAliase = $idAliase") or die(mysql_error());
	while($Aliase=mysql_fetch_array($queryAliaseUp)) {
	
?>				


<form method="POST" action='index.php?p=emailAliasesManagor&idFunc=<?php echo $idAliase; ?>&up' name="fm">
<h3><?php echo $Aliase['email']; ?></h3>
	<table class="table table-condensed">
		<tr>
				<th><center>emailAliases</center></th>
		</tr>
		<tr>
			<td>
			</td>
		</tr>
	</table>
<p><br /><button class='btn btn-success' ><img src='img/okWht.png' /> Update</button></p>
</form>				
<p><br /><br /><a class='btn btn-info' href="index.php?p=emailAliasesManagor" >Back</a></p>
<?php } ?>
<?php } ?>

<?php
 if(isset($_GET["up"]))
{/**
	$idAliase = $_GET['idFunc'];
	mysql_query("DELETE FROM functionGroups WHERE idFunc = $idAliase"); // delete all groups association
	if(!empty($_POST['group'])) 
	{
		foreach($_POST['group'] as $check) 
		{
            //echo $check;
			//Update record in database
			//$result = mysql_query("update functions SET r = '" . $_POST["r"] . "', w = " . $_POST["w"] ." WHERE idFunc = " . $_POST["idE"] . ";")
			
			mysql_query("INSERT INTO functionGroups (idFunc, idGroup) VALUES($idAliase, $check)") or die (mysql_error()); // and recreate them here
		}
			echo "<h2 class='text-success'>Functions correctly updated</h2>";
			echo "<meta http-equiv='refresh' content='0;url=index.php?p=emailAliasesManagor'>";
	}**/
}
?>




