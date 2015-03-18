<!--
This script shows tools only for PP admin and PP HR people

Included by:
index.php (case ?p=tools)

Hrefs pointing here:

Requires:

Includes:

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
</script>

<span class="syncMess">
	<p align="center" class="text-default">
		<br/><strong><img src="img/ajax-loader.gif"/> Synchronisation... This process can take a while...</strong></p>
</span>

<h1>Tools</h1>
<hr/>
<?php if (memberOf($pp_admin)) { ?>
	<h3><img src="img/adtools.png"/> People Portal tools</h3>
	<p><a class="btn btn-default" href="index.php?p=usersManager"><img src="img/userS.png"/> Users Manager</a></p>
	<p><a class="btn btn-default" href="index.php?p=groupsManager"><img src="img/groupS.png"/> Groups Manager</a></p>
	<hr/>
<?php } ?>

<?php if ((memberOf($pp_admin)) || (memberOf($pp_hr))) { ?>
	<h3><img src="img/setup.png"/> Managing tools</h3>
	<p>
		<a class="btn btn-default" href="index.php?p=fileServerManagor"><img src="img/securityAccess.png"/> File Server Manager</a>
	</p>
	<p><a class="btn btn-default" href="index.php?p=labelManagor"><img src="img/labelS.png"/> Companies Manager</a></p>
	<p><a class="btn btn-default" href="index.php?p=departmentManagor"><img src="img/depS.png"/> Departments Manager</a>
	</p>
	<p>
		<a class="btn btn-default" href="index.php?p=functionsManagor&list"><img src="img/function.png"/> Functions Manager</a>
	</p>
	<p>
		<a class="btn btn-default" href="index.php?p=contractsManagor"><img src="img/contract.png"/> Contracts Manager</a>
	</p>
	<p><a class="btn btn-default" href="index.php?p=labelEmailDomains"><img src="img/emailS.png"/> Email Domains Manager</a>
	</p>
<?php } ?>


<?php if (memberOf($pp_admin)) { ?>
	<hr/>
	<h3><img src="img/sync.png"/> Global pull from AD</h3><!-- AD to DB  -->
	<span class="syncBtn">
		<p>
			<a class="btn btn-default" href="index.php?p=globalSync" title="Global Synchronisation"><img src="img/all.png"/> Global pull from AD</a>
		</p>
	</span>

	<hr/>
<?php } ?>

<!--
 <h3>Google Sheet to DB Synchronisation tools</h3>
<span class="syncBtn">
		 <p><a class="btn btn-default sync" href="index.php?p=googleSyncEmp" >Employees List sync</a></p>
</span>
-->
