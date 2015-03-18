<?php


	/**
    require_once( $_SERVER['DOCUMENT_ROOT'].'/_modules/php/LConfigClass.php' );
    $config = new LConfigClass();
	
	
	//Open database connection
	$con = mysql_connect( $config->get('db_host'),  $config->get('db_user'), $config->get('db_password') );
	mysql_select_db( $config->get('db_name'), $con);
	**/
	
	
	/** local connection 
	$db_host = "127.0.0.1";
	$db_user = "root";
	$db_password = "";
	$db_name = "tbwagroup_people";
**/
	
	/** People Portal connection **/
	$db_host = "10.173.0.34";
	$db_user = "root";
	$db_password = "lpsselCelc!";
	$db_name = "tbwagroup_people";

	
	//Open database connection
	$con = mysql_connect($db_host,$db_user,$db_password);
	mysql_select_db($db_name, $con);
	//mysql_select_db("jtabletestdb", $con);

?>
