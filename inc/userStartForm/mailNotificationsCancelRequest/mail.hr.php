<?php
	require($_SERVER['DOCUMENT_ROOT'] . "/groupsName.conf.php");


	$queryEmail = mysql_query("
							SELECT * FROM employees AS emp
								INNER JOIN employeeGroup AS empGroup ON empGroup.idE = emp.idE
								INNER JOIN groups AS groups ON groups.idGroup = empGroup.idGroup
								INNER JOIN contracts AS cont ON emp.idE = cont.idE
							WHERE groups.groupName = \"$pp_hr\"
																") or die (mysql_error());


	//SMTP needs accurate times, and the PHP time zone MUST be set
	//This should be done in your php.ini, but this is how to do it if you don't have access to that
	date_default_timezone_set('Etc/UTC');


	//Create a new PHPMailer instance
	$mail = new PHPMailer();
	//Tell PHPMailer to use SMTP
	$mail->isSMTP();
	//Enable SMTP debugging
	// 0 = off (for production use)
	// 1 = client messages
	// 2 = client and server messages
	$mail->SMTPDebug = 0;
	//Ask for HTML-friendly debug output
	$mail->Debugoutput = 'html';
	$mail->Host = 'mail.tbwagroup.be';
	$mail->Port = 25;
	//Whether to use SMTP authentication
	$mail->SMTPAuth = FALSE;

	//Set who the message is to be sent from
	$mail->setFrom('people.portal@tbwagroup.be', 'People Portal');

	//Set who the message is to be sent to
	while ($email = mysql_fetch_array($queryEmail)) {
		// $mailto = "test.".$email["primaryEmail"]; // for testing, aliases test. is ONLY sent to the IT
		$mailto = $email["primaryEmail"];
		$name = $email["firstname"] . " " . $email["lastname"];
		echo "An email has been sent to : " . $mailto . " " . $name . "<br />";
		$mail->addAddress($mailto, $name);
	}
	//Set the subject line
	$mail->Subject = "[People Portal] Request for " . $firstname . " " . $lastname . " cancelled";


	if ($emp["startDate"] == "") {
		$startDate = "unknown";
	} else {
		$startDate = $emp["startDate"];
	}
	//Replace the plain text body with one created manually
	$mail->IsHTML(TRUE);
	$mail->Body = "
					<body>
						<p>Hello,</p>
							<p>The request for " . $emp["firstname"] . " " . $emp["lastname"] . " has been cancelled</p>
							<p>Have a good day,
							<br/> People Portal.</p>
							<p><strong><a href='http://peopleportal.tbwagroup.be' target='blank'>Click here to access People Portal</a> or go to http://peopleportal.tbwagroup.be<strong></p>
							<p>This is an automatic email sent by People Portal.</p>
					</body>
					";

	// if the mail viewer don't support html
	$mail->AltBody = "
						Request cancelled.
							";

	//send the message, check for errors
	if (!$mail->send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	}




?>
