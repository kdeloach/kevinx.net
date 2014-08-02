<?php

class Queries
{
    // Work-around for inability to request multiple result sets
    static $SELECT_PRODUCT = <<<sql
        select id, name, null as value, createdDate, updatedDate, isActive, isDeleted, null
        from product 
        where id = :productID
        
        union
        
        select id, name, value, null, null, null, null, productID
        from product_attribute 
        where productId = :productID
sql;

    static $INSERT_PRODUCT = <<<sql
        insert into product (name, createdDate, updatedDate, isActive, isDeleted)
        values(:name, :createdDate, :updatedDate, :isActive, :isDeleted)
sql;

    static $UPDATE_PRODUCT = <<<sql
        update product set
            name = :name, 
            updatedDate = :updatedDate,
            isActive = :isActive,
            isDeleted = :isDeleted
        where id = :id
sql;

    static $INSERT_PRODUCT_ATTRIBUTE = <<<sql
        insert into product_attribute (productID, name, value)
        values(:productID, :name, :value)
sql;

    static $DELETE_PRODUCT_ATTRIBUTES = <<<sql
        delete from product_attribute where productID = :productID
sql;
}