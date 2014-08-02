<?php

class NavigationUserControl extends UserControl
{
	var $items;
	
	function __construct()
	{
		parent::__construct();
	}
	
	function onLoad()
	{
		parent::onLoad();
		$this->items[] = 'A';
		$this->items[] = 'B';
		$this->items[] = 'C';
	}
}