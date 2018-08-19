<?php
	
	println();
	println('</div>');
	println('</td>');
	println('</tr>');
	println('</table>');
    
	///////////////////////////////

	// Copyright
	//println();
	//println('<div id="copyright">&copy; '. date('Y') .' WEB<em>Central</em></div>');
	//println();
		
	/////////////////////////////////
	
	println('</body>');
	println('</html>');
	
	//////////////////////////////////

	// End timer - get time in nanoseconds as float
	$GLOBALS['microtime_end'] = getmicrotime(true);
	
	// calculate execution time
	$exec_time = $GLOBALS['microtime_end'] - $GLOBALS['microtime_start'];
	
	// output
	println('<!-- executed in '. sprintf('%1.6f', $exec_time) .' seconds -->');
	println('<!-- '.$sql->query_count.' queries -->');
	
	//////////////////////////////////
	// Print output
	
	$str = ob_get_contents();
	
	ob_end_clean();
	
	echo $str;
	
?>
