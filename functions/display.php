<?php
// NOTE:
// This is code I use in other projects.  I'll just copypaste
// it here so I don't have to retype the parts I need to use.
// -- CG
// Note: Added safePrint

// email.php
// Functions related to emails


// Return HTML for an email address in a way to fool harvesters
function getSafeEmail( $email )
{
	$atPos = strpos( $email, "@" );
	$name = substr( $email, 0, $atPos );
	$domain = substr( $email, $atPos+1 );

	$script = "document.write('<a href=\"mailto:$name' + '@' + '$domain\">$name' + '@' + '$domain</a>');";
	$noscript = "$name<img style='position: relative; top: .3em; height: 1.1em;' src='images/text_at.gif'/>$domain";

	return "<script language='javascript' type='text/javascript'>$script</script><noscript>$noscript</noscript>";
}

// Return an escaped string
function safePrint( $text )
{
	return htmlspecialchars($text);
}

?>
