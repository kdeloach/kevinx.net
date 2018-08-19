<?php

	// Authentication
	// October 01, 2005 01:28:59 PM - finished first draft
	// October 01, 2005 01:34:06 PM - added referrer code
	// October 26, 2005 11:42:30 PM - added verify code

	// If not logged in redirect to login page
	if(!islogged())
	{
		$ref = scriptname();
		
		header('Location: login.php?ref='. $ref);
		exit;
	}
	else if(scriptname() != 'restricted.php' && !verify(scriptname()))
	{
	  header('Location: restricted.php');
	  exit;
	}

?>