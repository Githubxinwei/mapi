<?php
namespace common\models;

use Yii;
use yii\base\Model;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class Qiniu extends model
{
    private $accessKey = "su_7pmwUM2iX2wn_2F0YSjOHqMWffbi6svEysW3S";        // 需要填写你的 Access Key 和 Secret Key
    private $secretKey = "Hxz3V-NoBgrhzb8R2lEgf4X67JwA9EAic9EXx8Py";
    private $bucket = "cloudsports";                                        // 要上传的空间
    private $baseurl = "http://oo0oj2qmr.bkt.clouddn.com";                  //图片基础路径
    private $callbackUrl = "";

    /**
     * 上传图片路径到七牛
     * @author xingsonglin
     * @param $filePath  图片本地路径
     * @param $fileName  图片名称(包含扩展名)
     * @return array key文件名
     * @throws \Exception
     */
    public function uploadFile($filePath,$fileName){

        $accessKey = $this->accessKey;
        $secretKey = $this->secretKey;
        // 构建鉴权对象
        $auth = new Auth($accessKey, $secretKey);
        $bucket = $this->bucket;
        // 生成上传 Token
        $token = $auth->uploadToken($bucket);
        // 初始化 UploadManager 对象并进行文件的上传。
        $uploadMgr = new UploadManager();
        // 调用 UploadManager 的 putFile 方法进行文件的上传。
        list($ret, $err) = $uploadMgr->putFile($token, $fileName, $filePath);
         return $err;
    }

    /**
     * 上传图片路径到七牛并回调到服务器
     * @author xingsonglin
     * @param $filePath  图片本地路径
     * @param $fileName  图片名称(包含扩展名)
     * @return array key文件名
     * @throws \Exception
     */
    public function uploadFileCallback($filePath,$fileName){


        $accessKey = $this->accessKey;
        $secretKey = $this->secretKey;
        // 构建鉴权对象
        $auth = new Auth($accessKey, $secretKey);
        // 要上传的空间
        $bucket = $this->bucket;
        // 上传文件到七牛后， 七牛将文件名和文件大小回调给业务服务器.
        // 可参考文档: http://developer.qiniu.com/docs/v6/api/reference/security/put-policy.html
        $policy = array(
            'callbackUrl' => 'http://your.domain.com/callback.php',
            'callbackBody' => 'filename=$(fname)&filesize=$(fsize)'
        );
        $uptoken = $auth->uploadToken($bucket, null, 3600, $policy);

        $uploadMgr = new UploadManager();

        list($ret, $err) = $uploadMgr->putFile($uptoken, null, $filePath);
        return $err;
    }
    /**
     * 上传图片回调函数
     * @author xingsonglin
     * @return json key文件名
     * @throws \Exception
     */

    public function callBack()
    {
        $accessKey = $this->accessKey;
        $secretKey = $this->secretKey;
        $auth = new Auth($accessKey, $secretKey);

        //获取回调的body信息
        $callbackBody = file_get_contents('php://input');

        //回调的contentType
        $contentType = 'application/x-www-form-urlencoded';

        //回调的签名信息，可以验证该回调是否来自七牛
        $authorization = $_SERVER['HTTP_AUTHORIZATION'];

        //七牛回调的url，具体可以参考：http://developer.qiniu.com/docs/v6/api/reference/security/put-policy.html
        $url = 'http://172.30.251.210/callback.php';

        $isQiniuCallback = $auth->verifyCallback($contentType, $authorization, $url, $callbackBody);

        if ($isQiniuCallback) {
            $resp = array('ret' => 'success');
        } else {
            $resp = array('ret' => 'failed');
        }

        return json_encode($resp);
    }

    
    /**
     * @desc 得到图片完整路径
     * @author xingsonglin
     * @param $fileName 文件名称
     * @return string 文件路径
     *
     */
    public function getImgUrl($fileName){
        $accessKey = $this->accessKey;
        $secretKey = $this->secretKey;
        // 构建Auth对象
        $auth = new Auth($accessKey, $secretKey);

        $baseUrl = $this->baseurl.'/'.$fileName;

        // 对链接进行签名
        $signedUrl = $auth->privateDownloadUrl($baseUrl);

        return $signedUrl;
    }

    /**
     * @desc 删除图片
     * @author xingsonglin
     * @param $fileName 文件名称
     * @return bool
     *
     */
    public function deleteImg($fileName){

        $accessKey = $this->accessKey;
        $secretKey = $this->secretKey;
        //初始化Auth状态：
        $auth = new Auth($accessKey, $secretKey);

        //初始化BucketManager
        $bucketMgr = new BucketManager($auth);

        //你要测试的空间， 并且这个key在你空间中存在
        $bucket = $this->bucket;

        //删除$bucket 中的文件 $key
        $err = $bucketMgr->delete($bucket, $fileName);
        if ($err !== null) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 上传base64格式图片
     * @author   yanghuilei<yanghuilei@itsport.club>
     * @createAt 2018/3/7
     * @param $base64_str
     * @param $fileName
     * @return mixed
     */
    public function uploadBase64($fileName, $base64_str)
    {
        $accessKey     = $this->accessKey;
        $secretKey     = $this->secretKey;
        $base64_string = explode(',', $base64_str);
        $octet         = base64_decode($base64_string[1]);
        $auth          = new Auth($accessKey, $secretKey);
        $bucket        = $this->bucket;
        $token         = $auth->uploadToken($bucket);
        $uploadMgr     = new UploadManager();
        list($ret, $err) = $uploadMgr->put($token, $fileName, $octet);
        return $err;
//        $serverUrl = "http://upload.qiniu.com/putb64/" . $oldFileSize;
//        $auth = new Auth($accessKey, $secretKey);
//        $upToken = $auth->uploadToken($this->bucket);
//        $headers = [];
//        $headers[] = 'Content-Type:image/png';
//        $headers[] = 'Authorization:UpToken '.$upToken;
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL,$serverUrl);
//        curl_setopt($ch, CURLOPT_HTTPHEADER ,$headers);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $base64_str);
//        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
//        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
//        $result = curl_exec($ch);
//        curl_close($ch);
//        $data = json_decode($result, true);
//        if(is_array($data) && isset($data['key'])){
//            $baseUrl = $this->baseurl . '/' . $data['key'];
//            $imgReUrl = $auth->privateDownloadUrl($baseUrl);
//            return $imgReUrl;
//        }
//        return false;
    }
}
