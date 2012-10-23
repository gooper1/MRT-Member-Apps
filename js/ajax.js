// File pulled from another one of my projects
// -- CG

// I don't like the long XML syntax, so...
getChildVal = function( parent, child ) {
	var c = parent.getElementsByTagName(child)[0].firstChild;
	if( c )
		return c.nodeValue;
	else
		return "";
}


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


// Do an ajax get call
doAjaxGet = function( target, data, callback ) {
	var ajax = createAjaxObject();
	if( ! ajax ) {
		callback( null );
		return;
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

	ajax.open( "GET", target + "?" + data, true );

	ajax.overrideMimeType( "text/xml; charset=utf-8" );

	ajax.send( null );

	return ajax;
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


