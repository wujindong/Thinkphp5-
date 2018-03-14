<?php

namespace app\demo\controller;
use think\Db;
use think\Loader;
class Down
{
    /**
     * 将数据库数据导出为excel文件
     */
    function downLoadExcle()
    {
        $user = Db::query("select * from user");
        Loader::import('PHPExcel.PHPExcel');
        Loader::import('PHPExcel.PHPExcel.IOFactory.PHPExcel_IOFactory');
        Loader::import('PHPExcel.PHPExcel.Reader.Excel2007');
        $objPHPExcel = new \PHPExcel();

        //设置每列的标题
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'ID')
            ->setCellValue('B1', '用户名')
            ->setCellValue('C1', '密码')
            ->setCellValue('D1', '标志');

        //存取数据  这边是关键
        foreach ($user as $k => $v) {
            $num = $k + 2;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $num, $v['id'])
                ->setCellValue('B' . $num, $v['username'])
                ->setCellValue('C' . $num, $v['password'])
                ->setCellValue('D' . $num, $v['right']);
        }
        //设置工作表标题
        $objPHPExcel->getActiveSheet()->setTitle('我的文档');

        //设置列的宽度
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=用户信息.xlsx");//设置文件标题
        header('Cache-Control: max-age=0');
        $objWriter->save('php://output');
        exit;
    }


    /**
     * 将excel文件导入数据库
     */
    function uploadExcel($filepathname)
    {
        $file_path =$filepathname;
        $file_path = iconv('utf-8', 'gbk', $file_path);
        Loader::import('PHPExcel.PHPExcel.Reader.Excel2007');
        Loader::import('PHPExcel.PHPExcel.Reader.Excel5');
        $PHPReader = new \PHPExcel_Reader_Excel2007();
        if (!$PHPReader->canRead($file_path)) {
            $PHPReader = new \PHPExcel_Reader_Excel5();
            if (!$PHPReader->canRead($file_path)) {
                return;
            }
        }
        $objPHPExcel = $PHPReader->load($file_path, $encode = 'utf-8');
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();//获取总行数
        for ($i = 2; $i <= $highestRow; $i++) {
            $data['username'] = $objPHPExcel->getActiveSheet()->getCell("B" . $i)->getValue();
            $data['password'] = $objPHPExcel->getActiveSheet()->getCell("C" . $i)->getValue();
            $data['right'] = $objPHPExcel->getActiveSheet()->getCell("D" . $i)->getValue();
            $allData[] = $data;
        }

        //将文件写入数据库
        foreach ($allData as $value) {
            Db::table('user')->insert($value);
        }
    }

    /**
     * 文件上传
     */
    function uploadFile()
    {
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('image');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->validate(['ext'=>'xlsx,xls,cvs'])->move(ROOT_PATH . 'public' . DS . 'uploads');
        if ($info) {
//                echo $info->getPathname(); 获取文件路径
                //将文件写入数据库
                $this->uploadExcel($info->getPathname());
        } else {
            // 上传失败获取错误信息
            echo $file->getError();
        }

    }
}