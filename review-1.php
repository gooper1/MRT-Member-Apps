<?php

	require_once( "config.php" );

	$auth = $_POST["auth"];
?><?php if($auth == $authentification): ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title>MRT Server Member Application Review</title>

	<!-- Javascript -->
	<script language="javascript" type="text/javascript"
		src="js/display.js"></script>
	<script language="javascript" type="text/javascript"
		src="js/ajax.js"></script>
	<script language="javascript" type="text/javascript">

		// Variables
		var currentApp; // Id of current app

		// On ajax error or error from called page
		getApp_error = function() {
			document.getElementById("userinfo")
				.innerHTML = "<div class='error'>An error occured while retrieving the user's application.</div>";
		}

		// On ajax success
		getApp_success = function( xml ) {
			var rs = getChildVal(xml, "response");
			if( rs == "empty" ) {
				document.getElementById("userinfo")
					.innerHTML = "<div class='error'>There are no pending member applications at this time.</div>";
				setTimeout(function(){getApp(0)}, <?php echo $refreshTime; ?>*1000);
			}
			else if( rs == "refused" ) {
				//TODO: Go to log-in
			}
			else if( rs == "okay" ) {
				currentApp = parseInt(getChildVal(xml, "id"));
				var ign = getChildVal(xml, "ign");
				var email = getChildVal(xml, "email");
				var age = parseInt(getChildVal(xml, "age"));
				var location = getChildVal(xml, "location");
				var heardof = getChildVal(xml, "heardof");
				var heardof_other = getChildVal(xml, "heardof_other");
				if( heardof == "other" )
					heardof = "Other: " + heardof_other;
				var links = getChildVal(xml, "links");
				var reasons = getChildVal(xml, "reasons");
				var ip = getChildVal(xml, "ip");
				var hasHacked = getChildVal(xml, "hasHacked");
				var httpProxy = getChildVal(xml, "httpProxy");
				var torProxy = getChildVal(xml, "torProxy");
				var cachingProxy = getChildVal(xml, "cachingProxy");
				var proxy = "None detected";
				if( torProxy )
					proxy = "TOR proxy from " + ip;
				else if( cachingProxy )
					proxy = "Caching proxy from " + ip + " (traced back to " + cachingProxy + ")";
				else if( httpProxy )
					proxy = "Maybe http proxy from " + ip;
				document.getElementById("userinfo").innerHTML =
					"<table>" +
					"<tr><td>IGN</td><td class='right'>" + ign + "</td></tr>" +
					"<tr><td>Age</td><td class='right'>" + age + "</td></tr>" +
					"<tr><td>Email</td><td class='right'>" + email + "</td></tr>" +
					"<tr><td>Location</td><td class='right'>" + location + "</td></tr>" +
					"<tr><td>Heard of</td><td class='right'><textarea readonly='readonly'>" + heardof + "</textarea></td></tr>" +
					"<tr><td>Links</td><td class='right'><textarea readonly='readonly'>" + links + "</textarea></td></tr>" +
					"<tr><td>Reasons</td><td class='right'><textarea readonly='readonly'>" + reasons + "</textarea></td></tr>" +
					"<tr><td>Hack attempt detected?</td><td class='right'>" + (hasHacked?"yes":"no") + "</td></tr>" +
					"<tr><td>Proxy?</td><td class='right'>" + proxy + "</td></tr>" +
					"</table>";
			}
			fixContentHeight();
		}

		// Update the view to show the current application
		getApp = function( direction ) {
			doAjaxPost(
				"ajax/getApp.php",
				"appId=" + currentApp + "&dir=" + direction + "&auth=<?php echo $auth; ?>", 
				function( ajax ) {
					if( ! ajax )
						getApp_error();
					else if( ajax.readyState==4 && ajax.status==200 )
						getApp_success( ajax.responseXML );
				} );
		}

		// Events
		onNext = function() {
			getApp( 1 );
		}
		onPrev = function() {
			getApp( -1 );
		}
		onAccept = function() {
			makeBusy( document.getElementById("userinfo") );
			doAjaxPost(
				"review_action.php", 
				"auth=<?php echo $auth;?>&action=accept&id=" + currentApp, 
				function( ajax ) {
					onNext();
				} );
		}
		onReject = function() {
			makeBusy( document.getElementById("userinfo") );
			doAjaxPost(
				"review_action.php", 
				"auth=<?php echo $auth;?>&action=reject&id=" + currentApp, 
				function( ajax ) {
					onNext();
				} );
		}
		onBan = function() {
			makeBusy( document.getElementById("userinfo") );
			doAjaxPost(
				"review_action.php", 
				"auth=<?php echo $auth;?>&action=ban&id=" + currentApp, 
				function( ajax ) {
					onNext();
				} );
		}
		onResize = function() {
			fixContentHeight();
		}
		onLoad = function() {
			makeBusy( document.getElementById("userinfo") );
			currentApp = 0;
			getApp( 0 );
		}

	</script>
	<!-- -->


	<!-- CSS -->
	<link rel="stylesheet" type="text/css"
		href="style.css"/>
	<link rel="stylesheet" type="text/css"
		href="review.css"/>
	<!--[if !IE 7]>
		<style type="text/css">
			#container {
				display: table;
				height: 100%;
			}
		</style>
	<![endif]-->
	<!-- -->
</head>

<body onresize="onResize()" onload="onLoad()">
	<div id="container">

		<div id="header"><div id="headerContents">
		</div></div>

		<div id="contents">
		<div id="panel_border">
		<div id="panel">
			<span id="nav_left" class="button" onclick="onPrev()"></span>
			<span id="nav_right" class="button" onclick="onNext()"></span>
			<div id="userinfo">
				<noscript>
					Please enable JavaScript.  It is required for Ajax calls.
				</noscript>
			</div>
			<span id="action_accept" class="button" onclick="onAccept()">Accept</span>
			<span id="action_reject" class="button" onclick="onReject()">Reject</span>
			<span id="action_ban" class="button" onclick="onBan()">Ban</span>
		</div>
		</div>
		</div>

		<div id="footer">
			Copyright MRT Server 2012
		</div>

	</div>
</body>

</html>
<?php else: ?><?php require_once("login.php");?>
<?php endif; ?>
