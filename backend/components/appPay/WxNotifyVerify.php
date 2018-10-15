<?php
namespace backend\components\appPay;
use \backend\components\appPay\lib\WeEncryption;
use backend\modules\v1\models\CabinetRentForm;
use backend\modules\v1\models\PaymentCardForm;
use common\models\base\Order;

class WxNotifyVerify {
	
	public function getNotifySignData(){
		$weEncpt = WeEncryption::getInstance();
        $obj     = $weEncpt->getNotifyData();
//		$emObj   = $this->getData($obj);
		if ($obj === false) {
			return $obj;
		}
		if (!empty($obj)) {
			$data = [
				'appid'				=>	$obj->appid,
				'mch_id'			=>	$obj->mch_id,
				'attach'            =>  $obj->attach,
				'nonce_str'			=>	$obj->nonce_str,
				'result_code'		=>	$obj->result_code,
				'openid'			=>	$obj->openid,
				'trade_type'		=>	$obj->trade_type,
				'bank_type'			=>	$obj->bank_type,
				'total_fee'			=>	$obj->total_fee,
				'cash_fee'			=>	$obj->cash_fee,
				'transaction_id'	=>	$obj->transaction_id,
				'out_trade_no'		=>	$obj->out_trade_no,
				'time_end'			=>	$obj->time_end
			];
			$sign = $weEncpt->getSign($data);
			if ($data['result_code'] == 'SUCCESS') {
				$reply = "<xml>
					<return_code><![CDATA[SUCCESS]]></return_code>
					<return_msg><![CDATA[OK]]></return_msg>
				</xml>";
				$rawXml = file_get_contents("php://input");
				libxml_disable_entity_loader(true);
				$ret = json_decode(json_encode(simplexml_load_string($rawXml,'SimpleXMLElement',LIBXML_NOCDATA)),true);
				$orderId = $ret['attach'];
				$order   = Order::findOne(['id'=>$orderId]);
				if(!empty($order) && $order->status == 2){
					return $reply;
				}
				$payment = new PaymentCardForm();
				$pay     = $payment->setMemberCardInfo($orderId);
				$pay_params = json_decode($order->pay_params,true);
                $pay_params["notify_params"] = $obj;
                $params = json_encode($pay_params);
                $secretKey = 'xingfufit';
                $pay_params = json_encode(base64_encode(\Yii::$app->security->encryptByPassword($params,$secretKey)));
                Order::updateAll(['pay_params' => $pay_params,'merchant_order_number' => $obj->transaction_id],['id' => $orderId]);
				$payment->sendMessage();
				if($pay === 'SUCCESS'){
				   return $reply;
				}
			}
		}
		return 'FAIL';
	}
	/**
	 * 云运动 - 后台-  会员卡爽约解冻代码
	 * @author houkaixin <houkaixin@itsports.club>
	 * @create 2017/6/8
	 * @return  string  //返回报错信息
	 */
	public function getThawNotifySignData(){
		$weEncpt = WeEncryption::getInstance();
		$obj     = $weEncpt->getNotifyData();
//		$emObj   = $this->getData($obj);
		if ($obj === false) {
			return $obj;
		}
		if (!empty($obj)) {
			$data = [
				'appid'				=>	$obj->appid,
				'mch_id'			=>	$obj->mch_id,
				'attach'            =>  $obj->attach,
				'nonce_str'			=>	$obj->nonce_str,
				'result_code'		=>	$obj->result_code,
				'openid'			=>	$obj->openid,
				'trade_type'		=>	$obj->trade_type,
				'bank_type'			=>	$obj->bank_type,
				'total_fee'			=>	$obj->total_fee,
				'cash_fee'			=>	$obj->cash_fee,
				'transaction_id'	=>	$obj->transaction_id,
				'out_trade_no'		=>	$obj->out_trade_no,
				'time_end'			=>	$obj->time_end
			];
			$sign = $weEncpt->getSign($data);
			if ($data['result_code'] == 'SUCCESS') {
				$reply = "<xml>
					<return_code><![CDATA[SUCCESS]]></return_code>
					<return_msg><![CDATA[OK]]></return_msg>
				</xml>";
				$rawXml = file_get_contents("php://input");
				libxml_disable_entity_loader(true);
				$ret = json_decode(json_encode(simplexml_load_string($rawXml,'SimpleXMLElement',LIBXML_NOCDATA)),true);
				$orderId = $ret['attach'];
				$order   = Order::findOne(['id'=>$orderId]);
				if(!empty($order) && $order->status == 2){
					return $reply;
				}
				$payment = new PaymentCardForm();
				$pay     = $payment->thawMemberCard($orderId);
				$payment->sendMessage();
				if($pay === 'SUCCESS'){
					return $reply;
				}
			}
		}
		return 'FAIL';
	}

	/**
	 * 云运动 - 后台-  柜子租赁回调标签验证
	 * @author houkaixin <houkaixin@itsports.club>
	 * @create 2017/6/8
	 * @return  string  //返回报错信息
	 */
	public function CabinetRent(){
		$weEncpt = WeEncryption::getInstance();
		$obj     = $weEncpt->getNotifyData();
		$reply = "<xml>
					<return_code><![CDATA[SUCCESS]]></return_code>
					<return_msg><![CDATA[OK]]></return_msg>
				</xml>";
//		$emObj   = $this->getData($obj);
		if ($obj === false) {
			return $obj;
		}
		if (!empty($obj)) {
			$data = [
				'appid'				=>	$obj->appid,
				'mch_id'			=>	$obj->mch_id,
				'attach'            =>  $obj->attach,
				'nonce_str'			=>	$obj->nonce_str,
				'result_code'		=>	$obj->result_code,
				'openid'			=>	$obj->openid,
				'trade_type'		=>	$obj->trade_type,
				'bank_type'			=>	$obj->bank_type,
				'total_fee'			=>	$obj->total_fee,
				'cash_fee'			=>	$obj->cash_fee,
				'transaction_id'	=>	$obj->transaction_id,
				'out_trade_no'		=>	$obj->out_trade_no,
				'time_end'			=>	$obj->time_end
			];
			$sign = $weEncpt->getSign($data);
			if ($data['result_code'] == 'SUCCESS') {
				$rawXml = file_get_contents("php://input");
				libxml_disable_entity_loader(true);
				$ret = json_decode(json_encode(simplexml_load_string($rawXml,'SimpleXMLElement',LIBXML_NOCDATA)),true);
				$orderId = $ret['attach'];
				$order   = Order::findOne(['id'=>$orderId]);
				if(!empty($order) && $order->status == 2){
					return $reply;
				}
				$payment = new CabinetRentForm();
				$pay     = $payment->rentCabinetLogic($orderId);
				//$payment->sendMessage();
				if($pay ===  'SUCCESS'){
					return $reply;
				}
			}
		}
		return $reply;
	}



















	/**
	 * 接收支付结果通知参数
	 * @return Object 返回结果对象；
	 */
	public function getData($data)
	{
		$arr = [];
       if(!empty($data)){
          foreach ($data as $k=>$v){
			  $arr[] = '...'.$k.'->'.$v ."...\n";
		  }
	   }
		return $arr;
	}
}
