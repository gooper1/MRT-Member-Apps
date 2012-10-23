<?php

// Functions for accessing the database


// Connect to the database
function DB_connect() {
	$dbhost = "localhost";
	$dbname = "mrt";
	$dbuser = "apply";
	$dbpass = "kalmarfriedchickens";

	$link = mysqli_connect( $dbhost, $dbuser, $dbpass );
	$db = mysqli_select_db( $link, $dbname );

	if( ! mysqli_set_charset($link, "utf8") )
		return false;

	return $link;
}


// Disconnect from the database
function DB_disconnect( $link ) {
	mysqli_close( $link );
}


// End PHP
?>
