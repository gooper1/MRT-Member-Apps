<?php

// Included files
require_once("functions/database.php");
require_once("functions/user.php");
require_once("config.php");


// Get parameters
$action = $_POST["action"];
$id = $_POST["id"];
$auth = $_POST["auth"];

if( $auth != $authentification )
	die( "No permission!" );


// Take action
if( $action == "ban" )
	userBan($id);
else if( $action == "accept" )
	userAccept($id);
else if( $action == "reject" )
	userReject($id);


// Remove from list of applicants
$link = DB_connect();
$query = "UPDATE `applications`" .
	"SET `status`='$action'" .
	"WHERE `id`='$id'";
mysqli_query($link, $query);
DB_disconnect($link);

?>
