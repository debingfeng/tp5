<?php
namespace app\test\controller;

use think\Controller;
use think\Request;

class Index extends Controller
{


    public function index()
    {
        return $this->fetch('index');
    }

    public function upload1()
    {
//        if ($_FILES["file"]["error"] > 0) {
//            return json(array('code'=>'101','desc'=>'文件上传出错'));
//        } else {
//            move_uploaded_file($_FILES["file"]["tmp_name"],
//                "upload/" . $_FILES["file"]["name"].time());
//            return  json(array('code'=>'200','desc'=>'上传成功'));

        $file = request()->file('uploadfile');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        if ($info) {
            return  json(array('code'=>'200','desc'=>'上传成功'));
            // 成功上传后 获取上传信息
//            // 输出 jpg
//            echo $info->getExtension();
//            // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
//            echo $info->getSaveName();
//            // 输出 42a79759f284b767dfcb2a0197904287.jpg
//            echo $info->getFilename();
        } else {
            // 上传失败获取错误信息
            return json(array('code' => 102, 'desc' => 'upload fail!', 'data' => $file->getError()));
        }
    }

    public function upload()
    {

        // 获取表单上传文件 例如上传了001.jpg

        $file = request()->file('uploadfile');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        if ($info) {

            $imgInfo = array(
                "ext"=>$info->getExtension(),
                "saveName"=>$info->getSaveName(),
                "fileName"=>$info->getFilename(),
            );

            return  json(array('code'=>'200','desc'=>'上传成功','data'=>$imgInfo));

        } else {

            // 上传失败获取错误信息
            return json(array('code' => 102, 'desc' => 'upload fail!', 'data' => $file->getError()));

        }
    }
}
