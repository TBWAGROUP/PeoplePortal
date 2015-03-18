<!--
This script prepares and shows Frozen users for PP Admin lists

Included by:
inc/employeesLists/pp_admin-employeeList.inc.php

Hrefs pointing here:

Requires:

Includes:
index.php?p=emp
jtable/usersStatutQueries.php?action=listFrozen

Form actions:

-->

<div id="PeopleTableContainerFrozen" style="width: 100%; overflow-x: scroll"></div>

	<script type="text/javascript">

		$(document).ready(function () 
		{
		    //Prepare jTable
			$('#PeopleTableContainerFrozen').jtable({
				title: 'Frozen Users, employee.ppAccountStatut = 2',
				paging: true,
				pageSize: 500,
				sorting: true,
				selecting : true,
				multiselect: true,
				defaultSorting: 'firstname ASC',
				actions: {
					listAction: 'jtable/usersStatutQueries.php?action=listFrozen',
					updateAction : 'jtable/usersStatutQueries.php?action=editStatut',
					deleteAction : 'jtable/usersStatutQueries.php?action=moveFreezeToUsers'
				},
					fields: 
					{
						idE: {
							key: true,
							create: false,
							edit: false,
							list: false
						},
						ppAccountStatut: {
							title: '',
							width: '1%',
							create: false,
							edit: false,
							display: function(data) {
								 return '<img src="img/ppAS-' + data.record.ppAccountStatut + '_S.png" />' ;
							},
						},	
						firstname: {
							title: 'Firstname',
								display: function(data) {
								 return '<a href="index.php?p=emp&idE=' + data.record.idE + '&idCon=' + data.record.idCon + '&upn=' + data.record.upn + '&objectsid=' + data.record.objectsid +'">' + data.record.firstname + '</a> '; }
						},
						lastname: {
							title: 'Lastname',
								display: function(data) {
								 return '<a href="index.php?p=emp&idE=' + data.record.idE + '&idCon=' + data.record.idCon + '&upn=' + data.record.upn + '&objectsid=' + data.record.objectsid +'">' + data.record.lastname +'</a> '; }
						},	
							language: {
							title: 'Lang',
							width: '1%',
						},
						functionName: {
							title: 'Function',
							width: '5%',
						},
						nameDepartment: {
							title: 'Department',
							width: '5%',
						},
						labelName: {
							title: 'Label',
							width: '5%',
						},	
						financePayroll: {
							title: 'Payroll',
							width: '5%',
						},
						internalPhone: {
							title: 'Int. Phone',
						},	
						mobile: {
							title: 'Mob. Phone',
						},	
						primaryEmail: {
							title: 'Email',
						},	
						nrPlaat: {
							title: 'Number Plate',
						},
						parking: {
							title: 'Parking',
						},	
						upn: {
							title: 'UPN',
							width:'5%',
						}
					},

			});

			//Load person list from server
			$('#PeopleTableContainerFrozen').jtable('load');

			
						
				$('#Button').button().click(function () 
				{
					var $selectedRows = $('#PeopleTableContainerFrozen').jtable('selectedRows');
					$('#PeopleTableContainerFrozen').jtable('deleteRows', $selectedRows);
				});
			
		});

	</script>

 	
	

	
<p> <br />
<button id="Button"><img src="img/userS.png" /> Activate all users</button>
	<span class="cleanBtn"> <a class="btn btn-default clean" href="#"><img src="img/trashS.png" /> Send all frozen users without contracts to the trash</a></span>	

	<span class="cleanMess text-danger">Are you sure? This operation is irreversible <a class="btn btn-default btn-sm" href="../../inc/ppTools/index.php?p=empList&cleanFrozen&tab=3" title="Send all frozen users to the trash">YES</a></span>

</p>

<script>
	$(document).ready(function(){
		$(".cleanFrozenToStandMess").hide();
		$(".cleanFrozenToStand").click(function()
		{
			$(".cleanFrozenToStandMess").show();
			$(".cleanFrozenToStandBtn").hide();
		});
	});
</script>

<p> <br />
	<span class="cleanFrozenToStandBtn"> <a class="btn btn-default cleanFrozenToStand" href="#"><img src="img/archiveS.png" /> Send all frozen users with contract in archive</a></span>	

	<span class="cleanFrozenToStandMess text-danger">Archive all. Are you sure? <a class="btn btn-default btn-sm" href="../../inc/ppTools/index.php?p=empList&moveArch&tab=3" title="Sure">YES</a></span>

</p>

<?php
	if (isset($_GET['moveArch']))
	{
		$movedToStand = 0;
		$notMovedToStand = 0;
		echo "<hr />";
		$queryClean = mysql_query("SELECT idE, firstname, lastname FROM employees WHERE ppAccountStatut = 2") or die (mysql_error());
		while ($clean=mysql_fetch_array($queryClean))
		{
			$idE = $clean['idE'];
			$queryCheckContracts = mysql_query("SELECT * FROM contracts WHERE idE = $idE AND (idDep !=18 AND idFunc != 74 AND idLab != 22)") or die (mysql_error());
			if (mysql_num_rows($queryCheckContracts) == 0)
			{
				$notMovedToStand++;
			}
			else
			{
				echo $clean['firstname']." ".$clean['lastname']." moved to standalone <br />";
				mysql_query("UPDATE employees SET ppAccountStatut = '4' WHERE idE = $idE;");		
				$movedToStand++;				
			}
		}
		echo $notMovedToStand ." users stayed in frozen (unknown contracts)<br />";
		echo $movedToStand ." users moved to archives";
	}

	if (isset($_GET['cleanFrozen']))
	{
		$movedToTrash = 0;
		$notMovedToTrash = 0;
		echo "<hr />";
		$queryClean = mysql_query("SELECT idE, firstname, lastname FROM employees WHERE ppAccountStatut = 2") or die (mysql_error());
		while ($clean=mysql_fetch_array($queryClean))
		{
			$idE = $clean['idE'];
			$queryCheckContracts = mysql_query("SELECT * FROM contracts WHERE idE = $idE AND validationStage != 4031 OR  (idDep !=18 AND idFunc != 74 AND idLab != 22)") or die (mysql_error());
			if (mysql_num_rows($queryCheckContracts) > 0)
			{
				$notMovedToTrash++;
			}
			else
			{
				echo $clean['firstname']." ".$clean['lastname']." moved to trash <br />";
				mysql_query("UPDATE employees SET ppAccountStatut = '1' WHERE idE = $idE;");		
				$movedToTrash++;				
			}
		}
		echo "<hr />".$notMovedToTrash ." users with approved contracts and not moved to trash<br />";
		echo $movedToTrash ." users moved to trash";
	}

?>

