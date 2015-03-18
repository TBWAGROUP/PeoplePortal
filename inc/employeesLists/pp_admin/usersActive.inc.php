<!--
This script prepares and shows Active users for PP Admin lists

Included by:
inc/employeesLists/pp_admin-employeeList.inc.php

Hrefs pointing here:

Requires:

Includes:
index.php?p=emp
jtable/usersStatutQueries.php?action=listActive

Form actions:

-->

<div id="PeopleTableContainerActive" style="width: 100%; overflow-x: scroll"></div>

	<script type="text/javascript">

		$(document).ready(function () 
		{
		    //Prepare jTable
			$('#PeopleTableContainerActive').jtable({
				title: 'Active Users, employee.ppAccountStatut = 0',
				paging: true,
				pageSize: 500,
				sorting: true,
				selecting : true,
				multiselect: true,
				defaultSorting: 'firstname ASC',
				actions: {
					listAction: 'jtable/usersStatutQueries.php?action=listActive'
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
			$('#PeopleTableContainerActive').jtable('load');

		});

	</script>

 
 
 
