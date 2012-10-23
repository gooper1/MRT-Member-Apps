<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php

	require_once( "config.php" );

	/* TODO: Change this*/
	$auth = "md5 of password";
	/* */

?>

<head>
	<title>MRT Server Member Application Review</title>

	<!-- Javascript -->
	<script language="javascript" type="text/javascript"
		src="js/display.js"></script>
	<script language="javascript" type="text/javascript"
		src="js/md5.js"></script>
	<script language="javascript" type="text/javascript">
		onResize = function() {
			fixContentHeight();
		}
		onLoad = function() {
			fixContentHeight();
		}
		onSubmit = function() {
			var pass = document.getElementById("password");
			var auth = document.getElementById("auth");
			auth.value = calcMD5(pass.value);
			document.getElementById("form").submit();
		}
	</script>
	<!-- -->


	<!-- CSS -->
	<link rel="stylesheet" type="text/css"
		href="style.css"/>
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
			<span>Password:</span><input id="password" type="password" onkeydown="if( event.keyCode == 13 ) onSubmit()"></input>
			<span class="button" onclick="onSubmit()">submit</span>
			<form id="form" action="review.php" method="post">
				<input type="hidden" id="auth" name="auth"></input>
			</form>
		</div>

		<div id="footer">
			Copyright MRT Server 2012
		</div>

	</div>
</body>

</html>
