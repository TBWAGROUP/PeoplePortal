<!--
This script prepares and shows All users for PP Admin lists

Included by:
inc/employeesLists/pp_admin-employeeList.inc.php

Hrefs pointing here:

Requires:

Includes:
index.php?p=emp
jtable/employeesListQueries.php?action=$action

Form actions:

-->

<div id="PeopleTableContainerAll" style="width: 100%; overflow-x: scroll"></div>

<script type="text/javascript">

			$(document).ready(function () 
			{
				//Prepare jTable
				$('#PeopleTableContainerAll').jtable({
					title: 'All users, whatever employee.ppAccountStatut value',
					paging: true,
					pageSize: 500,
					sorting: true,
					selecting : false,
					defaultSorting: 'firstname ASC',
					actions: {
						listAction: 'jtable/usersStatutQueries.php?action=<?php echo $action; ?><?php if (isset ($_GET['searchF'])) { echo "&searchF=".$_GET['searchF']; } ?>'
					},
					fields: {
						idE: {
							key: true,
							create: false,
							edit: false,
							list: false
						},
						ppAccountStatut: {
							title: '',
							create: false,
							edit: false,
							display: function(data) {
								 return '<img src="img/ppAS-' + data.record.ppAccountStatut + '_S.png" />' ;
							},
						},
						picture: {
							title: 'Picture',
							display: function(data) {
								return '<center><a href="index.php?p=emp&idE=' + data.record.idE + '&idCon=' + data.record.idCon + '&upn=' + data.record.upn + '&objectsid=' + data.record.objectsid +'"><img src="http://peopleportal.tbwagroup.be/pics/' + data.record.upn + '_tn.png" height="72px"></a></center>'; }
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
							title: 'Lang'
						},
						functionName: {
							title: 'Function'
						},
						nameDepartment: {
							title: 'Department'
						},
						labelName: {
							title: 'Label'
						},
						financePayroll: {
							title: 'Payroll'
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
							title: 'UPN'
						}
					},
				
				});

				//Load person list from server
				$('#PeopleTableContainerAll').jtable('load');

			});

		</script>
