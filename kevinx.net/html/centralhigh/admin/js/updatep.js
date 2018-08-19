
	// 1 is the id for the 'default' preset
	var prev = 1;
	
	// show/hide privy table
	function updatep(id)
	{
		
		// show current table of selection
		var eolp = document.getElementById(id);
		eolp.style.display = "table";
	
		// hide previous table
		//if(prev != null)
		//{
			var urql = document.getElementById(prev);
			urql.style.display = "none";
		//}
		
		prev = id;
	}