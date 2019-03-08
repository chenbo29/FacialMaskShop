<?php

namespace app\admin\controller;

use think\Page;
use think\Db;


class Distribut extends Base {

    public function goods_list(){
       
     
        return $this->fetch();
    }

    public function distributor_list()
    {
      
        return $this->fetch();
    }
    
    public function tree()
    {
      
        return $this->fetch();
    }
    

    public function grade_list()
    {
      
        return $this->fetch();
    }
    

    public function rebate_log()
    {
      
        return $this->fetch();
    }
    
    
    
    
   
    
}