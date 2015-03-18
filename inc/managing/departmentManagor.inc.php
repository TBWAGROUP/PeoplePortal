<center>
<?php
// Groups NA & JR
	$queryDep = mysql_query("SELECT * FROM departments WHERE hidden=0 ORDER BY nameDepartment ASC ") or die(mysql_error());

if (!isset($_GET['manage']))
{
?>
<h1><img src="img/setupBig.png" /> Departments Manager</h1>
	<p><a class="btn btn-default btn-sm" href="index.php?p=departmentManagor&manage&add">Add a department</a></p>

	<table class="tableEmp">
	<tr>
			<th><center>Departments</center></th>
	</tr>
			<?php while($dep=mysql_fetch_array($queryDep)) { ?>				
		<tr class="hover">
			<td><span align="right"><a href="index.php?p=departmentManagor&idDep=<?php echo $dep['idDep']; ?>&manage" ><?php echo $dep['nameDepartment']; ?></a></span></td>
		</tr>
		<?php } ?>
	</table>

</center>
<?php
}
else  if (isset($_GET['idDep']))
{ //while($group=mysql_fetch_array($queryGroups)) { 
	$idDep = $_GET['idDep'];
	$queryDepUp = mysql_query("SELECT * FROM departments WHERE idDep = $idDep AND hidden=0") or die(mysql_error());
	while($dep=mysql_fetch_array($queryDepUp)) {
	
?>


<form method="POST" action='index.php?p=departmentManagor&idDep=<?php echo $idDep; ?>&up&manage' name="fm">
<h3><?php echo $dep['nameDepartment']; ?></h3>
	<table class="table table-condensed"  style="width:500px;">
		<tr>
				<th><center>Department name</center></th>
			<td>
				<input type"text" name="nameDepartment" value="<?php echo $dep['nameDepartment']; ?>" size="50"/>
			</td>
		</tr>
	</table>
<p><br /><button class='btn btn-success' ><img src='img/okWht.png' /> Update</button></p>
</form>				
<p><br /><br /><a class='btn btn-info' href="index.php?p=departmentManagor" >Back</a></p>
<?php } ?>
<?php } ?>


<?php if (isset($_GET['add'])) { ?>
<form method="POST" action='index.php?p=departmentManagor&addThis&manage' name="fm">
<h3>New department</h3>
	<table class="table table-condensed"  style="width:500px;">
		<tr>
				<th><center>Department name</center></th>
			<td>
				<input type"text" name="nameDepartment" size="50"/>
			</td>
		</tr>
	</table>
<p><br /><button class='btn btn-success' ><img src='img/okWht.png' /> Add</button></p>
</form>				
<p><br /><br /><a class='btn btn-info' href="index.php?p=departmentManagor" >Back</a></p>
<?php } ?>



<?php
 if(isset($_GET["up"]))
{
	$idLab = $_GET['idDep'];
	
            //echo $check;
			//Update record in database
			$result = mysql_query("UPDATE departments SET nameDepartment = \"$_POST[nameDepartment]\" WHERE idDep = \"$idDep\" ;");

			echo "<h2 class='text-success'>Department correctly updated</h2>";
			echo "<meta http-equiv='refresh' content='0;url=index.php?p=departmentManagor'>";
	
}
 if(isset($_GET["addThis"]))
{
	
            //echo $check;
			//Update record in database
			$result = mysql_query("INSERT INTO departments (nameDepartment) VALUES (\"$_POST[nameDepartment]\") ;");

			echo "<h2 class='text-success'>Department correctly added</h2>";
			echo "<meta http-equiv='refresh' content='0;url=index.php?p=departmentManagor'>";
	
}
?>





