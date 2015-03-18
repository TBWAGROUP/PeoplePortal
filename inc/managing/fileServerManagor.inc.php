<center>
<?php
// Groups NA & JR
	$queryFS = mysql_query("SELECT * FROM fileServers ORDER BY serverName ASC ") or die(mysql_error());

if (!isset($_GET['manage']))
{
?>
<h1><img src="img/setupBig.png" /> File Server Manager</h1>
	<p><a class="btn btn-default btn-sm" href="index.php?p=fileServerManagor&manage&add">Add a server</a></p>

	<table class="tableEmp">
	<tr>
			<th><center>Server</center></th>
	</tr>
			<?php while($serv=mysql_fetch_array($queryFS)) { ?>				
		<tr class="hover">
			<td><span align="right"><a href="index.php?p=fileServerManagor&idServ=<?php echo $serv['idServ']; ?>&manage" ><?php echo $serv['serverName']; ?></a></span></td>
		</tr>
		<?php } ?>
	</table>

</center>
<?php
}
else  if (isset($_GET['idServ']))
{ //while($group=mysql_fetch_array($queryGroups)) { 
	$idServ = $_GET['idServ'];
	$queryFSUp = mysql_query("SELECT * FROM fileServers WHERE idServ = $idServ") or die(mysql_error());
	while($serv=mysql_fetch_array($queryFSUp)) {
	
?>


<form method="POST" action='index.php?p=fileServerManagor&idServ=<?php echo $idServ; ?>&up&manage' name="fm">
<h3><?php echo $serv['serverName']; ?></h3>
	<table class="table table-condensed"  style="width:500px;">
		<tr>
				<th><center>Server name</center></th>
			<td>
				<input type"text" name="serverName" value="<?php echo $serv['serverName']; ?>" size="50"/>
			</td>
		</tr>
	</table>
<p><br /><button class='btn btn-success' ><img src='img/okWht.png' /> Update</button></p>
</form>				
<p><br /><br /><a class='btn btn-info' href="index.php?p=fileServerManagor" >Back</a></p>
<p><br /><a href="index.php?p=fileServerManagor&idServ=<?php echo $idServ; ?>&manage&delete">Delete</a></p>

<?php } ?>
<?php } ?>


<?php if (isset($_GET['add'])) { ?>
<form method="POST" action='index.php?p=fileServerManagor&addThis&manage' name="fm">
<h3>New server</h3>
	<table class="table table-condensed"  style="width:500px;">
		<tr>
				<th><center>Server name</center></th>
			<td>
				<input type"text" name="serverName" size="50"/>
			</td>
		</tr>
	</table>
<p><br /><button class='btn btn-success' ><img src='img/okWht.png' /> Add</button></p>
</form>				
<p><br /><br /><a class='btn btn-info' href="index.php?p=fileServerManagor" >Back</a></p>
<?php } ?>



<?php
 if(isset($_GET["up"]))
{
	$idLab = $_GET['idServ'];
	
            //echo $check;
			//Update record in database
			$result = mysql_query("update fileserversSET serverName = \"$_POST[serverName]\" WHERE idServ = \"$idServ\" ;");

			echo "<h2 class='text-success'>Server correctly updated</h2>";
			echo "<meta http-equiv='refresh' content='0;url=index.php?p=fileServerManagor'>";
	
}
 if(isset($_GET["addThis"]))
{
	
            //echo $check;
			//Update record in database
			$result = mysql_query("INSERT INTO fileServers (serverName) VALUES (\"$_POST[serverName]\") ;");

			echo "<h2 class='text-success'>Server correctly added</h2>";
			echo "<meta http-equiv='refresh' content='0;url=index.php?p=fileServerManagor'>";
	
}
 if(isset($_GET["delete"]))
{
	$idLab = $_GET['idServ'];
	echo "Are you sure? <a href=\"index.php?p=fileServerManagor&idServ=$idServ&manage&delete&deleteThis\">Yes</a>";
	//echo $check;
	if (isset($_GET['deleteThis'])) 
	{ 
		$result = mysql_query("DELETE FROM contFileServers WHERE idServ = \"$idServ\" ;");
		$result = mysql_query("DELETE FROM fileServers WHERE idServ = \"$idServ\" ;");
		echo "<h4 class='text-success'>Server correctly deleted</h4>";
		echo "<meta http-equiv='refresh' content='0;url=index.php?p=fileServerManagor'>";
	}
}
?>





