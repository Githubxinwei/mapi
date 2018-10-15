<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);
use backend\components\payment\lib\WxPayApi;
use backend\components\payment\lib\WxPayNotify;
use backend\components\payment\example\Log;
use backend\components\payment\example\CLogFileHandler;
use backend\components\payment\lib\WxPayOrderQuery;
include ('../../components/payment/lib/WxPay.Notify.php');
//初始化日志
$logHandler = new CLogFileHandler("../payment/logs/".date('Y-m-d').'.txt');
$log        = Log::Init($logHandler, 15);

class PayNotifyCallBack extends WxPayNotify
{
	//查询订单
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		Log::DEBUG("query:" . json_encode($result));
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			return true;
		}
		return false;
	}
	
	//重写回调处理函数
	public function NotifyProcess($data, &$msg)
	{
		Log::DEBUG("call back:" . json_encode($data));
		$notfiyOutput = array();
		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			return false;
		}
		return true;
	}
}

Log::DEBUG("begin notify2");
$notify = new PayNotifyCallBack();
$notify->Handle(false);
$res = $notify->GetValues();
if($res['return_code'] === 'SUCCESS'){
	$rawXml = file_get_contents("php://input");
	libxml_disable_entity_loader(true);
	$ret = json_decode(json_encode(simplexml_load_string($rawXml,'SimpleXMLElement',LIBXML_NODATA)),true);
	$orderId = $ret['attach'];
	$order   = \common\models\base\Order::findOne(['id'=>$orderId]);
	$order->status = 2;
	$order->save();
	echo 'SUCCESS';
}

?>
