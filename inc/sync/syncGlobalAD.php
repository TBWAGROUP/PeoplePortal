<!--
This script triggers global AD Users and AD Groups synchronisation, each triggering further subscripts

Included by:
index.php (case globalsync)

Hrefs pointing here:

Requires:

Includes:
/inc/sync/syncUsersFromADtoDB.php
/inc/sync/syncGroupsFromADtoDB.php

Form actions:

-->

<script>
	$(document).ready(function () {
		$(".syncMess").hide();
		$(".sync").click(function () {
			$(".syncMess").show();
			$(".syncBtn").hide();
		});
	});
</script><h1><img src="img/sync.png"/> Global pull from AD</h1>

<span class="syncMess">
	<p align="center" class="text-default">
		<br/><strong><img src="img/ajax-loader.gif"/> Synchronisation... This process can take a while...</strong></p>
</span>
<center>

	<span class="syncBtn">
		<a class="btn btn-default sync" href="index.php?p=globalSync&ok">Start</a>
	</span>

	<p class="text-info"><br/>This operation can take a while, typically up to 10-15 minutes, depending on the network use.
		<br/>Users in "Archives" and "USF Process" aren't concerned by this pull. All new users will be added in the "Frozen List".
	</p>
</center><p>
	<?php
		if (isset($_GET['ok'])) {
			$time_start_global = microtime(true);

			echo "<hr><h3>Users... </h3>";
			$time_start_users = microtime(true);
			include($_SERVER['DOCUMENT_ROOT'] . "/inc/sync/syncUsersFromADtoDB.php");
			// Display Script End time
			$time_end_users = microtime(true);
			$execution_time = round(($time_end_users - $time_start_users), 2);
			echo '<h3><b>Total Execution Time of syncing AD users:</b> '.$execution_time.' seconds.<br></h3>';
			echo "<h3>Done.</h3> <hr>";

			echo "<h3>Groups...</h3> ";
			$time_start_groups = microtime(true);
			include($_SERVER['DOCUMENT_ROOT'] . "/inc/sync/syncGroupsFromADtoDB.php");
			// Display Script End time
			$time_end_groups = microtime(true);
			$execution_time = round(($time_end_groups - $time_start_groups), 2);
			echo '<h3><b>Total Execution Time of syncing AD groups:</b> '.$execution_time.' seconds.<br></h3>';
			echo "<h3>Done. </h3> <hr>";


			echo "<h3 class='text-success'>Sync done</h3>";
			// Display Script End time
			$time_end_global = microtime(true);
			$execution_time = round(($time_end_global - $time_start_global), 2);
			echo '<h3><b>Total Execution Time:</b> '.$execution_time.' seconds.<br></h3>';


			echo "
		 <object type='application/x-shockwave-flash' data='widgets/dewplayer/dewplayer-mini.swf?mp3=widgets/dewplayer/mp3/ok.mp3&amp;autostart=1' width='0' height='0' id='dewplayer-mini'>
		<param name='wmode' value='transparent' /><param name='movie' value='widgets/dewplayer/dewplayer-mini.swf?mp3=widgets/dewplayer/mp3/ok.mp3&amp;autostart=1' /></object>
	";
		}
	?>
</p>