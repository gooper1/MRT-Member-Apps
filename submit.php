<?php
// IMPORTANT!!! --> Don't forget to sanitize inputs!


// So mysql will actually report errors to me.  o.O
//mysqli_report(MYSQLI_REPORT_ERROR);


// Included files
require_once( "functions/database.php" );
require_once( "functions/ip.php" );
require_once( "functions/user.php" );
require_once( "config.php" );


// Get POST data
function getPost($field)
{
	if( isset($_POST[$field]) )
		return $_POST[$field];
	return "";
}
$in_userIGN = getPost("username");
$in_email = getPost("email");
$in_age = getPost("age");
$in_location = getPost("location");
$in_heardof = getPost("heardof");
$in_heardof_other = getPost("heardof_other");
$in_links = getPost("links");
$in_reasons = getPost("reasons");
$in_ipAddress = getPost("ipAddress");
$in_rules = getPost("rules");

// Truncate any data that is too long
$in_userIGN = substr($in_userIGN, 0, $maxDataAccepted);
$in_email = substr($in_email, 0, $maxDataAccepted);
$in_age = substr($in_age, 0, $maxDataAccepted);
$in_location = substr($in_location, 0, $maxDataAccepted);
$in_heardof = substr($in_heardof, 0, $maxDataAccepted);
$in_heardof_other = substr($in_heardof_other, 0, $maxDataAccepted);
$in_links = substr($in_links, 0, $maxDataAccepted);
$in_reasons = substr($in_reasons, 0, $maxDataAccepted);


// Connect to the database
$dbLink = DB_connect();
if( $dbLink === false ) {
		$reason = "An error at our end has occurred.  Please retry your application later.";
		require_once( "response_refused.php" );
}

else if (array_sum($in_rules) != 429) {
	$reason = "Your answer to the final question of the application, regarding the rules and FAQ, was incorrect. Please read the documents carefully and try your application again.";
	require_once("response_refused.php");
}

// Check that all fields were filled out
else if( $in_userIGN == "" || $in_email == "" || $in_age == ""
	|| $in_age == "" || $in_location == "" || $in_heardof == ""
	|| $in_links == "" || $in_reasons == "" )
{
	$reason = "Please fill out all of the application form.";
	require_once( "response_refused.php" );
}


// Check for hacking attempt
else if( $in_ipAddress != getUserIp() ) {
	logHack( $dbLink, getUserIp(), $in_userIGN );
	$reason = "A hacking attempt has been detected and logged.";
	require_once( "response_refused.php" );
}


// Check to make sure they can submit an application
else if( isMember($in_userIGN) ) {
	$reason = "You are already a registered member.";
	require_once( "response_refused.php" );
}
else if( isBanned($in_userIGN) ) {
	$reason = "You cannot submit an application because you are banned from the server. Please email admin@minecartrapidtransit.net to appeal your ban.";
	require_once( "response_refused.php" );
}
else if( isIPBanned($in_ipAddress) ) {
	$reason = "Your IP address has been banned. Please email admin@minecartrapidtransit.net to appeal your ban.";
	require_once( "response_refused.php" );
}
else if( isAlreadySubmitted($dbLink, $in_userIGN) ) {
	$reason = "You have already submitted an application in the past 24 hours.";
	require_once( "response_refused.php" );
}
else if( isAlreadySubmittedIp($dbLink, $in_ipAddress) >= $maxSubmissionsFromIP ) {
	$reason = $maxSubmissionsFromIP ." applications have already been submitted from your IP address in the past 24 hours.  Please wait before submitting your application.";
	require_once( "response_refused.php" );
}
else if( isPermGuest($in_userIGN) ) {
	$reason = "You have submitted too many rejected applications.";
	require_once( "response_refused.php" );
}


// Okay, so everything seems to be okay.
else {
	if( $in_heardof != "other" )
		$in_heardof_other = "";
	$ret = submitApplication( $dbLink, $in_userIGN, $in_email, 
		$in_age, $in_location, $in_heardof, $in_heardof_other, 
		$in_links, $in_reasons, $in_ipAddress, 
		detectTypicalProxy(), detectHttpProxy($in_ipAddress), 
		detectTorProxy($in_ipAddress, 80) | detectTorProxy($in_ipAddress, 25565)
		);
	if( $ret )
		require_once( "response_submitted.php" );
	else {
		$reason = "An error at our end has occurred.  Please retry your application later.";
		require_once( "response_refused.php" );
	}
}


// Disconnect from the database
DB_disconnect( $dbLink );


?>
