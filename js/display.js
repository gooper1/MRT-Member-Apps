// NOTE:
// This is code I used in other projects.  I'll just copypaste
// it here so I don't have to retype the parts I need to use.
// -- CG

// display.js
// Functions changing stuff that's displayed


// Globals
var busytimer;
var content_origHeight;
var id_counter = 0;


// Make an element busy
makeBusy = function( elem ) {
	clearTimeout( busytimer );
	elem.innerHTML = 
		"<br/><center><img style='width: 2in; height: 0.18in;' src='images/busy.gif'/></center>";
	var html = elem.innerHTML;

	busytimer = setTimeout(

		function() {
			if( elem.innerHTML != html )
				return;
			elem.innerHTML = "<br/><center>"
				+ "<div class='error'>Sorry for the wait.<br/>I will continue trying to load the content.</div><br/>"
				+ "<img style='width: 2in; height: 0.18in;' src='images/busy.gif'/></center>";
			var html2 = elem.innerHTML;

			busyTimer = setTimeout(
				function() {
					if( elem.innerHTML != html2 )
						return;
					elem.innerHTML = "<br/><center>"
						+ "<div class='error'>Sorry for the wait.<br/>I will continue trying to load the content.<br/>Possibly <a href='javascript:location.reload()'>a reload</a> may be necessary.</div><br/>"
						+ "<img style='width: 2in; height: 0.18in;' src='images/busy.gif'/></center>";
				}, 
				1000 * 6
			);
		}, 

		1000 * 3

	);
}


// Wait for the DOM to be ready
on_DOM_loaded = function( func )
{
	// THIS is why I don't like web dev
	// Thank you for the code, 
	// http://www.javascriptkit.com/dhtmltutors/domready.shtml

	if( document.addEventListener ) {
		document.addEventListener(
			"DOMContentLoaded", 
			func, 
			false
		);
	}
	else if( document.add && ! window.opera ) {
		document.write( 'script type="text/javascript" '
			+ 'id="dom_loaded_' + id_counter + '\"'
			+ ' defer="defer"'
			+ ' src="javascript:void(0)"><\/script>' );
		var loadtag = document.getElementById(
			"dom_loaded_" + id_counter );
		loadtag.onreadystatechange = function() {
			if( this.readyState == "complete" ) {
				func();
			}
		}
		++ id_counter;
	}
	else if(/Safari/i.test(navigator.userAgent)) {
		var _timer = setInterval( function() {
			if(/loaded|complete/.test(document.readyState)) {
				clearInterval(_timer);
				func();
			}
		} );
	}
	else {
		throw "UNSUPPORTED BROWSER";
	}
}


// Get the viewport height
getViewportHeight = function() {
	return window.innerHeight?
		window.innerHeight :
		-1;
}

// Fix content div height
fixContentHeight = function() {
	var content = document.getElementById( "contents" );
	var header = document.getElementById( "header" );
	var footer = document.getElementById( "footer" );

	var height = getViewportHeight()
		- header.offsetHeight - footer.offsetHeight
		- 0.40 * header.offsetHeight - 3;
	if( height < content_origHeight )
		height = content_origHeight;
	content.style.minHeight = height + "px";
}


// On DOM Loaded
on_DOM_loaded( function() {
	content_origHeight = document.getElementById( "contents" )
		.offsetHeight;
	fixContentHeight();
} );



