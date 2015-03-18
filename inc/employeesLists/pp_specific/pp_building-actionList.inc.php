<!--
This script prepares and shows

Included by:

Hrefs pointing here:

Requires:

Includes:

Form actions:

-->

<div id="PeopleTableContainerPPBuilding" style="width: 100%; overflow-x: scroll"></div>
	<script type="text/javascript">

		$(document).ready(function () 
		{
		    //Prepare jTable
			$('#PeopleTableContainerPPBuilding').jtable({
				title: 'List of People (and their Business Card details)',
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
						businessCard: {
							title: 'Business Card',
							width:'4%',
							display: function(data) {
								return '<img src="img/chk-' + data.record.businessCardNeeded +'-XS.png" /> (<img src="img/chk-' + data.record.businessCardCreated +'-XS.png" />) '; }
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
						labelName: {
							title: 'Label',
							width: '5%',
						},
						nameDepartment: {
							title: 'Department',
							width: '5%',
						},
						primaryEmail: {
							title: 'Email',
						},
						language: {
							title: 'Lang',
							width: '1%',
						},
						functionName: {
							title: 'Function',
							width: '5%',
						},
						nrPlaat: {
							title: 'Number Plate',
						},
						parking: {
							title: 'Parking',
							width:'5%',
						},
						badgeNr: {
							title: 'Badge Number',
							width:'5%',
						},
						badgeAccessLevel: {
							title: 'Badge Access Level',
							width:'5%',
						},
						empType: {
							title: 'Type',
							width:'5%',
						}
					},
			});

			//Load person list from server
			$('#PeopleTableContainerPPBuilding').jtable('load');

		});

	</script>

 
 
 
