<!--
This script Shows a delete button under the IT Tasks tab if a user's ppAccountstatus is 1 so PP Admin can delete his records.

Included by:
inc/emp.inc.php

Hrefs pointing here:

Requires:

Includes:

Form actions:

-->

<?php
	$queryEmp = mysql_query("SELECT * FROM employees WHERE idE = $idE") or die(mysql_error());
	$emp=mysql_fetch_array($queryEmp);
if ($emp['ppAccountStatut'] == 1)
{
?>
<script>
	$(document).ready(function(){
		$(".refMess").hide();
		{
			$(".refMess").hide();
		});
		$(".ref").click(function()
		{
			$(".refMess").show();
		});
	});
</script>

	<a class="btn btn-default btn-xs ref" ><img src='img/noOkS.png' /> Erase this user, all of his data and contracts from the database</a>
	
	<span class="refMess text-danger">Are you sure? <a class="btn btn-default btn-sm" href="#" title="Erase">YES</a></span>

<?php } ?>
