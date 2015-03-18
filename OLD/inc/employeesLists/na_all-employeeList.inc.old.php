<!--
This script shows the active employee list to all john does directly from jtable/employeesListQueries.php

Included by:
index.php

Hrefs pointing here:

Requires:

Includes:
jtable/employeesListQueries.php

Form actions:
index.php?p=empList&action=search
index.php?p=empList&action=list

-->

<center>
<div >
	<form method="GET" width="100px" action="index.php?p=empList&action=search" name="searchForm" class="form-inline">
		<input type="text"  name="searchF" class="form-control" size=50 value="<?php if (isset ($_GET['searchF'])) { echo $_GET['searchF']; } ?>"  placeholder="User search" autofocus/>
		<input type="hidden" name="p" value="empList" />
	</form>
</div>
</center>
<br />
<?php 

if (isset ($_GET['searchF'])) 
{ 
	$action="search";
	echo "<p align='center'><a href='index.php?p=empList'>View All</a></p>";
} 
else { 
	$action="listAllStaff";
}

?>

<div id="PeopleTableContainer" style="width: 100%;"></div>

	<script type="text/javascript">

		$(document).ready(function () 
		{
		    //Prepare jTable
			$('#PeopleTableContainer').jtable({
				title: 'List of All People (mouseover a name to get a bio!)',
				paging: true,
				pageSize: 250,
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
//                                picture: {
//                                                        title: 'Picture',
//                                                                display: function(data) {
//                                                                return '<center><img src="http://server-united.tbwagroup.be/' + data.record.upn + '.jpg" height="32px"></center>'; }
//                                                },
						firstname: {
							title: 'Firstname',
								display: function(data) {
								 return '<a href="index.php?p=emp&idE=' + data.record.idE + '&upn=' + data.record.upn + '" title="' + data.record.bio + '">' + data.record.firstname + '</a> '; }
						},
						lastname: {
							title: 'Lastname',
								display: function(data) {
								 return '<a href="index.php?p=emp&idE=' + data.record.idE + '&upn=' + data.record.upn + '" title="' + data.record.bio + '">' + data.record.lastname + '</a> '; }
						},	
						language: {
							title: 'Lang',
							width: '1%',
						},
						functionName: {
							title: 'Function',
							width: '1%',
						},
						nameDepartment: {
							title: 'Department',
							width: '1%',
						},
						labelName: {
							title: 'Label',
							width: '1%',
						},	
						financePayroll: {
							title: 'Payroll',
							width: '1%',
						},
						internalPhone: {
							title: 'Phone',
						},	
						mobile: {
							title: 'Mobile',
						},	
						primaryEmail: {
							title: 'Email',
							width: '1%',
						},	
						nrPlaat: {
							title: 'Number Plate',
						},
						parking: {
							title: 'Parking',
						}
					},
			});

			//Load person list from server
			$('#PeopleTableContainer').jtable('load');

		});

	</script>

 
 
 
