<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => "2017070707675504",

		//商户私钥，您的原始格式RSA私钥
		'merchant_private_key' => "MIICXgIBAAKBgQDYDXDeVct4MFkQRMcTqvzd66HkzBsw9as0t+eqTvcn3TP9uufGb6HJNDKAbOcd8ymjUMErM66WSuZqkv+wHGOPTi5u2BbGMzJN4hbtm19+iHO32YPKPhaq1Meji/TEzh+7fD5HqfwFwPrTW7OuH4x8bI9pbzeLw0df/MH4vADGjwIDAQABAoGBAMKZEKFh21uNCJtrp3fimdH1C6O953vouuVIA+TVLS59wfd2oo6MO8ezOQ2NiXnjbAyKjwKvkUAThIhXkO96Enz3yzHMuLIjy67UWnZW0+rsBb/2xdks/MBNKaSM3rJsm2IDeXmmJflq3+w+TRhTLkFPIbcYKpyD4FUFUJa1kfiRAkEA+TEiEON1ohtC2ZkU5MHXNekzzN5f9+ZgBKMHne9Q6dQ7pV6Hj2AVBJkaLMp+AKtcdpRcSA/7Eam/gLFXe1iohwJBAN30hznqrE1SuWD3Z81aUzfXK3MYriEXbqGpLv18RcFJ57HOCcV2BnFFNCdYkHQzOUGLvaMlewZYb+0q2s+BW7kCQFCClVULiFgFosWW9e8qyZN7cub/+dWoMuGMosteJLBHh7XYIsgp7OB9br2F9svUkZ0mNeJ6d02voFW99lu9u0UCQQDaSZFsznWinkcfLHL0LW9GreFXkuA+P1NYvpEuT7gyvsKVxfMqNkdjwxHl2F5JSc93vT1GZKg3t2ZstAb6qVhRAkEA8CRvKZoRhDoxxUJo7h4iniJn850NT861vA+x42XcCCKCLgawOQAN/7fS9iFkng9S6w5bJwn7dYn4bK2HguXf4w==",
		
		//异步通知地址
		'notify_url' => "http://".$_SERVER['SERVER_NAME']."/payment/notify-pay",
		
		//同步跳转
		'return_url' => "http://".$_SERVER['SERVER_NAME']."/purchase-card/order-complete",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDYDXDeVct4MFkQRMcTqvzd66HkzBsw9as0t+eqTvcn3TP9uufGb6HJNDKAbOcd8ymjUMErM66WSuZqkv+wHGOPTi5u2BbGMzJN4hbtm19+iHO32YPKPhaq1Meji/TEzh+7fD5HqfwFwPrTW7OuH4x8bI9pbzeLw0df/MH4vADGjwIDAQAB",
		
	
);