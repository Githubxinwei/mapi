<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/23 0023
 * Time: 下午 6:00
 */

namespace backend\modules\v1\models;

use common\models\Qiniu;
use yii\base\Model;

class Func extends Model
{
    /**
     * 后台 - 上传图片 - 公共调用七牛类
     * @return string
     * @author 黄鹏举
     * @create 2017-6-24
     * @param
     */
    public static function uploadImage()
    {
        if (($_FILES["file"]["type"] == "image/gif")
            || ($_FILES["file"]["type"] == "image/jpeg")
            || ($_FILES["file"]["type"] == "image/png")
            || ($_FILES["file"]["type"] == "image/pjpeg")
        ) {
            if ($_FILES["file"]["error"] > 0) {
//                return $_FILES["file"]["error"];                                         // 错误代码
                return ['code' => 0, 'status' => 'error', 'message' =>$_FILES["file"]["error"]];
            } else {

                $fileName = $_FILES['file']['name'];                                       // 得到文件全名
                $dotArray = explode('.', $fileName);                                       // 以.分割字符串，得到数组
                $type = end($dotArray);                                                    // 得到最后一个元素：文件后缀
                $path = md5(uniqid(rand())) . '.' . $type;                                 // 产生随机唯一的名字
                $byte = $_FILES["file"]["tmp_name"];                                       //图片文件
//                move_uploaded_file($byte,$path);         //参数1.规定要移动的文件。参数2.规定文件的新位置。
                Func::uploadFile($byte,$path);    //上传七牛（参数1.图片本地路径，参数2.图片名称(包含扩展名)）
                $url = Func::getImgUrl($path);                                             //获取连接
                return $url;
            }
        } else {
            return ['code' => 0, 'status' => 'error', 'message' => '图片类型错误'];
        }
    }
    /**
     * 上传图片路径到七牛
     * @author xingsonglin
     * @param $filePath  //图片本地路径
     * @param $fileName  //图片名称(包含扩展名)
     * @return array key文件名
     * @throws \Exception
     */
    public static function uploadFile($filePath,$fileName){
        $model = new Qiniu();
        $return = $model->uploadFile($filePath,$fileName);
        return $return;
    }
    /**
     * @desc 得到图片完整路径
     * @author xingsonglin
     * @param $fileName //文件名称
     * @return string 文件路径
     *
     */
    public static function getImgUrl($fileName){
        $model = new Qiniu();
        $return = $model->getImgUrl($fileName);
        return $return;
    }


    public static function dataChange($data){
        foreach($data as $keys=>$value){
             if(isset($value[0])){
                 return $value[0];
             }
        }
    }
}