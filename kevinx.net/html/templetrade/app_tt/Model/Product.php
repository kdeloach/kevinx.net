<?php

class Model_Product
{
    var $id;
    var $name;
    var $createdDate;
    var $updatedDate;
    var $isActive = 0;
    var $isDeleted = 0;
    var $attributes = array();

    static function load($id)
    {
        $product = new Model_Product();
        
        $db = DataContext::instance();
        $stmt = $db->selectProduct($id);
        
        $productRow = $stmt->fetch();
        Helper_Reflection::copyProperties($productRow, $product);
        
        while($attrRow = $stmt->fetch())
        {
            $attr = new Model_ProductAttribute();
            Helper_Reflection::copyProperties($attrRow, $attr);
            $product->attributes[] = $attr;
        }
        
        return $product;
    }
    
    function save()
    {
        if($this->id == null)
            $this->insert();
        else
            $this->update();
    }
    
    function insert()
    {
        if($this->id != null)
            return;
        $db = DataContext::instance();
        $db->insertProduct($this);
    }
    
    function update()
    {
        $db = DataContext::instance();
        $db->updateProduct($this);
    }
}