<?php

namespace app\demo\controller;
use think\Controller;
use think\view;
class Index extends Controller
{
    protected $view=null;
    public function _initialize(){
      $this->view=new View();
    }

    public function index()
    {
        return $this->view->fetch();
    }


    public function redis(){
        $store=1000;
        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        $res=$redis->lLen('goods_store');
        echo $res."<br/>";
        $count=$store-$res;
        for($i=0;$i<$count;$i++){
            $redis->lPush('goods_store',1);
        }


        $redis->lPop('goods_store');
        echo $redis->lLen('goods_store');
    }
}
