<!--
This script prepares and shows

Included by:

Hrefs pointing here:

Requires:

Includes:

Form actions:

-->

<div id="PeopleTableContainerPPCarfleet" style="width: 100%; overflow-x: scroll"></div>
<script type="text/javascript">

	$(document).ready(function ()
	{
		//Prepare jTable
		$('#PeopleTableContainerPPCarfleet').jtable({
			title: 'List of People (and their Car Fleet details)',
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
				comesBy: {
					title: 'Transport',
					width:'5%',
				},
				nrPlaat: {
					title: 'Number Plate',
					width:'5%',
				},
				parking: {
					title: 'Parking',
					width:'5%',
				},
				parkingNr: {
					title: 'Number',
				}
			},
		});

		//Load person list from server
		$('#PeopleTableContainerPPCarfleet').jtable('load');

	});

</script>