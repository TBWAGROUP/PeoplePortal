<!--
This script prepares and shows

Included by:

Hrefs pointing here:

Requires:

Includes:

Form actions:

-->
<div id="PeopleTableContainerPPPlanning" style="width: 100%; overflow-x: scroll"></div>
<script type="text/javascript">

	$(document).ready(function ()
	{
		//Prepare jTable
		$('#PeopleTableContainerPPPlanning').jtable({
			title: 'List of People (mouseover a name for a bio!)',
			paging: true,
			pageSize: 500,
			sorting: true,
			defaultSorting: 'firstname ASC',
			actions: {
				listAction: 'jtable/employeesListQueries.php?action=<?php echo $action; ?><?php if (isset ($_GET['searchF'])) { echo "&searchF=".$_GET['searchF']; } ?>'
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
						return '<a href="index.php?p=emp&idE=' + data.record.idE + '&idCon=' + data.record.idCon + '&upn=' + data.record.upn + '" title="' + data.record.bio + '">' + data.record.firstname + '</a> '; }
				},
				lastname: {
					title: 'Lastname',
					display: function(data) {
						return '<a href="index.php?p=emp&idE=' + data.record.idE + '&idCon=' + data.record.idCon + '&upn=' + data.record.upn + '" title="' + data.record.bio + '">' + data.record.lastname + '</a> '; }
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
					title: 'Phone',
				},
				empType: {
					title: 'Type',
					width:'5%',
				},
				timeRegime: {
					title: 'Time Regime',
					width:'5%',
				},
				startDate: {
					title: 'Start Date',
					width:'5%',
				},
				endDate: {
					title: 'End Date',
					width:'5%',
				},
				upn: {
					title: 'UPN (login)',
					width:'5%',
				}
			},
		});

		//Load person list from server
		$('#PeopleTableContainerPPPlanning').jtable('load');

	});

</script>




