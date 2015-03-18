<?php

// association of the google fields with the AD fields

	// table employees
		// table contracts (2 tabulations)


	if ($x == 0)
	{
		$firstname = $entry;
	}
	if ($x == 1)
	{
		$lastname = $entry;
		// genering basic UPN
		$upn2 = genUpn($firstname,$lastname);
	}
		if ($x == 2)
		{
			$function = $entry;
		}
		if ($x == 3)
		{
			$startDate = $entry;
		}
		if ($x == 4)
		{
			$financePayroll = $entry;
		}
		if ($x == 5)
		{
			$label = $entry;
		}
		if ($x == 6)
		{
			$department = $entry;
		}
			if ($x == 7)
			{
				$teamLead = $entry;
			}
		if ($x == 8)
		{
			$statut = $entry;
		}
		if ($x == 9)
		{
			$timeRegime = $entry;
		}
		if ($x == 10)
		{
			$language = $entry;
		}
		if ($x == 11)
		{
			$endDate = $entry;
		}
		if ($x == 12)
		{
			$operationalEndDate = $entry;
		}
	if ($x == 13)
	{
		$address = $entry;
	}	
	if ($x == 14)
	{
		$birthdate = $entry;
	}
			// parking table
			if ($x == 15)
			{
				$nrPlaat = $entry;
			}	
			if ($x == 16)
			{
				$comesBy = $entry;
			}
			if ($x == 17)
			{
				$parking = $entry;
			}
			if ($x == 18)
			{
				$parkingBricoNr = $entry;
			}
			
		if ($x == 19)
		{
			$maconomyRol = $entry;
		}
		if ($x == 20)
		{
			$timeLockout = $entry;
		}
		if ($x == 21)
		{
			$checkMinHours = $entry;
			$checkMinHours = strtoupper($checkMinHours);
			if ($checkMinHours == "YES") { $checkMinHours = TRUE; }
		}
	if ($x == 22)
	{
		$upn = $entry;
	}
		if ($x == 23)
		{
			$group = $entry;
		}
		if ($x == 24)
		{
			$phoneNumber = $entry;
		}
		if ($x == 25)
		{
			$internalPhone = $entry;
		}
	if ($x == 26)
	{
		$mobile = $entry;
	}
		if ($x == 27)
		{
			$companyPhone = $entry;
		}
		if ($x == 28)
		{
			$mobilePhoneOwner = $entry;
		}
		if ($x == 29)
		{
			$cellPhoneAbo = $entry;
		}
		if ($x == 30)
		{
			$badgeNr = $entry;
		}
	if ($x == 31)
	{
		$contactEmergency = $entry;
	}
		if ($x == 32)
		{
			$businessCard = strtoupper($entry);
			if ($businessCard = "YES") { $businessCard = TRUE; }
		}
		if ($x == 33)
		{
			$emailSignatureLogo = $entry;
		}
		if ($x == 34)
		{
			$primaryEmail = $entry;
		}
	?>