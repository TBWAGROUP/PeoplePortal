<?php

// call the Zend Gdata library
require_once ($_SERVER['DOCUMENT_ROOT']."/Zend/Loader.php");

Zend_Loader::loadClass('Zend_Http_Client');
Zend_Loader::loadClass('Zend_Gdata');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
Zend_Loader::loadClass('Zend_Gdata_Calendar');


// Authenticate to Google docs
$authService = Zend_Gdata_Calendar::AUTH_SERVICE_NAME;
$httpClient = Zend_Gdata_ClientLogin::getHttpClient("people.portal@tbwagroup.be", "Qwerty!", $authService);



$service = new Zend_Gdata_Calendar($httpClient);

$opEndDate = convertgDataTime($contract["operationalEndDate"]);
$matEndDate = convertgDataTime($contract["materialReturnDate"]);
$disccDate= convertgDataTime($contract["disableAccountDate"]);
// $endDate = convertgDataTime($contract["endDate"]);

// ****************----------------------------------------------
$event= $service->newEventEntry();



$event->title = $service->newTitle("Operational end date for : ".$contract['firstname']." ".$contract['lastname']. ".");
$event->content =
    $service->newContent("Operational end date");
 
// Set the date using RFC 3339 format.
$startDate = $opEndDate;
$startTime = "08:00";
$endDate = $opEndDate;
$endTime = "18:00";
$tzOffset = "+02";
 
$when = $service->newWhen();
$when->startTime = "{$startDate}T{$startTime}:00.000{$tzOffset}:00";
$when->endTime = "{$endDate}T{$endTime}:00.000{$tzOffset}:00";
$event->when = array($when);
 
// Upload the event to the calendar server
// A copy of the event as it is recorded on the server is returned





// Schedule the event to occur on December 05, 2007 at 2 PM PST (UTC-8)
// with a duration of one hour.

$when = $service->newWhen();
$when->startTime = $startDate;
// $when->endTime= $startDate;
 
// Apply the when property to an event
$event->when = array($when);


// Create a new reminder object. It should be set to send an email
// to the user 10 minutes beforehand.

$reminderUser = array();

$reminder = $service->newReminder();
$reminder2= $service->newReminder();
$reminder3 = $service->newReminder();
$reminder4 = $service->newReminder();

$reminder->method = "email";
$reminder->days = "7";

$reminder2->method = "email";
$reminder2->days = "2";

$reminder3->method = "email";
$reminder3->days = "1";
 
 
 
// Apply the reminder to an existing event's when property
// $when = $event->when[0];
$when->reminders = array($reminder,$reminder2,$reminder3);

$newEvent = $service->insertEvent($event);



echo "Event created in the calendar";
?>