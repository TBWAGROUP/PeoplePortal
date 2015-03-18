<!--
This script destroys the current session

Included by:
index.php

Hrefs pointing here:

Requires:

Includes:

Form actions:

-->

<?php
	session_start();

	session_destroy();
	echo "<meta http-equiv='refresh' content='0;url=index.php'>";
?>