<?php

namespace app\demo\controller;
use Endroid\QrCode\QrCode;
class Qcode
{
    public function createCode(){

        $logo=ROOT_PATH.'public/static/images/log.jpg';
        $qrCode = new QrCode('Life is too short to be generating QR codes');
        //设置前景色
        $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' =>0, 'a' => 0]);
        //设置背景色
        $qrCode->setBackgroundColor(['r' => 250, 'g' => 255, 'b' => 255, 'a' => 10]);
        //设置二维码大小
        $qrCode->setSize(200);
        $qrCode->setPadding(20);
        //添加logo
        $qrCode->setLogo($logo);
        //设置logo大小
        $qrCode->setLogoSize(40);
        $qrCode->setLabel("HelloWorld");
        $qrCode->setLabelFontSize(14);
        $qrCode->setLabelHalign(100);
        //绘制二维码边框
        // $qrCode->setDrawBorder(true);
        //获取二维码数据
        $img= $qrCode->getDataUri();
        //输出二维码
        echo "<img src='$img' />";
    }
}