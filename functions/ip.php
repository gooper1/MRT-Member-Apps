<?php


	/* Get ip of user
	 */
	function getUserIp()
	{
		return $_SERVER["REMOTE_ADDR"];
	}


	// Proxy detecting functions

	/* Detect TOR proxies
	Reliability: High
	Usefulness: High - Determine if a proxy is being used to connect 
		to the server (after /whois for an ip address) or determine 
		if a proxy is being used while filling out a member app.
	Recommendation: Use on member app and use on questionable users.
	Bug: Lists MRT server as TOR proxy.
	*/
	function detectTorProxy( $userIp, $serverPort ) {
		global $serverIp;
		$url = "https://check.torproject.org/cgi-bin/TorBulkExitList.py"
			. "?ip=" . $serverIp . "&port=" . $serverPort;
		$file = file_get_contents( $url );
		return strpos( $file, $userIp ) !== false;
	}

	/* Detect HTTP proxies
	Reliability: Low - May give false positives
	Usefulness: Low - Isn't likely to be used to connect
	Recommendation: Don't use.
	*/
	function detectHttpProxy( $ipAddress ) {
		$ports = array(80, 81, 553, 554, 1080, 3128, 4480, 6588, 8000, 8080);
		foreach( $ports as $port ) {
			if( @fsockopen($ipAddress, $port, $errno, $errstr, 30) )
				return true;
		}
		return false;
	}

	/* Detect typical proxies
	Reliability: High - However, these proxies are not for hacking.
	Usefulness: Medium - Can determine if this page is accessed by 
		a non-anonymizer proxy, and returns actual IP address.
	Recommendation: Use on member application in conjunction with 
		the detectTORProxy function.
	Returns: false for no proxy; originating ip if proxy
	*/
	function detectTypicalProxy() {
		$proxyHeaders = array(
			"CLIENT_IP", 
			"FORWARDED", 
			"FORWARDED_FOR_IP", 
			"HTTP_CLIENT_IP", 
			"HTTP_FORWARDED", 
			"HTTP_FORWARDED_FOR", 
			"HTTP_FORWARDED_FOR_IP", 
			"HTTP_PROXY_CONNECTION", 
			"HTTP_VIA", 
			"HTTP_X_FORWARDED_FOR", 
			"HTTP_X_FORWARDED", 
			"VIA", 
			"X_FORWARDED_FOR", 
			"X_FORWARDED"
		);
		foreach( $proxyHeaders as $header ) {
			if( isset($_SERVER[$header]) ) {
				$trace = explode( ",", $_SERVER[$header] );
				return $trace[0];
			}
		}
		return false;
	}

?>
