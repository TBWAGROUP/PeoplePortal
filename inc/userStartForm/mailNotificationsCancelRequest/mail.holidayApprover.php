<?php
	$queryEmail = mysql_query("
							SELECT * FROM teamLeads AS tl
							INNER JOIN contracts AS cont ON cont.idE = tl.employees_idE
							INNER JOIN employees AS emp ON emp.idE = cont.idE
							WHERE tl.contracts_idCon = $idCon AND tl.appType = 1
						") or die (mysql_error());
	if (mysql_num_rows($queryEmail) > 0) {

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
			$name = $email["firstname"] . " " . $email["lastname"];
			$mailto = $email["primaryEmail"];
			echo "Mail sent to holiday approver at : " . $mailto . " (" . $name . ")<br />";
			$mail->addAddress($mailto, $name);
		}

		//Set the subject line
		$mail->Subject = "[People Portal] Request for " . $firstname . " " . $lastname . " cancelled";

		if ($emp["startDate"] == "") {
			$startDate = "unknown";
		}

		//Replace the plain text body with one created manually
		$mail->IsHTML(TRUE);
		$mail->Body = "
						<body>
							<p>Hello,</p>
							<p>The request for " . $firstname . " " . $lastname . " has been cancelled</p>
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
	} // if > 0 holiday approvers
?>
