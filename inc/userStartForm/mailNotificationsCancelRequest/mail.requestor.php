<?php
	$requestorInfo = mysql_query("SELECT * FROM employees AS emp
									INNER JOIN contracts AS cont ON emp.contract = cont.idCon
								WHERE emp.idE = \"$requestor\"") or die(mysql_error());
	$requestorDB = mysql_fetch_array($requestorInfo);

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
	$mailto = $requestorDB["primaryEmail"]; // for testing, aliases test. is ONLY sent to the IT
	echo "Requestor mail sent to : " . $requestorDB["firstname"] . " (" . $requestorDB["primaryEmail"] . ")<br />";
	$mail->addAddress($mailto, 'You');


	//Set the subject line
	$mail->Subject = "[People Portal] Request for " . $firstname . " " . $lastname . " correctly cancelled";


	//Replace the plain text body with one created manually
	$mail->IsHTML(TRUE);
	$mail->Body = "
					<body>
						<p>Hello,</p>

						<p>Your request about creating " . $firstname . " " . $lastname . " in People Portal has been correctly cancelled.
-						<p>Have a good day,
						<br />People Portal</p>
						<p><strong><a href='http://peopleportal.tbwagroup.be' target='blank'>Click here to access People Portal</a> or go to http://peopleportal.tbwagroup.be<strong></p>
						<p>This is an automatic email sent by People Portal.</p>
					</body>
					";

	// if the mail viewer don't support html
	$mail->AltBody = "
						Request cancelled
							";

	//send the message, check for errors
	if (!$mail->send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	}

?>
