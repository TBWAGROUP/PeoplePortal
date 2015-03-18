<!--
This script prepares and shows Permanent Employees (Employees, Contractor Fixed, Contractor Timebased, Administrator)

Included by:

Hrefs pointing here:

Requires:

Includes:
index.php?p=emp
jtable/employeeListQueries.php?action=listInternStaff

Form actions:

-->

<div id="PeopleTableContainerPHDStaff" style="width: 100%; overflow-x: scroll"></div>

	<script type="text/javascript">

		$(document).ready(function () 
		{
		    //Prepare jTable
			$('#PeopleTableContainerPHDStaff').jtable({
				title: 'PHD People',
				paging: true,
				pageSize: 500,
				sorting: true,
				defaultSorting: 'firstname ASC',
				actions: {
					listAction: 'jtable/employeesListQueries.php?action=listPHDStaff'
				},
					fields: {
						idE: {
							key: true,
							create: false,
							edit: false,
							list: false
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
					},
			});

			//Load person list from server
			$('#PeopleTableContainerPHDStaff').jtable('load');

		});

	</script>
