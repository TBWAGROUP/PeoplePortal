<center>
<?php
// Groups NA & JR
	$queryLab = mysql_query("SELECT * FROM labels  WHERE level = 0 ORDER BY companyCode ASC") or die(mysql_error());

if (!isset($_GET['manage']))
{
?>
<h1><img src="img/setupBig.png" /> Companies Manager</h1>
	<p><a class="btn btn-default btn-sm" href="index.php?p=labelManagor&add&manage">Add a company</a></p>
<hr />
	<table class="tableEmp" width="40%">
	<tr>
			<th><center>Companies (OU)</center></th>
	</tr>
			<?php while($lab=mysql_fetch_array($queryLab)) { 
				$queryParentLab = mysql_query("SELECT * FROM labels  WHERE hidden=0 AND level = \"$lab[idLab]\" ORDER BY companyCode ASC") or die(mysql_error());  ?>
		<tr class="hover">
			<td align="right">
				<ul>
					<li><?php if($lab['hidden']) { echo "<i>"; } ?><a href="index.php?p=labelManagor&idLab=<?php echo $lab['idLab']; ?>&manage" ><?php echo $lab['companyCode']; ?> <?php echo $lab['labelName']; ?></a><?php if($lab['hidden']) { echo "</i>"; } ?></li>
						<ul>
						<?php while ($parentLab = mysql_fetch_array($queryParentLab)) { 
							$queryParentSubLab = mysql_query("SELECT * FROM labels  WHERE hidden=0 AND level = \"$parentLab[idLab]\" ORDER BY companyCode ASC") or die(mysql_error()); ?>
							<li><a href="index.php?p=labelManagor&idLab=<?php echo $parentLab['idLab']; ?>&manage"><?php echo $parentLab['companyCode']; ?> <?php echo $parentLab['labelName']; ?></a></li>
							<ul><?php while ($parentSubLab = mysql_fetch_array($queryParentSubLab)) { ?>
									<li><a href="index.php?p=labelManagor&idLab=<?php echo $parentSubLab['idLab']; ?>&manage"><?php echo $parentSubLab['companyCode']; ?> <?php echo $parentSubLab['labelName']; ?></a></li>
								<?php } ?>
							</ul>
							<?php } ?>
						</ul>
				</ul>
			</td>
		</tr>
		<?php } ?>
	</table>

</center>
<?php
}
else if (isset($_GET['idLab']))
{ //while($group=mysql_fetch_array($queryGroups)) { 
	$idLab = $_GET['idLab'];
	$queryLabUp = mysql_query("SELECT * FROM labels WHERE idLab = $idLab") or die(mysql_error());
	while($lab=mysql_fetch_array($queryLabUp)) {
	
?>				


<form method="POST" action='index.php?p=labelManagor&idLab=<?php echo $idLab; ?>&up' name="fm">
<h3><?php echo $lab['labelName']; ?></h3>
	<table class="table table-condensed" style="width:300px;">
	<tr>
			<th>Company Code</th>
			<td><input type="text" name="companyCode" value="<?php echo $lab['companyCode']; ?>"  /></td>
	</tr>
		<tr class="hover">
			<th>Company name</th>
			<td><input type="text" name="labelName" value="<?php echo $lab['labelName']; ?>" size="50"/></td>
		</tr>
		<tr>
				<th>Parent Company</th>
				<td>
					<select name="level">
						<option value="<?php echo $lab['level']; ?>">Keep</option>
						<option value="0">None</option>
						<?php $queryLabAll = mysql_query("SELECT * FROM labels  WHERE hidden=0 ORDER BY labelName ASC") or die(mysql_error());
								while($labList=mysql_fetch_array($queryLabAll)) { ?>				
								<option value="<?php echo $labList['idLab']; ?>"><?php echo $labList['labelName']; ?></option>
						<?php } ?>
					</select>
					<label><input type="checkbox" name="subOu"> Add as a sub-OU of <?php echo $lab['labelName']; ?></label>
				</td>
			</tr>
			<tr>
				<th>Hidden</th>
				<?php if ($lab['hidden'] == 1) { $checked = "checked";} else { $checked = ""; } ?>
				<td><input type="checkbox" name="hidden" <?php echo $checked; ?>></td>
			</tr>
	</table>
<p><br /><button class='btn btn-success' ><img src='img/okWht.png' /> Update</button></p>
</form>				
<p><br /><a class='btn btn-info' href="index.php?p=labelManagor" >Back</a></p>
<p><br /><a href="index.php?p=labelManagor&idLab=<?php echo $idLab; ?>&manage&delete">Delete</a></p>

<?php } ?>
<?php } ?>



<?php if (isset($_GET['add'])) { ?>
	<form method="POST" action='index.php?p=labelManagor&manage&addThis' name="fm">
	<h3>Add a new company</h3>
		<table class="table table-condensed" style="width:300px;">
		<tr>
				<th><center>Company Code</center></th>
				<td><input type="text" name="companyCode" value="0303"  /></td>
		</tr>
			<tr class="hover">
				<th><center>Company name</center></th>
				<td><input type="text" name="labelName" value=""  size="50"/></td>
			</tr>
			<tr>
				<th>Parent Company</th>
				<td>
					<select name="level">
							<option value="0">None</option>
					<?php $queryLabAll = mysql_query("SELECT * FROM labels  WHERE hidden=0 ORDER BY labelName ASC") or die(mysql_error());
							while($lab=mysql_fetch_array($queryLabAll)) { ?>				
							<option value="<?php echo $lab['idLab']; ?>"><?php echo $lab['labelName']; ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<th>Hidden</th>
				<td><input type="checkbox" name="hidden"></td>
			</tr>

		</table>
	<p><br /><button class='btn btn-success' ><img src='img/okWht.png' /> Add</button></p>
	</form>				
	<p><a class='btn btn-info' href="index.php?p=labelManagor" >Back</a></p>
<?php } ?>



<?php
	if (!empty($_POST['hidden'])) { $hidden = 1; } else { $hidden = 0; }
 if(isset($_GET["up"]))
{
	$idLab = $_GET['idLab'];
            //echo $check;
			if (empty($_POST['subOu'])) { 
				$level = $_POST['level']; 
				//Update record in database
				$result = mysql_query("UPDATE labels SET labelName = \"$_POST[labelName]\", companyCode = \"$_POST[companyCode]\", hidden = \"$hidden\", level = $level  WHERE idLab = \"$idLab\" ;");
			} else { 
				$level = $_GET['idLab']; 
				$result = mysql_query("INSERT INTO labels (labelName,  companyCode, level, hidden) VALUES (\"$_POST[labelName]\", \"$_POST[companyCode]\", $level, $hidden ) ;");
			}
			echo "<h2 class='text-success'>Company correctly updated</h2>";
			echo "<meta http-equiv='refresh' content='0;url=index.php?p=labelManagor'>";
}
 if(isset($_GET["addThis"]))
{
	//echo $check;
	//Update record in database
	$result = mysql_query("INSERT INTO labels (labelName,  companyCode, level, hidden) VALUES (\"$_POST[labelName]\", \"$_POST[companyCode]\", \"$_POST[level]\" ,$hidden) ;");

	echo "<h2 class='text-success'>Company correctly added</h2>";
	echo "<meta http-equiv='refresh' content='0;url=index.php?p=labelManagor'>";
}

 if(isset($_GET["delete"]))
{
	$idLab = $_GET['idLab'];
	echo "Are you sure? <a href=\"index.php?p=labelManagor&idLab=$idLab&manage&delete&deleteThis\">Yes</a>";
	//echo $check;
	if (isset($_GET['deleteThis'])) 
	{ 
		$result = mysql_query("DELETE FROM labels WHERE idLab = \"$idLab\" ;");
		echo "<h4 class='text-success'>Company correctly deleted</h4>";
		echo "<meta http-equiv='refresh' content='0;url=index.php?p=labelManagor'>";
	}
}
?>
