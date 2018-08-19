<?php

	// template header
	require 'include/admin-header.php';
	
	// Verify logged in
	require ROOT.PATH_CMS.'include/auth.php';
	/////////////////////////////////////
	$retval=1;
	if (isset($_REQUEST['cryptpass'])) {
		$pass=$_REQUEST['cryptpass'];
//		$pass=$argv[1];
		exec('MCRYPT_KEY="'.$pass.'" mdecrypt < passwords.txt.nc', $output, $retval);
		if ($retval==0) {
			foreach ($output as $line) {
				println($line."<br>\n");
			}
		} else {
			println('Error decrypting passwords file.');
		}
	}
	if ($retval!=0) {
		println('<form acton="manage-passwords.php" method="POST">Password: <input type="password" name="cryptpass" /><input type="submit" /></form>'."\n");
	}
	
	//////////////////
	// template footer
	require 'include/admin-footer.php';
	
?>
