<!--
This script prepares and shows Active users for PP Admin lists

Included by:
inc/employeesLists/pp_admin-employeeList.inc.php

Hrefs pointing here:

Requires:

Includes:
index.php?p=emp
jtable/usersStatutQueries.php?action=listUsf

Form actions:

-->

<div id="PeopleTableContainerUSF" style="width: 100%; overflow-x: scroll"></div>

<script type="text/javascript">

			$(document).ready(function () 
			{
				//Prepare jTable
				$('#PeopleTableContainerUSF').jtable({
					title: 'Users in start form, employee.ppAccountStatut = 5',
					paging: true,
					pageSize: 500,
					sorting: true,
					selecting : false,
					defaultSorting: 'startDateTS ASC',
					actions: {
						listAction: 'jtable/usersStatutQueries.php?action=listUsf'
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
								 return '<a href="index.php?p=emp&idE=' + data.record.idE + '&idCon=' + data.record.idCon + '&upn=' + data.record.upn + '">' + data.record.firstname + '</a> '; }
						},
						lastname: {
							title: 'Lastname',
								display: function(data) {
								 return '<a href="index.php?p=emp&idE=' + data.record.idE + '&idCon=' + data.record.idCon + '&upn=' + data.record.upn + '">' + data.record.lastname +'</a> '; }
						},	
						startDateTS: {
							title: 'Start Date',
							display: function(data) {
								 return data.record.startDate; }
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
						empType: {
							title: 'Type',
							width:'5%',
						}
					},
					
					selectionChanged: function (data) {
						return window.location ="index.php?p=emp&idE="+ data.selectedRows.idE;
					
					}
				});

				//Load person list from server
				$('#PeopleTableContainerUSF').jtable('load');

			});

		</script>
