<?php
	$queryEmail = mysql_query("
							SELECT * FROM teamLeads AS tl
							INNER JOIN contracts AS cont ON cont.idE = tl.employees_idE
							INNER JOIN employees AS emp ON emp.idE = cont.idE
							WHERE tl.contracts_idCon = $idCon AND tl.appType = 0
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
			echo "Mail sent to manager at : " . $mailto . " (" . $name . ")<br />";
			// $mailto = "test.".$email["primaryEmail"];

			$mail->addAddress($mailto, $name);
		}
		//Set the subject line
		$mail->Subject = "[People Portal] You are the manager of a new employee " . $emp["firstname"] . " " . $emp["lastname"];


		if ($emp["startDate"] == "") {
			$startDate = "unknown";
		} else {
			$startDate = $emp["startDate"];
		}

	if ($emp["endDate"] == "") {
		$endDate = "not set";
	} else {
		$endDate = $emp["endDate"];
	}

		//Replace the plain text body with one created manually
		$mail->IsHTML(TRUE);
		$mail->Body = "
						<body>
							<p>Hello,</p>

							<p>A request from People Portal for a new user shows you are his manager.
							<p>Name: " . $emp["firstname"] . " " . $emp["lastname"] . "
							<p>Department: " . $emp["nameDepartment"] . "</p>
							<p>Function: " . $emp["functionName"] . "</p>
							<p>Type: " . $emp["empType"] . "</p>
							<p>Start date: " . $startDate . "</p>
							<p>End date: " . $endDate . "</p>
							<br/>
							<br />You can find more information on People Portal.</p>			
							<p>Have a good day,
							<br/> People Portal.</p>
							<p><strong><a href='http://peopleportal.tbwagroup.be' target='blank'>Click here to access People Portal</a> or go to http://peopleportal.tbwagroup.be<strong></p>
							<p>This is an automatic email sent by People Portal.</p>
						</body>
						";

		// if the mail viewer don't support html
		$mail->AltBody = "
							Hello, a request from People Portal for a new user shows you are his manager.
								";

		//send the message, check for errors
		if (!$mail->send()) {
			echo "Mailer Error: " . $mail->ErrorInfo;
		}
	} // if > 0 managers
?>
