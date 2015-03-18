<!--
This script ties all employee info together in tabs, based on membership of current user

Included by:
index.php (case "?p=emp")

Hrefs pointing here:

Requires:

Includes:
inc/employeeProfile.inc.php
inc/managing/contractsManagor.inc.php
inc/ppTools/it.tasks.php
inc/ppTools/userGroupsManager.inc.php
inc/showUsfProcess.inc.php
inc/userStartForm/provisioning/it.fields.php
inc/userStartForm/provisioning/pp_building.fields.php
inc/userStartForm/provisioning/pp_car_fleet.fields.php
inc/userStartForm/provisioning/pp_facebook.fields.php
inc/userStartForm/provisioning/pp_finance.fields.php
inc/userStartForm/provisioning/pp_planning.fields.php

Form actions:

-->

<script src="../../SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="../../SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />

<script>
  $(function() {
    $( ".datepicker" ).datepicker({dateFormat: 'dd/mm/yy', firstDay:1, changeMonth:true, changeYear:true, yearRange: "1900:2100", constrainInput:true, showWeek:true });
  });
 </script> 

<?php
	
	if (isset($_GET['tab'])) { $defaultTab = $_GET['tab']; } else { $defaultTab = 0; }
	if (isset($_GET['upn'])) { $upn = $_GET['upn']; }
	if (isset($_GET['idCon'])) { $idConUrl = $_GET['idCon']; }
	$idE = $_GET['idE'];
	$queryEmp = mysql_query("SELECT * FROM employees WHERE idE = $idE") or die(mysql_error());
	$emp=mysql_fetch_array($queryEmp);
?>

  <h3><?php echo $emp['firstname']." ".$emp['lastname']; ?></h3>

<div id="TabbedPanels1" class="TabbedPanels">
	<ul class="TabbedPanelsTabGroup">
		<li class="TabbedPanelsTab" tabindex="0">Infos</li>
		<?php if ( (memberOf($pp_admin)) || (memberOf($pp_hr)) )  { ?><li class="TabbedPanelsTab" tabindex="10">Contract(s)</li><?php } ?>
		<?php if ( (memberOf($pp_admin)) || (memberOf($pp_planning)) ) { ?><li class="TabbedPanelsTab" tabindex="10">Planning info</li><?php } ?>
		<?php if ( (memberOf($pp_admin)) || (memberOf($pp_carfleet)) ) { ?><li class="TabbedPanelsTab" tabindex="10">Car fleet info</li><?php } ?>
		<?php if ( (memberOf($pp_admin)) || (memberOf($pp_finance)) ) { ?><li class="TabbedPanelsTab" tabindex="10">Finance info</li><?php } ?>
		<?php if ( (memberOf($pp_admin)) || (memberOf($pp_building)) ) { ?><li class="TabbedPanelsTab" tabindex="10">Building info</li><?php } ?>
		<?php if ( (memberOf($pp_admin)) || (memberOf($pp_building)) ) { ?><li class="TabbedPanelsTab" tabindex="10">Parking info</li><?php } ?>
		<?php if ( (memberOf($pp_admin)) || (memberOf($pp_building)) ) { ?><li class="TabbedPanelsTab" tabindex="10">Business Card info</li><?php } ?>
		<?php if ( (memberOf($pp_admin)) || (memberOf($pp_facebook)) ) { ?><li class="TabbedPanelsTab" tabindex="10">Facebook info</li><?php } ?>
		<?php if (memberOf($pp_admin)) { ?><li class="TabbedPanelsTab" tabindex="20">Member Of</li> <?php } ?>
		<?php if (memberOf($pp_admin)) { ?><?php if (isset($_GET['activation'])) { ?><li class="TabbedPanelsTab" tabindex="30">IT Activation</li><?php } } ?>
		<?php if ( (memberOf($pp_admin)) || (memberOf($pp_hr))) { ?><li class="TabbedPanelsTab" tabindex="20">USF Process</li> <?php } ?>
		<?php if (memberOf($pp_admin)) { ?><li class="TabbedPanelsTab" tabindex="30">IT Tasks</li><?php } ?>
	</ul>

<div class="TabbedPanelsContentGroup">
		<div class="TabbedPanelsContent"><?php include ("inc/employeeProfile.inc.php"); ?></div>
		<?php if ( (memberOf($pp_admin)) || (memberOf($pp_hr)) )  { ?><div class="TabbedPanelsContent"><?php include ("inc/managing/contractsManagor.inc.php"); ?></div><?php } ?>
		<?php if ( (memberOf($pp_admin)) || (memberOf($pp_planning)) ) { ?><div class="TabbedPanelsContent"><?php include ("inc/userStartForm/provisioning/pp_planning.fields.php"); ?></div><?php } ?>
		<?php if ( (memberOf($pp_admin)) || (memberOf($pp_carfleet)) ) { ?><div class="TabbedPanelsContent"><?php include ("inc/userStartForm/provisioning/pp_car_fleet.fields.php"); ?></div><?php } ?>
		<?php if ( (memberOf($pp_admin)) || (memberOf($pp_finance)) ) { ?><div class="TabbedPanelsContent"><?php include ("inc/userStartForm/provisioning/pp_finance.fields.php"); ?></div><?php } ?>
	<?php if ( (memberOf($pp_admin)) || (memberOf($pp_building)) ) { ?><div class="TabbedPanelsContent"><?php include ("inc/userStartForm/provisioning/pp_building.fields.php"); ?></div><?php } ?>
	<?php if ( (memberOf($pp_admin)) || (memberOf($pp_building)) ) { ?><div class="TabbedPanelsContent"><?php include ("inc/userStartForm/provisioning/pp_parking.fields.php"); ?></div><?php } ?>
		<?php if ( (memberOf($pp_admin)) || (memberOf($pp_building)) ) { ?><div class="TabbedPanelsContent"><?php include ("inc/userStartForm/provisioning/pp_businesscard.fields.php"); ?></div><?php } ?>
		<?php if ( (memberOf($pp_admin)) || (memberOf($pp_facebook)) ) { ?><div class="TabbedPanelsContent"><?php include ("inc/userStartForm/provisioning/pp_facebook.fields.php"); ?></div><?php } ?>
		<?php if (memberOf($pp_admin)) { ?><div class="TabbedPanelsContent"><?php include ("inc/ppTools/userGroupsManager.inc.php"); ?></div><?php } ?>
		<?php if (memberOf($pp_admin)) { ?><?php if (isset($_GET['activation'])) { ?><div class="TabbedPanelsContent"><?php include ("inc/userStartForm/provisioning/it.fields.php"); ?></div><?php } }?>
		<?php if ( (memberOf($pp_admin)) || (memberOf($pp_hr)) ) { ?><div class="TabbedPanelsContent"><?php $source = "WHERE emp.idE=$idE AND validationStage != 4"; include ("inc/showUsfProcess.inc.php"); ?></div><?php } ?>
		<?php if (memberOf($pp_admin)) { ?><div class="TabbedPanelsContent"><?php include ("inc/ppTools/it.tasks.php"); ?></div><?php } ?>
	</div>
</div>

<script type="text/javascript">
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1",  {defaultTab:<?php echo $defaultTab; ?>});
</script>
