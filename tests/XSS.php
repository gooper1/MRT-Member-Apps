<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title>MRT Server Member Application</title>

	<!-- Javascript -->
<body>
	<form name="memberapp" action="../submit.php" method="post">
		<table>
			<tr>
				<td class="question">
					Minecraft IGN:
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
					Location:
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
					<input type="radio" name="heardof" value="no">No.</input><br/>
					<input type="radio" name="heardof" value="yes">Yes!</input><br/>
					<input type="radio" name="heardof" value="maybe"></input><br/>
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
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<input type="hidden" name="ipAddress" value="127.0.0.1"></input>
					<input type="submit" value="submit"></input>
				</td>
			</tr>
		</table>
	</form>
</body>

</html>
