<?php
	$requestor = $emp["requestor"];
	$queryEmailRequestor = mysql_query("
										SELECT * FROM contracts AS cont
										INNER JOIN employees AS emp ON emp.idE = cont.idE
										WHERE emp.idE = \"$requestor\"
										ORDER BY cont.idCon DESC 
										LIMIT 0,1
						") or die (mysql_error());

	$email = mysql_fetch_array($queryEmailRequestor);

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
	$name = $email["firstname"] . " " . $email["lastname"];
	$mailto = $email["primaryEmail"];
	echo "mail sent to : " . $mailto . "(" . $name . ")<br />";
	$mail->addAddress($mailto, $name);

	//Set the subject line
	$mail->Subject = "[People Portal] " . $emp["firstname"] . " " . $emp["lastname"] . " request refused by HR";

	//Replace the plain text body with one created manually
	$mail->IsHTML(TRUE);
	$mail->Body = "
					<body>
						<p>Hello,</p>

						<p>Your request for " . $emp["firstname"] . " " . $emp["lastname"] . " in People Portal has been refused by HR.

						<p>Have a good day,
						<br />People Portal</p>
						<p><strong><a href='http://peopleportal.tbwagroup.be' target='blank'>Click here to access People Portal</a> or go to http://peopleportal.tbwagroup.be<strong></p>
						<p>This is an automatic email sent by People Portal. If you want more information about your request, please contact HR.</p>
					</body>
					";

	// if the mail viewer don't support html
	$mail->AltBody = "
						Hello, your request for a new user on People Portal has been refused. Have a good day, People Portal.
							";

	//send the message, check for errors
	if (!$mail->send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	}

?>
