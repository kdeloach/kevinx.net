<?php

	function accesslog($action)
	{
		global $sql;
		
		$user_id = getid();	
		$page = $_SERVER['REQUEST_URI'];
		$time = time();
	
		$res = $sql->query(
			 "INSERT INTO access_log "
			." (user_id,page,time,action)"
			." VALUES('$user_id','$page','$time','$action')"
		);
	}

	function dialog($str, $type=SUCCESS, $centered=false)
	{
		switch($type)
		{
			case SUCCESS:
				$id = 'success';
				break;
			case FAILURE:
				$id = 'error';
				break;
		}
		
		if($centered)
			$style = ' style="margin-left: auto; margin-right: auto;"';
		else
			$style = '';
		
		return '<div id="'. $id .'"'. $style .'>'. $str .' &nbsp; <img src="images/'. $id .'.png" width="17" height="17" /></div>';	
	}

	/*
	 * Salt PW Hashes Function
	 *
	 * Takes password as $pass and username as $salt
	 * To verify user, password AND name are required
	 *
	 * Previously, this would use random bits as salt
	 * and keep the generated bits at the beginning of the hash
	 *
	 * This works too though
	 *
	 */ 
	function encrypt($pass, $salt)//$salt=null)
	{
		/*if(!isset($salt))    
			$salt = md5(uniqid(rand(), true)); // generate unique identifier*/
		
		// only want the first 7 bits
		//$salt = substr($salt, 0, 7);            
		
		// newly encrypted pass
		//return $salt.'/'.md5($salt.$pass);
		
		return md5($salt.$pass);
	} 

	function salt($a, $b) {
		return md5($b . $a);
	}

	function get($str, $id=null)
	{
		global $sql;
		
		if(islogged() && empty($id))
			$id = getid();
		else if(!islogged() && empty($id))
			return false;
		
		$res = $sql->query("SELECT $str FROM users WHERE id='$id'");
		$tmp = $sql->fetch_assoc($res);
		
		if(isset($tmp[$str]))
			return $tmp[$str];
		else 
			return false;
	}

	/*
	 * November 24, 2005 04:23:45 PM 
	 *  previously, a query was executed whenever this function was called. now, all settings are
	 *  put into session cookies on login.  this increases speed but setting changes won't take effect
	 *  until a re-login occurs.
	 *
	 */
	function get_setting($name)
	{
		return (isset($_SESSION[$name]) ? $_SESSION[$name] : null);
	}

	function getid()
	{
		if(!islogged())	
			return false;
			
		return $_SESSION['user_id'];
	}

	// http://us2.php.net/microtime
	function getmicrotime()
	{
		list($usec, $sec) = explode(' ', microtime()); 
		return ((float)$usec + (float)$sec); 
	} 

	function islogged()
	{
		return isset($_SESSION['islogged']) && isset($_SESSION['user_id']);
	}

	function listdir($dir, $hide=array(
         'logout.php'
        ,'login.php'
        ,'restricted.php'
        ,'.DS_Store'
        ,'.htaccess'
        ,'style.css'
        ,'model.tmpl'
        ,'index.php'
    )) {
		$files = array();
		
		if(!is_dir($dir))
			return false;
		
		$dh = opendir($dir);
		
		// Read each file
		while($file = readdir($dh))
		{
			if(!in_array($file, $hide) && !is_dir($file))
				$files[] = $file;
		}
		
		closedir($dh); 
		
		return $files;
	}

	function parseurl($i)
	{
		$p = explode('/', $_GET['a']);
			
		if(isset($p[$i]))
			return $p[$i];
		else
			return false;
	}

	$n = 0; // Indent spaces
	//$line_count = 0;
	$stack = array(); // Tags stack
	
	// Print formatted HTML
	function println($str='', $nl=true)
	{
		global $stack, $n, $line_count;
		
		if($nl)
			$br = "\n";
		else
			$br = '';
		
		// what to return
		$ret = '';
		
		// In case some of the old html formatting is in the script...
		// UPDATE - nevermind, I might want custom formatting
		//$str = trim($str);
		
		// Amount of spaces in a tab
		$m = 2;
		
		// Pattern matches: <table> or </table> or <table border="0">
		// Pattern failures: <br /> or <!---->
		$pattern = "^<(/?)([a-z^/ ]{1,})( [a-z^/]{1,}=\".{1,}\")*>$";
		
		/*// Numbered Lines
		if(!isset($stack['script'.($n-1)]))
		{
			$ret .= '<!--'. sprintf('%04d', $line_count++) .'-->';
		}
		else
		{
			//    <!--   0000   -->
			$ret .= '    '.'    '.'   ';
			
			$line_count++;
		}*/
		
		// If $str is an open/close tag
		if(eregi($pattern, $str))
		{
			// Get tag name (<table border="1"> becomes table)
			$match = eregi_replace($pattern, '\2', $str);
			
			$type = eregi_replace($pattern, '\1', $str);
		
			// If tag was already opened...
			// $n is added to the end to give it a unique name
			// unique names are important to account for nested elements
			if(isset($stack[$match.($n-1)]) && $type == '/')
			{
				$ret .= str_repeat(' ', --$n*$m).$str;
				//$ret .= '<!-- '.$match.' [-]-->';
				$ret .= $br;
			
				// destroy
				unset($stack[$match.$n]);	
			}
			// If this is an open tag...
			else
			{
				// create
				$stack[$match.$n] = true;	
				
				$ret .= str_repeat(' ', $m*$n++).$str;
				//$ret .= '<!-- '.$match.' [+]-->';
				$ret .= $br;
			}
		}
		else
		{
			// append spaces at beginning of line
			$ret .= str_repeat(' ', $m*$n).$str.$br;
		}
		
		if(!$nl)
			return $ret;
		else
			echo $ret;
			
		return;
	}
	
	// examples:
	//echo scriptname(1, true);
	//echo scriptname(-1);
	function scriptname($n=0, $showfile=true)
	{
		$script = $_SERVER['SCRIPT_NAME'];
		$script = explode('/', $script);
		
		// If $n is too large, return the whole path
		if($n == -1)
			return scriptname(count($script)-1, $showfile);
	
		if($n == 0 && !$showfile)
			return '';
		else if($n == 0)
			return $script[count($script)-1];
		else if($n > 0)
			return $script[count($script)-$n-1].'/'.scriptname($n-1, $showfile);
	}
	
	function tag($str)
	{
		// lowercase it
		$str = strtolower($str);
		// replace spaces
		$str = str_replace(' ', '-', $str);	
		
		return $str;
	}

	function valid_email($email)
	{
		return eregi('^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$', $email);	
	}

	function verify($pg)
	{
		global $sql;
		
	  $id = getid();
	  $script = $pg;
	  
	  $res = $sql->query(
	  	 "SELECT 1"
	  	." FROM users,rangefile"
	  	." WHERE users.group_id=rangefile.group_id"
	  	." AND rangefile.file='$script'"
	  	." AND users.id='$id'"
	  );
	  
	  return !($sql->num_rows($res) > 0);
	}

?>