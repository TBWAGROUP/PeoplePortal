<?php
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
	$mailto = $requestor;
	echo "Requestor mail sent to : " . $mailto . " (" . $requestor . ")<br />";
	$mail->addAddress($mailto, 'You');


	//Set the subject line
	$mail->Subject = "[People Portal] New user request for " . $firstname . " " . $lastname . " correctly sent to HR";


	//Replace the plain text body with one created manually
	$mail->IsHTML(TRUE);
	$mail->Body = "
					<body>
						<p>Hello,</p>

						<p>Your request for creating " . $firstname . " " . $lastname . " from People Portal has been sent to HR.
						<p>You will receive a response soon.</p>					
						<p>Have a good day,
							<br/> People Portal.</p>
						<p><strong><a href='http://peopleportal.tbwagroup.be' target='blank'>Click here to access People Portal</a> or go to http://peopleportal.tbwagroup.be<strong></p>
						<p>This is an automatic email sent by People Portal. If you want more information, please contact HR or IT.</p>
					</body>
					";

	// if the mail viewer don't support html
	$mail->AltBody = "
						Your request for creating " . $firstname . " " . $lastname . " from People Portal has been sent to the HR service
							";

	//send the message, check for errors
	if (!$mail->send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	}


?>
