<!--
This script aids user login

Included by:
index.php

Hrefs pointing here:

Requires:

Includes:
bdd/adConnect.inc.php
loginForm.inc.php
login.inc.php

Form actions:

-->

<?php include ($_SERVER['DOCUMENT_ROOT']."/bdd/adConnect.inc.php"); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="favicon.ico">

    <title>People Portal</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	
	<link rel="stylesheet" type="text/css" href="widgets/css/jquery.jdigiclock.css" />
	<script type="text/javascript" src="widgets/lib/jquery-1.3.2.min.js"></script>
	<script type="text/javascript" src="widgets/lib/jquery.jdigiclock.js"></script>
	
<script type="text/javascript">
    $(document).ready(function() {
        $('#digiclock').jdigiclock({
        });
    });
</script>
  </head>

  <body>
    <div class="container">
	<center><img src="img/logo.png"  height="80px"/></center>
<?php
if (!isset($_GET["p"]))
{
	include ("loginForm.inc.php");
?>


<?php
}
if (isset($_GET["p"]))
{
	include ("login.inc.php");
}
?>
    </div> <!-- /container -->
	
<?php if ($localTest == 1) { ?>
	<p>Tests accounts :
		<ul>
			<li class="text-warning">PP Admin : 50%</li>
			<li class="text-success">michel.michel (PP HR) : 85%</li>
			<li class="text-success">building.test (PP Building) : 90%</li>
			<li class="text-success">carfleet.test (PP Carfleet) : 90%</li>
			<li class="text-success">facebook.test (PP Facebook) : 90%</li>
			<li class="text-success">finance.test (PP Finance) : 90%</li>
			<li class="text-warning">planning.test (PP Planning) : 70%</li>
			<li class="text-warning"><i>(alex.ameye (NA All, Basic user) : 30%)</i></li>
			<li class="text-success">Synchronisation tools : 80%</li>
		</ul>
	</p>
<?php } ?>
  </body>
</html>
