<?php
namespace backend\components\mbpayment\lib;
/**
 * 
 * 微信支付API异常类
 * @author widyhu
 *
 */
class WxPayException extends \yii\base\Exception {
	public function errorMessage()
	{
		return $this->getMessage();
	}
}
