<!--
This script defines the main view, based on PP membership of current user

Included by:
index.php

Hrefs pointing here:

Requires:

Includes:
inc/mainViews/*.inc.php

Form actions:

-->

<?php
	// definiting the main view page for a group
	if (memberOf($pp_admin)) {
		$mainP = genMainView($pp_admin);
		include("inc/mainViews/" . $mainP . ".inc.php");
	}
	if (memberOf($pp_finance)) {
		$mainP = genMainView($pp_finance);
		include("inc/mainViews/" . $mainP . ".inc.php");
	}
	if (memberOf($pp_hr)) {
		$mainP = genMainView($pp_hr);
		include("inc/mainViews/" . $mainP . ".inc.php");
	}
	if (memberOf($pp_building)) {
		$mainP = genMainView($pp_building);
		include("inc/mainViews/" . $mainP . ".inc.php");
	}
	if (memberOf($pp_carfleet)) {
		$mainP = genMainView($pp_carfleet);
		include("inc/mainViews/" . $mainP . ".inc.php");
	}
	if (memberOf($pp_facebook)) {
		$mainP = genMainView($pp_facebook);
		include("inc/mainViews/" . $mainP . ".inc.php");
	}
	if (memberOf($pp_planning)) {
		$mainP = genMainView($pp_planning);
		include("inc/mainViews/" . $mainP . ".inc.php");
	} else if (memberOf($na_all)) {
		$mainP = genMainView($na_all);
		include("inc/mainViews/" . $mainP . ".inc.php");
	} else {
		session_destroy();
		echo "<meta http-equiv='refresh' content='0;url=index.php'></h4><img src='img/ajax-loader.gif' />";
		// $mainP = "guest";
		// include ("inc/mainViews/".$mainP.".inc.php");		
	}
?>