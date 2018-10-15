<?php
namespace backend\modules\v1\models;

use Yii;
use backend\components\appPay\AppConfig;
use backend\components\payment\lib\WxPayApi;
include "../components/payment/lib/WxPay.Api.php";
use yii\base\Model;

class Payment extends Model
{
    private static $PRIVATE_KEY = 'http://www.b-cloud.com/payment/rsa_private_key.pem';
    private static $PUBLIC_KEY  = '';

    public static function getWxConfig($params){
        if(isset($params['type']) && $params['type'] == 'charge'){
            $type = '私教课程';
        }elseif(isset($params['type']) && $params['type'] == 'chargeGroup'){
            $type = '私教小团体课';
        }elseif(isset($params['type']) && $params['type'] == 'meal'){
            $type = '健身餐';
        }else{
            $type = '会员卡';
        }
        $config = [
            'appid'        => AppConfig::APPID,
            'mch_id'       => AppConfig::MCHID,
            'nonce_str'    => WxPayApi::getNonceStr(),
            'trade_type'   => 'APP',
            'body'         => $type,
            'out_trade_no' => isset($params['out_trade_no'])?$params['out_trade_no']:'',
            'total_fee'    => isset($params['price'])?$params['price']:'',
            'attach'       => isset($params['attach'])?$params['attach']:'',
            'notify_url'   => Yii::$app->request->hostInfo .'/v1/api-payment/notify',
            'spbill_create_ip'=> $_SERVER['REMOTE_ADDR']
        ];
        return $config;
    }

    public static function getMbWxConfig($params){
        if(isset($params['type']) && $params['type'] == 'charge'){
            $type = '私教课程';
        }elseif(isset($params['type']) && $params['type'] == 'chargeGroup'){
            $type = '私教小团体课';
        }else{
            $type = '会员卡';
        }
        $config = [
            'appid'        => AppConfig::MB_APPID,
            'mch_id'       => AppConfig::MB_MCHID,
            'nonce_str'    => WxPayApi::getNonceStr(),
            'trade_type'   => 'APP',
            'body'         => $type,
            'out_trade_no' => isset($params['out_trade_no'])?$params['out_trade_no']:'',
            'total_fee'    => isset($params['price'])?$params['price']:'',
            'attach'       => isset($params['attach'])?$params['attach']:'',
            'notify_url'   => Yii::$app->request->hostInfo .'/v1/api-payment/notify',
            'spbill_create_ip'=> $_SERVER['REMOTE_ADDR']
        ];
        return $config;
    }

    /**
     * 后台 - 会员卡解冻- 缴纳罚金
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/10/09
     * @param  $params     // 请求参数
     * @return object     // 返回所有的app请求参数
     */
    public static function getThawWxConfig($params){
        $type = "会员卡解冻";
        $config = [
            'appid'        => AppConfig::APPID,
            'mch_id'       => AppConfig::MCHID,
            'nonce_str'    => WxPayApi::getNonceStr(),
            'trade_type'   => 'APP',
            'body'          => $type,
            'out_trade_no' => isset($params['out_trade_no'])?$params['out_trade_no']:'',
            'total_fee'    => isset($params['price'])?$params['price']:'',
            'attach'       => isset($params['attach'])?$params['attach']:'',
            'notify_url'   => Yii::$app->request->hostInfo .'/v1/api-payment/thaw-notify',
            'spbill_create_ip'=> $_SERVER['REMOTE_ADDR']
        ];
        return $config;
    }



    /**
     * 后台 - 会员卡解冻- 缴纳罚金
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/10/09
     * @param  $params     // 请求参数
     * @return object     // 返回所有的app请求参数
     */
    public static function getMbThawWxConfig($params){
        $type = "会员卡解冻";
        $config = [
            'appid'        => AppConfig::MB_APPID,
            'mch_id'       => AppConfig::MB_MCHID,
            'nonce_str'    => WxPayApi::getNonceStr(),
            'trade_type'   => 'APP',
            'body'          => $type,
            'out_trade_no' => isset($params['out_trade_no'])?$params['out_trade_no']:'',
            'total_fee'    => isset($params['price'])?$params['price']:'',
            'attach'       => isset($params['attach'])?$params['attach']:'',
            'notify_url'   => Yii::$app->request->hostInfo .'/v1/api-payment/thaw-notify',
            'spbill_create_ip'=> $_SERVER['REMOTE_ADDR']
        ];
        return $config;
    }
    /**
     * 后台 - 会员租柜子- 会员租柜子配置参数
     * @author Hou kaixin <houkaixin@itsports.club>
     * @create 2017/10/09
     * @param  $params     // 请求参数
     * @return object     // 返回所有的app请求参数
     */
    public static function getCabinetRentConfig($params){
        $type = "柜子租用";
        $config = [
            'appid'        => AppConfig::APPID,
            'mch_id'       => AppConfig::MCHID,
            'nonce_str'    => WxPayApi::getNonceStr(),
            'trade_type'   => 'APP',
            'body'          => $type,
            'out_trade_no' => isset($params['out_trade_no'])?$params['out_trade_no']:'',
            'total_fee'    => isset($params['price'])?$params['price']:'',
            'attach'       => isset($params['attach'])?$params['attach']:'',
            'notify_url'   => Yii::$app->request->hostInfo .'/v1/api-payment/cabinet-rent-notify',
            'spbill_create_ip'=> $_SERVER['REMOTE_ADDR']
        ];
        return $config;
    }




    public function getAliPayConfig($params){
        $arr = $this->getContent($params);
        $config = [
            'app_id'        => \backend\components\alipay\Config::APPID,
            'biz_content'   => json_encode($arr),
            'charset'       => strtolower('UTF-8'),
            'format'        => 'json',
            'method'        => 'alipay.trade.app.pay',
            'sign_type'     => strtoupper(trim('RSA')),
            'notify_url'    => Yii::$app->request->hostInfo .'/v1/api-payment/ali-pay-notify',
            'timestamp'     => date('Y-m-d H:i:s',time()),
            'version'       =>  '1.0',
        ];
        return $config;
    }

    public function getAliPayAppConfig($params){
        $arr = $this->getContent($params);
        $config = [
            'app_id'        => \backend\components\alipay\Config::MB_APPID,
            'biz_content'   => json_encode($arr),
            'charset'       => strtolower('UTF-8'),
            'format'        => 'json',
            'method'        => 'alipay.trade.app.pay',
            'sign_type'     => strtoupper(trim('RSA')),
            'notify_url'    => Yii::$app->request->hostInfo .'/v1/api-payment/ali-pay-app-notify',
            'timestamp'     => date('Y-m-d H:i:s',time()),
            'version'       =>  '1.0',
        ];
        return $config;
    }

    public function getContent($params)
    {
        if(isset($params['type']) && $params['type'] == 'charge'){
             $type = '私教课程';
        }elseif(isset($params['type']) && $params['type'] == 'chargeGroup'){
            $type = '私教小团体课';
        }elseif(isset($params['type']) && $params['type'] == 'meal'){
            $type = '健身餐';
        }else{
             $type = '会员卡';
        }
        return [
            'timeout_express' => '30m',
            'seller_id'       => '',
            'product_code'    => 'QUICK_MSECURITY_PAY',
            'total_amount'    => isset($params['total_amount'])?$params['total_amount']:'',
            'subject'         => $type,
            'body'            => isset($params['body'])?$params['body']:'',
            'attach'          => isset($params['attach'])?$params['attach']:'',
            'out_trade_no'    => isset($params['out_trade_no'])?$params['out_trade_no']:'',
        ];
    }

    public function getThawContent($params){
        return [
            'timeout_express' => '30m',
            'seller_id'       => '',
            'product_code'    => 'QUICK_MSECURITY_PAY',
            'total_amount'    => isset($params['total_amount'])?$params['total_amount']:'',
            'subject'         => "会员卡解冻",
            'body'            => isset($params['body'])?$params['body']:'',
            'attach'          => isset($params['attach'])?$params['attach']:'',
            'out_trade_no'    => isset($params['out_trade_no'])?$params['out_trade_no']:'',
        ];
    }

    public function getAliPayThawConfig($params){
        $arr = $this->getThawContent($params);
        $config = [
            'app_id'        => \backend\components\alipay\Config::APPID,
            'biz_content'   => json_encode($arr),
            'charset'       => strtolower('UTF-8'),
            'format'        => 'json',
            'method'        => 'alipay.trade.app.pay',
            'sign_type'     => strtoupper(trim('RSA')),
            'notify_url'    =>Yii::$app->request->hostInfo .'/v1/api-payment/ali-pay-notify',
            'timestamp'     => date('Y-m-d H:i:s',time()),
            'version'       =>  '1.0',
        ];
        return $config;
    }



    public function gainAliPayCabinetOrder($params){
        $arr = $this->gainAliCabinetConfig($params);
        $config = [
            'app_id'        => \backend\components\alipay\Config::APPID,
            'biz_content'   => json_encode($arr),
            'charset'       => strtolower('UTF-8'),
            'format'        => 'json',
            'method'        => 'alipay.trade.app.pay',
            'sign_type'     => strtoupper(trim('RSA')),
            'notify_url'    => Yii::$app->request->hostInfo .'/v1/api-payment/ali-cabinet-order-notify',
            'timestamp'     => date('Y-m-d H:i:s',time()),
            'version'       =>  '1.0',
        ];
        return $config;
    }


    public function gainAliCabinetConfig($params){
        return [
            'timeout_express' => '30m',
            'seller_id'       => '',
            'product_code'    => 'QUICK_MSECURITY_PAY',
            'total_amount'    => isset($params['total_amount'])?$params['total_amount']:'',
            'subject'         => "会员购买柜子",
            'body'            => isset($params['body'])?$params['body']:'',
            'attach'          => isset($params['attach'])?$params['attach']:'',
            'out_trade_no'    => isset($params['out_trade_no'])?$params['out_trade_no']:'',
        ];
    }






    public function getMbAliPayThawConfig($params){
        $arr = $this->getThawContent($params);
        $config = [
            'app_id'        => \backend\components\alipay\Config::MB_APPID,
            'biz_content'   => json_encode($arr),
            'charset'       => strtolower('UTF-8'),
            'format'        => 'json',
            'method'        => 'alipay.trade.app.pay',
            'sign_type'     => strtoupper(trim('RSA')),
            'notify_url'    => Yii::$app->request->hostInfo .'/v1/api-payment/ali-pay-notify',
            'timestamp'     => date('Y-m-d H:i:s',time()),
            'version'       =>  '1.0',
        ];
        return $config;
    }




    /****************
    &charset=utf-8
     * &method=alipay.trade.app.pay
     * &sign_type=RSA2
     * &timestamp=2016-07-28 20:36:11
     * &version=1.0

     */
    /**
     * 除去数组中的空值和签名参数
     * @param $para //签名参数组
     * @return string //去掉空值与签名参数后的新签名参数组
     */
    public function paraFilter($para) {
        $para_filter = array();
        while (list ($key, $val) = each ($para)) {
            if($key == "sign" || $val == "")continue;
            else    $para_filter[$key] = $para[$key];
        }
        return $para_filter;
    }
    /**
     * 对数组排序
     * @param $para //排序前的数组
     * @return //排序后的数组
     */
    public function argSort($para) {
        ksort($para);
        reset($para);
        return $para;
    }
    /**
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
     * @param $para //需要拼接的数组
     * @return string 拼接完成以后的字符串
     */
    public function createLinkString($para) {
        $arg  = "";
        $quotes = '"';
        while (list ($key, $val) = each ($para)) {
            $arg.=$key.'='.$quotes.urlencode($val).$quotes.'&';
        }
        //去掉最后一个&字符
        $arg = substr($arg,0,count($arg)-2);

        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}

        return $arg;
    }
    /**
     * RSA签名
     * @param $data //待签名数据
     * @param $private_key //商户私钥字符串
     * @return string //签名结果
     */
    public function rsaSign($data, $private_key) {
        //以下为了初始化私钥，保证在您填写私钥时不管是带格式还是不带格式都可以通过验证。
        $private_key=str_replace("-----BEGIN RSA PRIVATE KEY-----","",$private_key);
        $private_key=str_replace("-----END RSA PRIVATE KEY-----","",$private_key);
        $private_key=str_replace("\n","",$private_key);

        $private_key="-----BEGIN RSA PRIVATE KEY-----".PHP_EOL .wordwrap($private_key, 64, "\n", true). PHP_EOL."-----END RSA PRIVATE KEY-----";

        $res=openssl_get_privatekey($private_key);

        if($res)
        {
            openssl_sign($data, $sign,$res);
        }
        else {
            echo "您的私钥格式不正确!"."<br/>"."The format of your private_key is incorrect!";
            exit();
        }
        openssl_free_key($res);
        //base64编码
        $sign = base64_encode($sign);
        return $sign;
    }
    /**
     * 获取私钥
     * @return bool|resource
     */
    private static function getPrivateKey()
    {
        $privKey = self::$PRIVATE_KEY;
        $priKey = file_get_contents($privKey);
        return openssl_pkey_get_private($priKey);
    }

    /**
     * 获取公钥
     * @return bool|resource
     */
    private static function getPublicKey()
    {
        $publicKey = self::$PUBLIC_KEY;
        return openssl_pkey_get_public($publicKey);
    }

    /**
     * 创建签名
     * @param string $data 数据
     * @param  string $private
     * @return null|string
     */
    public function createSign($data = '')
    {
        if (!is_string($data)) {
            return null;
        }
        return openssl_sign(
            $data,
            $sign,
            self::getPrivateKey()
        ) ? base64_encode($sign) : null;
    }

    /**
     * 验证签名
     * @param string $data 数据
     * @param string $sign 签名
     * @return bool
     */
    public function verifySign($data = '', $sign = '')
    {
        if (!is_string($sign) || !is_string($sign)) {
            return false;
        }
        return (bool)openssl_verify(
            $data,
            base64_decode($sign),
            self::getPublicKey(),
            OPENSSL_ALGO_SHA256
        );
    }
}