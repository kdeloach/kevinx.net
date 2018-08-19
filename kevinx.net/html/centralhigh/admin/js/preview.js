<!--

	function previewHTML(form)
	{
		submitForm();
		
		var inf = form.content.value;
		
		win = window.open('empty.txt', 'preview', 'width=400,height=400,resizable=1,status=1,scrollbars=1,titlebar=1');
		//win = window.open("", '_blank', 'toolbar1,status=1');
		
		doc = win.document;
		
		win.document.write(inf);
		win.document.close();
	}

//-->