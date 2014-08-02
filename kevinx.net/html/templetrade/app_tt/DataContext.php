<?php

class DataContext
{
    static $instance;

    var $db;
    
    function __construct($dsn=null, $user=null, $pass=null)
    {
        if($dsn == null)
        {
            $config = Config::instance();
            
            $dsn = (string)$config->setting('database/@dsn');
            $user = (string)$config->setting('database/@user');
            $pass = (string)$config->setting('database/@pass');
        }
        
        $this->db = new PDO($dsn, $user, $pass);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    static function instance()
    {
        if(self::$instance == null)
        {
            $context = new DataContext();
            self::$instance = $context;
        }
        return self::$instance;
    }
    
    static function getContext()
    {
        return self::instance()->db;
    }
    
    function selectProduct($productID)
    {
        $stmt = $this->db->prepare(Queries::$SELECT_PRODUCT);
        $stmt->execute(array(
            ':productID' => $productID
        ));
        
        return $stmt;
    }
    
    function insertProduct($product)
    {
        $stmt = $this->db->prepare(Queries::$INSERT_PRODUCT);
        $stmt->execute(array(
            ':name' => $product->name,
            ':createdDate' => time(),
            ':updatedDate' => time(),
            ':isActive' => $product->isActive,
            ':isDeleted' => $product->isDeleted
        ));
        $product->id = $this->db->lastInsertId();

        $this->insertProductAttributes($product);
    }
    
    function insertProductAttributes($product)
    {
        if(count($product->attributes) == 0)
            return;
        
        $attrStmt = $this->db->prepare(Queries::$INSERT_PRODUCT_ATTRIBUTE);
        
        foreach($product->attributes as $attr)
        {
            $attrStmt->execute(array(
                ':name' => $attr->name,
                ':value' => $attr->value,
                ':productID' => $product->id
            ));
            $attr->id = $this->db->lastInsertId();
        }
    }

    function updateProduct($product)
    {
        $stmt = $this->db->prepare(Queries::$UPDATE_PRODUCT);
        $stmt->execute(array(
            ':id' => $product->id,
            ':name' => $product->name,
            ':updatedDate' => time(),
            ':isActive' => $product->isActive,
            ':isDeleted' => $product->isDeleted
        ));
        
        $attrStmt = $this->db->prepare(Queries::$DELETE_PRODUCT_ATTRIBUTES);
        $attrStmt->execute(array(
            ':productID' => $product->id
        ));
        
        $this->insertProductAttributes($product);
    }
}