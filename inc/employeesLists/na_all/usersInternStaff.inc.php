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

<div id="PeopleTableContainerInternStaff" style="width: 100%; overflow-x: scroll"></div>

	<script type="text/javascript">

		$(document).ready(function () 
		{
		    //Prepare jTable
			$('#PeopleTableContainerInternStaff').jtable({
				title: 'Interns',
				paging: true,
				pageSize: 500,
				sorting: true,
				defaultSorting: 'firstname ASC',
				actions: {
					listAction: 'jtable/employeesListQueries.php?action=listInternStaff'
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
						startDate: {
							title: 'Start Date',
							width:'5%',
						},
						endDate: {
							title: 'End Date',
							width:'5%',
						},
					},
			});

			//Load person list from server
			$('#PeopleTableContainerInternStaff').jtable('load');

		});

	</script>
