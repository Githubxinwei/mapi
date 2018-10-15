<?php
namespace backend\components\appPay;

use backend\modules\v3\models\WechatPay;
use Yii;
use backend\components\appPay\lib\Curl;
use backend\components\appPay\lib\WeEncryption;

header("Content-type:text/html;charset=utf8");

class WePay
{
   public function getWxContentSign($params,$type = null)
   {
	   if(!is_array($params)){
		   return false;
	   }
	   $params['attach'] = urlencode($params['attach']);
	   $encryption       = WeEncryption::getInstance();		//实例化签名类
	   if(empty($type)){
		   $url              = Yii::$app->request->hostInfo . '/v1/api-payment/notify';
	   }elseif($type=="MB"){
		   $url              = AppConfig::MB_NOTIFY_URL;
	   }else{
           $url              = Yii::$app->request->hostInfo . '/v1/api-payment/thaw-notify';
       }
	   $encryption->setNotifyUrl($url);			//设置异步通知地址

	   $curl = new Curl();				//实例化传输类；
	   $xml_data = $encryption->sendRequest($curl,$params);		//发送请求
	   $postObj  = $encryption->xmlToObject($xml_data);			//解析返回数据
	   if ($postObj === false) {
		   return $postObj;
	   }
	   if ($postObj->return_code == 'FAIL') {
		   echo $postObj->return_msg;
	   } else {
		   $resignData = array(
			   'appid'			=>	$postObj->appid,
			   'partnerid'		=>	$postObj->mch_id,
			   'prepayid'		=>	$postObj->prepay_id,
			   'noncestr'		=>	$postObj->nonce_str,
			   'timestamp'		=>	time(),
			   'package'	    =>	'Sign=WXPay'
		   );
		   $sign = $encryption->getClientPay($resignData);
		   $resignData['sign'] = $sign;
		   return  $resignData;
	   }
   }

	/**
	 * 云运动 - 后台- 柜子下订单签名验证
	 * @author houkaixin <houkaixin@itsports.club>
	 * @create 2017/6/8
	 * @param $params  // 对应app的配置参数
	 * @return  string  //返回报错信息
	 */
	public function getWxRentCabinetContentSign($params){
		if(!is_array($params)){
			return false;
		}
		$params['attach'] = urlencode($params['attach']);
		$encryption       = WeEncryption::getInstance();		//实例化签名类
		$url              = Yii::$app->request->hostInfo . '/v1/api-payment/cabinet-rent-notify';
		$encryption->setNotifyUrl($url);			//设置异步通知地址

		$curl = new Curl();				//实例化传输类；
		$xml_data = $encryption->sendRequest($curl,$params);		//发送请求
		$postObj  = $encryption->xmlToObject($xml_data);			//解析返回数据
		if ($postObj === false) {
			return $postObj;
		}
		if ($postObj->return_code == 'FAIL') {
			echo $postObj->return_msg;
		} else {
			$resignData = array(
				'appid'			=>	$postObj->appid,
				'partnerid'		=>	$postObj->mch_id,
				'prepayid'		=>	$postObj->prepay_id,
				'noncestr'		=>	$postObj->nonce_str,
				'timestamp'		=>	time(),
				'package'	    =>	'Sign=WXPay'
			);
			$sign = $encryption->getClientPay($resignData);
			$resignData['sign'] = $sign;
			return  $resignData;
		}
	}

    /**
     * 云运动 - 后台 - 小程序下单签名验证
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2018/2/7
     * @param  $params  // 对应小程序支付的配置参数
     * @return string   //返回报错信息
     */
    public function getMiniContentSign($params)
    {
        if(!is_array($params)){
            return false;
        }
        $params['attach'] = '我爱运动';
        $encryption       = WeEncryption::getInstance();		    //实例化签名类
        $url              = '';
        $encryption->setNotifyUrl($url);			                //设置异步通知地址
        $curl     = new Curl();				                        //实例化传输类；
        $xml_data = $encryption->MiniSendRequest($curl,$params);    //发送请求
        $postObj  = $encryption->xmlToObject($xml_data);			//解析返回数据
        if ($postObj === false) {
            return $postObj;
        }
        if ($postObj->return_code == 'FAIL') {
            echo $postObj->return_msg;
        } else {
            $time = time();
            $data = array();
            $data['appId']        = 'wx90721efca5d2c575';
            $data['timeStamp']    = "{$time}";
            $data['nonceStr']     = $postObj->nonce_str;
            $data['package']      = 'prepay_id='.$postObj->prepay_id;
            $data['signType']     = 'MD5';
            $data['sign']         = WechatPay::getSign($data);
            $data['order_number'] = $params['out_trade_no'];
            return $data;
        }
    }

    /**
     * 云运动 - 后台 - 小程序下单签名验证
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2018/2/7
     * @param  $params  //对应公众号支付的配置参数
     * @return string   //返回报错信息
     */
    public function getOfficialContentSign($params)
    {
        if(!is_array($params)){
            return false;
        }
        $params['attach'] = '我爱运动';
        $encryption       = WeEncryption::getInstance();		    //实例化签名类
        $url              = '';
        $encryption->setNotifyUrl($url);			                //设置异步通知地址
        $curl     = new Curl();				                        //实例化传输类；
        $xml_data = $encryption->MiniSendRequest($curl,$params);    //发送请求
        $postObj  = $encryption->xmlToObject($xml_data);			//解析返回数据
        if ($postObj === false) {
            return $postObj;
        }
        if ($postObj->return_code == 'FAIL') {
            echo $postObj->return_msg;
        } else {
            $time = time();
            $data = array();
            $data['appId']        = 'wx62d223b7a2f0457a';
            $data['timeStamp']    = "{$time}";
            $data['nonceStr']     = $postObj->nonce_str;
            $data['package']      = 'prepay_id='.$postObj->prepay_id;
            $data['signType']     = 'MD5';
            $data['paySign']      = WechatPay::getSign($data);
            $data['order_number'] = $params['out_trade_no'];
            return $data;
        }
    }

}