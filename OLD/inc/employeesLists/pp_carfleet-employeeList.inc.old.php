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
				title: 'List of People (mouseover a name for a bio!)',
				paging: true,
				pageSize: 500,
				sorting: true,
				defaultSorting: 'firstname ASC',
				actions: {
					listAction: 'jtable/employeesListQueries.php?action=<?php echo $action; ?><?php if (isset ($_GET['searchF'])) { echo "&searchF=".$_GET['searchF']; } ?>'
					//createAction: 'view1.php?action=create',
					//updateAction: 'view1.php?action=update',
					//deleteAction: 'view1.php?action=delete'
				},
					fields: {
						idE: {
							key: true,
							create: false,
							edit: false,
							list: false
						},
//                                                picture: {
//                                                        title: 'Picture',
//                                                                display: function(data) {
//								return '<center><img src="http://server-united.tbwagroup.be/' + data.record.upn + '.jpg" height="64px"></center>'; }
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
						mobile: {
							title: 'Mobile',
						},	
						primaryEmail: {
							title: 'Email',
						},	
						nrPlaat: {
							title: 'Number Plate',
							width:'5%',
						},
						parking: {
							title: 'Parking',
						},
						
						empType: {
							title: 'Type',
							width:'5%',
						}
					},
			});

			//Load person list from server
			$('#PeopleTableContainer').jtable('load');

		});

	</script>

 
 
 
