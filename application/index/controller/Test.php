<?php
namespace app\index\controller;
use app\index\People;

class Test{
    public function test(){
        echo "我是继承来的";
        $people= new People();
        echo $people->say();
    }
}