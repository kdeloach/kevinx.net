<?php

	// Admin Sandbox
	// October 12, 2005 10:10:56 PM - created this instead of going to school
	// October 12, 2005 11:32:10 PM - this can be developed further in the future...

	// template header
	require 'include/admin-header.php';
	
	// Verify logged in
	require ROOT.PATH_CMS.'include/auth.php';
	/////////////////////////////////////
	
	println('<script type="text/javascript" src="bin/preview.js"></script>');
	
	println('<p>');
	println('The Sandbox does not affect the site or the CMS;');
	println(' Use it to generate HTML, test out your own HTML, or just to experiment with RTE (Rich Text Editor) below.');
	println('</p>');

	// Post news form
	println('<form method="post" action="" onsubmit="return submitForm();">');
	
		// Rich text editor
		println('<script type="text/javascript">');
		println('<!--');
		//Usage: writeRichText(fieldname, html, width, height, buttons, readOnly)
		println('writeRichText(\'content\', \'\', 400, 200, true, false);');
		println('//-->');
		println('</script>');
	
	println('<p><input type="button" name="preview" value="Preview" onclick="previewHTML(this.form);" /></p>');
	//println('<p><input type="submit" name="view" value="View Output" /></p>');
	println('</form>');

	/////////////////////////////////////////
	// template footer
	require 'include/admin-footer.php';
	
?>