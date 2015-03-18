<!--
This script prepares and shows Active users for PP Admin lists

Included by:
inc/employeesLists/pp_admin-employeeList.inc.php

Hrefs pointing here:

Requires:

Includes:
index.php?p=emp
jtable/usersStatutQueries.php?action=listTrash
jtable/usersStatutQueries.php?action=deleteFromTrash

Form actions:
index.php?p=empList

-->

<div id="PeopleTableContainerTrash" style="width: 100%; overflow-x: scroll"></div>

	<script type="text/javascript">

		$(document).ready(function () 
		{
		    //Prepare jTable
			$('#PeopleTableContainerTrash').jtable({
				title: 'Trashed Users, employee.ppAccountStatut = 1 OR 403',
				paging: true,
				pageSize: 500,
				sorting: true,
				selecting : true,
				multiselect: true,
				defaultSorting: 'firstname ASC',
				actions: {
					listAction: 'jtable/usersStatutQueries.php?action=listTrash',
					deleteAction : 'jtable/usersStatutQueries.php?action=deleteFromTrash'
				},
					fields: {
						idE: {
							key: true,
							create: false,
							edit: false,
							list: false
						},
						idCon: {
							key:false,
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
			$('#PeopleTableContainerTrash').jtable('load');
			
				$('#freezeAllButton').button().click(function () 
				{
					var $selectedRows = $('#PeopleTableContainerTrash').jtable('selectedRows');
					$('#PeopleTableContainerTrash').jtable('deleteRows', $selectedRows);
				});
		});

	</script>
	
	
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
	
	
<p> <br />
	<span class="cleanBtn"> <a class="btn btn-default clean" href="#" title="Delete all the users on the trash"><img src="img/trashS.png" /> Clean the trash</a></span>
	<span class="cleanMess text-danger">Are you sure? This operation is irreversible <a class="btn btn-default btn-sm" href="index.php?p=empList&clean&tab=5" title="Delete all the users on the trash">YES</a></span>
</p>

<?php
	if (isset($_GET['clean']))
	{
		echo "<hr />";
		echo "<form action='index.php?p=empList&clean&tab=5&confirm' method='POST' >";
		$queryClean = mysql_query("SELECT idE, firstname, lastname FROM employees WHERE ppAccountStatut = 1 OR ppAccountStatut = 403") or die (mysql_error());
		while ($clean=mysql_fetch_array($queryClean))
		{
			$idE = $clean['idE'];
			$queryCheckContracts = mysql_query("SELECT * FROM contracts WHERE idE = $idE AND validationStage != 4031") or die (mysql_error());
			if (mysql_num_rows($queryCheckContracts) > 0)
			{
				echo "<h4 class='text-danger'>WARNING, contracts found for ".$clean['firstname']." ".$clean['lastname']." !</h4>
							<p><strong><label><input type='checkbox' name='delete[]' value=".$clean['idE']." checked/> Definitely remove ".$clean['firstname']." ".$clean['lastname']."</label></strong></p>
						";
					$removeBtn = TRUE;
			}
			else
			{
				mysql_query("DELETE FROM employeeGroup WHERE idE = $idE") or die (mysql_error() );
				mysql_query("DELETE FROM teamLeads WHERE employees_idE = $idE") or die (mysql_error() );
				
				$delContracts = mysql_query("SELECT idCon, idE FROM contracts WHERE idE = $idE")or die (mysql_error());
				while ($findContracts = mysql_fetch_array($delContracts)) 
				{	
					mysql_query("DELETE FROM approvals WHERE idCon =\"$findContracts[idCon]\" ") or die (mysql_error() );
					mysql_query("DELETE FROM approvals WHERE idE = $idE ") or die (mysql_error() );
					mysql_query("DELETE FROM emailAliasesEmp WHERE idCon = \"$findContracts[idCon]\"") or die (mysql_error() );
					mysql_query("DELETE FROM contFileServers WHERE idCon = \"$findContracts[idCon]\"") or die (mysql_error() );
					mysql_query("DELETE FROM teamLeads WHERE contracts_idCon = \"$findContracts[idCon]\"") or die (mysql_error() );
					mysql_query("DELETE FROM parking WHERE contracts_idCon = \"$findContracts[idCon]\"") or die (mysql_error() );
					mysql_query("DELETE FROM contracts WHERE idCon = \"$findContracts[idCon]\" AND idE = $idE") or die (mysql_error() );
				}
				mysql_query("DELETE FROM employees WHERE ppAccountStatut = 1 AND idE = $idE") or die (mysql_error() );
				echo $clean['firstname']." ".$clean['lastname']." removed <br />";
			}
		}
			if ($removeBtn) {
				echo "<p><br /><button class='btn btn-default'>Confirm selected changes</button></p>";
				echo "</form>";
			}
			
			
		// force suppress	
		if (isset($_GET['confirm']))
		{
			if (isset($_POST['delete'])) 
			{
				if (!empty($_POST['delete']))
				{
					foreach($_POST['delete'] as $check) 
					{
						mysql_query("DELETE FROM employeeGroup WHERE idE = $check") or die (mysql_error() );
						mysql_query("DELETE FROM teamLeads WHERE employees_idE = $check") or die (mysql_error() );
						
						$delContracts = mysql_query("SELECT idCon, idE FROM contracts WHERE idE = $check")or die (mysql_error());
						while ($findContracts = mysql_fetch_array($delContracts)) 
						{	
							mysql_query("DELETE FROM approvals WHERE idCon =\"$findContracts[idCon]\" ") or die (mysql_error() );
							mysql_query("DELETE FROM approvals WHERE idE = $check ") or die (mysql_error() );
							mysql_query("DELETE FROM emailAliasesEmp WHERE idCon = \"$findContracts[idCon]\"") or die (mysql_error() );
							mysql_query("DELETE FROM contFileServers WHERE idCon = \"$findContracts[idCon]\"") or die (mysql_error() );
							mysql_query("DELETE FROM teamLeads WHERE contracts_idCon = \"$findContracts[idCon]\"") or die (mysql_error() );
							mysql_query("DELETE FROM parking WHERE contracts_idCon = \"$findContracts[idCon]\"") or die (mysql_error() );
							mysql_query("DELETE FROM contracts WHERE idCon = \"$findContracts[idCon]\" AND idE = $check") or die (mysql_error() );
						}
						mysql_query("DELETE FROM employees WHERE idE = $check") or die (mysql_error() );
						echo "Employee #".$check."Deleted<br />";
						echo "<meta http-equiv='refresh' content='0;url=index.php?p=empList&tab=5'>";
					}
				}
			}	
		}
		// end of force suppresion
		
	
	}

?>

