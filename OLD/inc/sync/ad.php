<script>
	$(document).ready(function(){
		$(".syncMess").hide();
		$(".sync").click(function()
		{
			$(".syncMess").show();
			$(".syncBtn").hide();
		});
	});
</script>
<h1><img src="img/sync.png" /> Global Synchronisation</h1>


<span class="syncMess">
	<p align="center" class="text-default"><br /><strong><img src="img/ajax-loader.gif" /> Synchronisation... This process can take a while...</strong></p></span>
<center>
</span>

<span class="syncBtn">
		<a class="btn btn-default sync" href="index.php?p=globalSync&ok">Start</a>
</span>
<p class="text-info"><br />This operation can take a while, depending on the network use.</p>
</center>
<p>
<?php
if (isset($_GET['ok']))
{
	echo "<hr><h3>Users... </h3>";
		include ($_SERVER['DOCUMENT_ROOT']."/inc/sync/syncUsersFromADtoDB.php");
	echo "<h3>Done.</h3> <hr>";
	
	echo "<h3>Groups...</h3> ";
		include ($_SERVER['DOCUMENT_ROOT']."/inc/sync/syncGroupsFromADtoDB.php");
	echo "<h3>Done. </h3>";
	
	echo "<h1 class='text-success'>Sync done</h1>";
}
?>
</p>