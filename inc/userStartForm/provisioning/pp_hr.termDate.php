<script>

  $(function() {
    $( ".datepicker" ).datepicker({dateFormat: 'dd/mm/yy', firstDay:1, changeMonth:true, changeYear:true, yearRange: "1900:2100", constrainInput:true, showWeek:true });
  });
</script>
<?php 
	$idConUrl = mysql_real_escape_string($_GET['idCon']);
	$idEurl = mysql_real_escape_string($_GET['idE']);
	$queryCont = mysql_query("
												SELECT * FROM employees AS emp
												INNER JOIN contracts AS cont ON cont.idE = emp.idE
												INNER JOIN teamLeads AS tl ON tl.contracts_idCon = cont.idCon
												INNER JOIN emailAliasesEmp AS eae ON eae.idCon = cont.idCon
												INNER JOIN labels AS lab ON lab.idLab = cont.idLab
												INNER JOIN departments AS dep ON dep.idDep = cont.idDep
												INNER JOIN functions AS func ON func.idFunc = cont.idFunc
												WHERE cont.idCon = $idConUrl
											") or die (mysql_error() );
	$contract = mysql_fetch_array($queryCont);
?>	

<?php if (isset($_GET['updateTermDate']))
{
	mysql_query("UPDATE contracts SET operationalEndDate = \"$_POST[opEndDate]\" , materialReturnDate = \"$_POST[matRetDate]\", disableAccountDate = \"$_POST[disAccDate]\"  WHERE idCon = $idConUrl") or die (mysql_error());
	
	//add events in google calendar HERE
	// include ('inc/userStartForm/provisioning/pp_hr.termDate.gCalendar.php"); // UNFINISHED
	
	echo "<h4><img src='img/ajax-loader.gif' /> Updating changes...</h4><meta http-equiv='refresh' content='0;url=index.php'>";
}
?>


<form method="POST" action='index.php?p=emp&idE=<?php echo $idEurl; ?>&idCon=<?php echo $idConUrl; ?>&updateTermDate&tab=3' name="usf1">
	<table  class="userStartForm">
			<tr>
				<th>Operational end date</th>
				<td>
					<input  type="text" size="20" name="opEndDate" class="datepicker" value='<?php echo $contract['operationalEndDate']; ?>' tabindex = 160 />
				</td>
			</tr>
			<tr>
				<th>Material return date</th>
				<td>
					<input  type="text" size="20" name="matRetDate" class="datepicker" value='<?php echo $contract['materialReturnDate']; ?>'  tabindex = 170 />
				</td>
		</tr>
		<tr>
				<th>Disable account date</th>
				<td>
					<input  type="text" size="20" name="disAccDate" class="datepicker" value='<?php echo $contract['disableAccountDate']; ?>'  tabindex = 170 />
				</td>
		</tr>
		</table>
	      <p><br /><button class="btn btn-default btn-sml" title="Approve"><img src='img/saveXS.png' />  Save</button></p>
	</form>

