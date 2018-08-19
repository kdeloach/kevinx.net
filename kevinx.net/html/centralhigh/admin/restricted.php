<?php

	// Restricted!

	// template header
	require 'include/admin-header.php';
	
	// Verify logged in
	require ROOT.PATH_CMS.'include/auth.php';
	/////////////////////////////////////

	println(dialog('Restricted', FAILURE));

	/////////////////////////////////////////
	// template footer
	require 'include/admin-footer.php';
	
?>