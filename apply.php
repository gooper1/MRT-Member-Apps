<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php

	require_once( "functions/ip.php" );
	require_once( "config.php" );

	$textareaLength = $maxDataAccepted;
	$ipAddress = getUserIp();

?>

<head>
	<title>MRT Server Member Application</title>

	<!-- Javascript -->
	<script language="javascript" type="text/javascript"
		src="js/display.js"></script>
	<script language="javascript" type="text/javascript">
		updateCounter = function( textareaId, counterId ) {
			var textarea = document.getElementById(textareaId);
			var text = textarea.value;
			var counter = document.getElementById(counterId);
			var length = <?php echo $textareaLength; ?> - text.length;
			if( length < 0 ) {
				textarea.value = text.substr(0, <?php echo $textareaLength; ?>);
				length = 0;
			}
			counter.innerHTML = "Answer in <?php echo $textareaLength; ?> characters or less. -- ("
				+ length + " characters left)";
		}
		onResize = function() {
			fixContentHeight();
		}
		onLoad = function() {
			fixContentHeight();
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
			<form name="memberapp" action="submit.php" method="post">
				<table>
					<tr>
						<td class="question">
							Minecraft username:
						</td>
						<td class="answer">
							<input type="text" name="username"></input>
						</td>
					</tr>
					<tr>
						<td class="question">
							Email Address:
						</td>
						<td class="answer">
							<input type="text" name="email"></input>
						</td>
					</tr>
					<tr>
						<td class="question">
							Age:
						</td>
						<td class="answer">
							<input type="number" name="age" min="0" max="200"></input>
						</td>
					</tr>
					<tr>
						<td class="question">
							Location:<br /><small>This is collected for statistical and troubleshooting purposes only. Please enter your country of residence. If you live in the United States, please include the state.</small>
						</td>
						<td class="answer">
							<input type="text" name="location"></input>
						</td>
					</tr>
					<tr>
						<td class="question">
							How did you hear about the server?
						</td>
						<td class="answer">
							<input type="radio" name="heardof" value="frumple-yt">Frumple's YouTube channel, frumple1</input><br/>
							<input type="radio" name="heardof" value="chief-yt">Chief's YouTube channel, chiefbozx</input><br/>
							<input type="radio" name="heardof" value="friends">Word of Mouth (friends told you)</input><br/>
							<input type="radio" name="heardof" value="internet">The Internet (Google, Reddit, other sites)</input><br/>
							<input type="radio" name="heardof" value="other">Other: </input><input type="text" name="heardof_other" onfocus="memberapp.heardof[memberapp.heardof.length-1].checked='true'"></input>
						</td>
					</tr>
					<tr>
						<td class="question">
							Links to screenshots of videos of some of your creations:
						</td>
						<td class="answer">
							<textarea type="text" name="links"
								id="links"
								onchange="updateCounter('links', 'links_count')"
								onkeyup="updateCounter('links', 'links_count')"
								></textarea>
							<span id="links_count">Answer in <?php echo $textareaLength; ?> characters or less.</span>
						</td>
					</tr>
					<tr>
						<td class="question">
							Why should we accept your application?
						</td>
						<td class="answer">
							<textarea type="text" name="reasons" 
								id="reasons" 
								onchange="updateCounter('reasons', 'reasons_count')" 
								onkeyup="updateCounter('reasons', 'reasons_count')"
								></textarea>
							<span id="reasons_count">Answer in <?php echo $textareaLength; ?> characters or less.</span>
						</td>
					</tr>
					<tr>
						<td class="question">
							<b>The Rules Test:</b><br />Read over the <a href="http://www.minecartrapidtransit.net/rules">rules</a> and the <a href="http://www.minecartrapidtransit.net/rules">FAQ</a>. Then, check all of the true statements on the right. If there are any statements that are false, leave the corresponding checkboxes un-checked.
						</td>
						<td class="answer">
							<input type="checkbox" name="rules[]" value="1" />Griefing and trolling are not allowed.<br />
							<input type="checkbox" name="rules[]" value="2" />I can build in any town I want, even if I don't ask permission.<br />
							<input type="checkbox" name="rules[]" value="4" />I can politely ask any staff member to do some WorldEdit for me.<br />
							<input type="checkbox" name="rules[]" value="8" />I may not build pixel art.<br />
							<input type="checkbox" name="rules[]" value="16" />I can ask for a special tag, like "Super Member" or "Random Canadian", to be displayed next to my username in chat.<br />
							<input type="checkbox" name="rules[]" value="32" />I may not ask to become a conductor, moderator, administrator, or OP.<br />
							<input type="checkbox" name="rules[]" value="64" />I can ask for teleports to any other player on the server.<br />
							<input type="checkbox" name="rules[]" value="128" />Advertising other Minecraft servers is not allowed.<br />
							<input type="checkbox" name="rules[]" value="256" />I will respect the actions and creations of other members, and I consent to any punishment I may receive for breaking the rules.<br />
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<input type="hidden" name="ipAddress" value="<?php echo $ipAddress; ?>"></input>
							<input type="submit" value="submit"></input>
						</td>
					</tr>
				</table>
			</form>
		</div>

		<div id="footer">
			Copyright MRT Server 2012
		</div>

	</div>
</body>

</html>
