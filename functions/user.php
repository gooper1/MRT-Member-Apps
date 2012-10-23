<?php

// Included files
//require_once( "db.php" );


/* Do a query and count the number of rows in the result
 */
function numrows( $dbLink, $query )
{
// test code
echo $query;
	// Why did they get rid of my mysql_num_rows!!??
	// *cries*
	$stmt = mysqli_prepare($dbLink, $query);
	mysqli_stmt_execute( $stmt );
	mysqli_stmt_store_result( $stmt );
	$count = mysqli_stmt_num_rows($stmt);
	mysqli_stmt_close( $stmt );
	return $count;
}


/* Detect if a user is already a registered member
 */
function isMember( $userIGN )
{
	return false;
}


/* Detect if a user is banned
 */
function isBanned( $userIGN )
{
	return false;
}


/* Detect if an IP has been banned
 */
function isIPBanned( $userIP )
{
	return false;
}


/* Detect if a user has already submitted an app in past 24 hours
 */
function isAlreadySubmitted( $dbLink, $userIGN )
{
	$query = sprintf(
		"SELECT * FROM `applications` WHERE `ign`='%s' AND `datetime`>DATE_SUB(CURRENT_TIMESTAMP, INTERVAL %d HOUR)", 
		mysqli_real_escape_string($dbLink, $userIGN), 
		24 );
	return numrows($dbLink, $query);
}


/* Return the number of submissions from an IP in the past 24 hours
 */
function isAlreadySubmittedIp( $dbLink, $ipAddress )
{
	$query = sprintf(
		"SELECT * FROM `applications` WHERE `ip`='%s' AND `datetime`>DATE_SUB(CURRENT_TIMESTAMP, INTERVAL %d HOUR)", 
		mysqli_real_escape_string($dbLink, $ipAddress), 
		24 );
	return numrows($dbLink, $query);
}


/* Detect if a user is a permanent guest
 */
function isPermGuest( $userIGN )
{
	return false;
}


/* Has an IP been the source of a hacking attempt?
 */
function hasHacked( $dbLink, $userIp, $userIGN="" )
{
	$query = sprintf(
		"SELECT * FROM `hackers` WHERE `ign`='%s' OR `ip`='%s'", 
		mysqli_real_escape_string($dbLink, $userIp), 
		mysqli_real_escape_string($dbLink, $userIGN)
		);

	if( numrows($dbLink, $query) )
		return true;
	return false;
}


/* Log an ip as a hacking attempt
 */
function logHack( $dbLink, $userIp, $userIGN )
{
	$query = sprintf( "INSERT INTO `hackers` " .
		"(`datetime`, `ign`, `ip`) " .
		"VALUES('%s', '%s', '%s')", 
		date("Y-m-d H-i-s"), 
		mysqli_real_escape_string($dbLink, $userIGN), 
		mysqli_real_escape_string($dbLink, $userIp)
		);

	return mysqli_query($dbLink, $query) !== false;
}


/* Submit an application
 * Returns false on error
 */
function submitApplication( $dbLink, $userIGN, $userEmail, 
	$userAge, $userLocation, $userHeardOf, $userHeardOf_other, 
	$userLinks, $userReasons, $userIpAddress,
	$typicalProxy, $httpProxy, $torProxy )
{
	$query = sprintf( "INSERT INTO `applications`" .
		"(`datetime`, `status`, `ign`, `email`, `age`, `location`, `heardof`, `heardof_other`, `links`, `reasons`, `ip`, `cachingProxy`, `httpProxy`, `torProxy`) " .
		"VALUES('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')", 
		date("Y-m-d H-i-s"), 
		"pending", 
		mysqli_real_escape_string($dbLink, $userIGN), 
		mysqli_real_escape_string($dbLink, $userEmail), 
		mysqli_real_escape_string($dbLink, $userAge), 
		mysqli_real_escape_string($dbLink, $userLocation), 
		mysqli_real_escape_string($dbLink, $userHeardOf), 
		mysqli_real_escape_string($dbLink, $userHeardOf_other), 
		mysqli_real_escape_string($dbLink, $userLinks), 
		mysqli_real_escape_string($dbLink, $userReasons), 
		mysqli_real_escape_string($dbLink, $userIpAddress), 
		mysqli_real_escape_string($dbLink, $typicalProxy), 
		mysqli_real_escape_string($dbLink, $httpProxy), 
		mysqli_real_escape_string($dbLink, $torProxy)
		);

	return mysqli_query($dbLink, $query) !== false;
}


/* Get the number of applications
 */
function getApplicationCount( $dbLink )
{
	$query = "SELECT * FROM `applications` WHERE status='pending'";
	return numrows($dbLink, $query);
}


/* Get an application
 */
function getApplication( $dbLink, &$id, $dir )
{
	$id += $dir;
	if( $dir == -1 )
		$query = "SELECT * FROM `applications` WHERE `status`='pending' AND `id`<='$id' ORDER BY `id` DESC";
	else
		$query = "SELECT * FROM `applications` WHERE `status`='pending' AND `id`>='$id' ORDER BY `id` ASC";

	$rs = mysqli_query( $dbLink, $query );
	if( $rs === false )
		return false;

	$app = mysqli_fetch_assoc($rs);
	if( ! $app ) {
		if( $dir == -1 ) {
			$id = 2000000;
			$query = "SELECT * FROM `applications` WHERE `status`='pending' AND `id`<='$id' ORDER BY `id` DESC";
		}
		else {
			$id = 0;
			$query = "SELECT * FROM `applications` WHERE `status`='pending' AND `id`>='$id' ORDER BY `id` ASC";
		}
		$rs = mysqli_query( $dbLink, $query );
		if( $rs === false )
			return false;
		$app = mysqli_fetch_assoc($rs);
		if( ! $app )
			return false;
	}

	$app["hasHacked"] = hasHacked($dbLink, $app["ip"], $app["ign"]);

	mysqli_free_result( $rs );
	return $app;
}


/* Accept user
 * $id is the sql row id
 */
function userAccept( $id )
{
	getApplication();
	$recipient_email = $app['email'];
	$recipient_name = $app['ign'];
	mail($recipient_email, "MRT Application Approved!","Hello " . $recipient_name . ", and welcome to the MRT Server! Your application to become a member has been approved.
		You now have full building privileges on the server. Please remember to abide by all of the rules at all times, including those for riding the MRT. 
		You may lose building privileges, or be banned, for breaking them. While playing, we recommend you join our Mumble channel. Get Mumble at http://www.mumble.com and sign on to our server. The connection instructions 
		are located in Spawn Station; please ask a staffer for the password. 
		Please contact us at admin@minecartrapidtransit.net with any questions. Thanks, and have fun! --Frumple and chiefbozx");
}

/* Reject user
 * $id is the sql row id
 */
function userReject( $id )
{
}


/* Ban user
 * $id is the sql row id
 */
function userBan( $id )
{
}


