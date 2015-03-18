<center>
<?php
// Groups NA & JR
	$queryFunctions = mysql_query("SELECT * FROM functions WHERE hidden=0 ORDER BY functionName ASC") or die(mysql_error());

if (isset($_GET['list']))
{
?>
<h2><img src="img/setupBig.png" /> Functions Manager</h2>

	<p><a class="btn btn-default btn-sm" href="index.php?p=functionsManagor&add"><img src="img/function.png" /> Add a Function</a></p>
	<hr />
	<table class="tableEmp" style="font-size:12px;">
		<tr>
				<th><center>Functions</center></th>
				<th><center>Tags</center></th>
		</tr>
			<?php while($func=mysql_fetch_array($queryFunctions)) { ?>
				
		<tr class="hover">
			<td><span align="right"><a href="index.php?p=functionsManagor&idFunc=<?php echo $func['idFunc']; ?>&update" ><?php echo $func['functionName']; ?></a></span></td>
			<td>
					<?php echo $func['functionTags']; ?>
			</td>
		</tr>
		<?php } ?>
	</table>

</center>
<?php
}
else if (isset($_GET['update']))
{ 
	$idFunc = $_GET['idFunc'];
	$queryFunctionsUp = mysql_query("SELECT * FROM functions WHERE idFunc = $idFunc") or die(mysql_error());
	$func=mysql_fetch_array($queryFunctionsUp);
	?>

	<form method="POST" action='index.php?p=functionsManagor&idFunc=<?php echo $idFunc; ?>&update&up' name="fm">
	<h3><?php echo $func['functionName']; ?></h3>
		<table class="table table-condensed" style="width:300px;">
			<tr>
					<th>Title</th>
					<th>Tags (separated by " , ")</th>
			</tr>
			<tr>
				<td><input type="text" name="functionName" value="<?php echo $func["functionName"]; ?>"/></td>
				<td><input type="text" size="50"name="functionTags" value="<?php echo $func["functionTags"]; ?>"/></td>
			</tr>
		</table>
	<p><br /><button class='btn btn-success' ><img src='img/okWht.png' /> Apply</button></p>
	</form>
	<p><br /><br /><a class='btn btn-info' href="index.php?p=functionsManagor&list" >Back</a></p>

	<?php
	 if(isset($_GET["up"]))
	{
		$idFunc = $_GET['idFunc'];

		mysql_query("update functions SET functionName = \"$_POST[functionName]\", functionTags = \"$_POST[functionTags]\" WHERE idFunc = \"$idFunc\"")or die (mysql_error());
		echo "<meta http-equiv='refresh' content='0;url=index.php?p=functionsManagor&idFunc=$idFunc&update'></h4><img src='img/ajax-loader.gif' /> Updating changes";
	}
} 
else if (isset($_GET['add'])) { ?>

	<form method="POST" action='index.php?p=functionsManagor&add&addThis' name="fm">
	<h3>New function</h3>
		<table class="table table-condensed" style="width:300px;">
			<tr>
					<th>Title</th>
					<th>Tags (separated by " , ")</th>
			</tr>
			<tr>
				<td><input type="text" name="functionName" value=""/></td>
				<td><input type="text" size="50"name="functionTags" value=""/></td>
			</tr>
		</table>
	<p><br /><button class='btn btn-success' ><img src='img/okWht.png' /> Add</button></p>
	</form>	
	<p><br /><br /><a class='btn btn-info' href="index.php?p=functionsManagor&list" >Back</a></p>

	<?php
	 if(isset($_GET["addThis"]))
	{

		mysql_query("INSERT INTO functions (functionName, functionTags) VALUES (\"$_POST[functionName]\", \"$_POST[functionTags]\") ")or die (mysql_error());
		echo "<meta http-equiv='refresh' content='0;url=index.php?p=functionsManagor&list'></h4><img src='img/ajax-loader.gif' /> Inserting";
	}
}
?>
