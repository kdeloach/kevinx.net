<?php

class IndexController extends MasterController
{
    function index()
    {
        $viewArgs = array();
        $viewArgs = array_merge($viewArgs, $this->masterViewArgs());
        echo $this->render('index', $viewArgs);
    }
    
    function test()
    {
        $accessKey = '';
        $secretKey = '';
        
        $amazonEcs = new AmazonECS($accessKey, $secretKey);
        $response = $amazonEcs->responseGroup('Large,Images')->category('Books')->search('Algorithms in a Nutshell [Paperback]');
        print_r($response);
        
        return $this->index();
    }
    
    function add()
    {
        if(isset($_POST['submit']))
        {
            $p = new Model_Product();
            $p->name = $_POST['name'];
            
            foreach($_POST['key'] as $i => $k)
            {
                if(!empty($k))
                {
                    $v = $_POST['val'][$i];
                    $attr = new Model_ProductAttribute();
                    $attr->name = $k;
                    $attr->value = $v;
                    $p->attributes[] = $attr;
                }
            }
            
            $p->createdDate = time();
            $p->updatedDate = time();
            $p->save();
        }
        
        $viewArgs = array(
        );
        $viewArgs = array_merge($viewArgs, $this->masterViewArgs());
        echo $this->render('add', $viewArgs);
    }
    
    function browse($filter=null)
    {
        if($filter == null)
            return $this->browseAll();
            
        $viewArgs = array(
            'filter' => $filter
        );
        $viewArgs = array_merge($viewArgs, $this->masterViewArgs());
        echo $this->render('browse', $viewArgs);
    }
    
    function browseAll()
    {
        $viewArgs = array(
        );
        $viewArgs = array_merge($viewArgs, $this->masterViewArgs());
        echo $this->render('browse', $viewArgs);
    }
    
    function detail($bookid)
    {
        $btnContactSeller = new ButtonUserControl('Contact Seller');
        $btnContactSeller->cssClass = 'contactSeller';
        
        $btnAlert = new ButtonUserControl('Alert When Available');       
        $btnAlert->cssClass = 'alert';
    
        $viewArgs = array(
            'btnContactSeller' => $btnContactSeller,
            'btnAlert' => $btnAlert
        );
        $viewArgs = array_merge($viewArgs, $this->masterViewArgs());
        echo $this->render('detail', $viewArgs);
    }
}