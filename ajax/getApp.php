<?php echo "<?xml version='1.0' encoding='UTF-8' ?>"; ?>
<application>
	<?php

		// Included files
		require_once( "../functions/database.php" );
		require_once( "../functions/display.php" );
		require_once( "../functions/user.php" );
		require_once( "../config.php" );

		// Get parameters
		$id = $_POST["appId"];
		$auth = $_POST["auth"];
		$dir = $_POST["dir"];

		// Get application
		$link = DB_connect();
		if( ! $link )
			die( "Unable to connect to database." );
		$app = getApplication( $link, $id, $dir );
		DB_disconnect( $link );

	?>
	<?php if( $auth != $authentification ): ?>
		<response>refused</response>
	<?php elseif( $app === false ): ?>
		<response>empty</response>
	<?php else: ?>
		<response>okay</response>
		<id><?php echo safePrint(safePrint($app["id"])); ?></id>
		<datetime><?php echo safePrint(safePrint($app["datetime"])); ?></datetime>
		<ign><?php echo safePrint(safePrint($app["ign"])); ?></ign>
		<email><?php echo safePrint(safePrint($app["email"])); ?></email>
		<age><?php echo safePrint(safePrint($app["age"])); ?></age>
		<location><?php echo safePrint(safePrint($app["location"])); ?></location>
		<heardof><?php echo safePrint(safePrint($app["heardof"])); ?></heardof>
		<heardof_other><?php echo safePrint(safePrint($app["heardof_other"])); ?></heardof_other>
		<links><?php echo safePrint(safePrint($app["links"])); ?></links>
		<reasons><?php echo safePrint(safePrint($app["reasons"])); ?></reasons>
		<ip><?php echo safePrint(safePrint($app["ip"])); ?></ip>
		<hasHacked><?php echo safePrint(safePrint($app["hasHacked"])); ?></hasHacked>
		<httpProxy><?php echo safePrint(safePrint($app["httpProxy"])); ?></httpProxy>
		<torProxy><?php echo safePrint(safePrint($app["torProxy"])); ?></torProxy>
		<cachingProxy><?php echo safePrint(safePrint($app["cachingProxy"])); ?></cachingProxy>
	<?php endif; ?>
</application>
