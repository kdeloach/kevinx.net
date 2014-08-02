<?php

class ButtonUserControl extends UserControl
{
    var $text;
    var $cssClass;
    
    function __construct($text)
    {
        parent::__construct();
        $this->text = $text;
    }
}