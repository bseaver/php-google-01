<?php
require __DIR__ . '/vendor/autoload.php';


// Begin Boot section - Could be moved to boot file or service provider
putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . '/My Project-e58172098e79.json');
$client = new Google_Client;
$client->useApplicationDefaultCredentials();

$client->setApplicationName("Something to do with my representatives");
$client->setScopes(['https://www.googleapis.com/auth/drive','https://spreadsheets.google.com/feeds']);

if ($client->isAccessTokenExpired()) {
    $client->refreshTokenWithAssertion();
}

$accessToken = $client->fetchAccessTokenWithAssertion()["access_token"];
var_dump($accessToken);


// From: https://stackoverflow.com/questions/24621707/google-spreadsheets-api-with-php-fatal-error-uncaught-exception
use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;
// End From


ServiceRequestFactory::setInstance(
    new DefaultServiceRequest($accessToken)
);
// End Boot Section


// Put this in a route
// Get our spreadsheet
$spreadsheet = (new Google\Spreadsheet\SpreadsheetService)
   ->getSpreadsheetFeed()
   ->getByTitle('Copy of Legislators 2017');

// Get the first worksheet (tab)
$worksheets = $spreadsheet->getWorksheetFeed()->getEntries();
$worksheet = $worksheets[0];


// To show that this worksheet works, let’s iterate over every entry and echo it out:
$listFeed = $worksheet->getListFeed();

/** @var ListEntry */
foreach ($listFeed->getEntries() as $entry) {
   $representative = $entry->getValues();
   echo var_dump( $representative);
}





?>
