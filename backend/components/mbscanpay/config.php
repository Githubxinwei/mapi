<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => "2017091108675479",

		//商户私钥
		'merchant_private_key' =>"MIICXgIBAAKBgQC4yBrNmztd7TJTeRkzs6XW32pv4Sq8Uu5SouqZ0Zvtn1v8hedODcdeJp/xdLD+bB0KcwQH7WYs1b7OIp3wMW3w0MDmBh/hpbib2wZoWsK198EY+rAf55VaGduwIB2u1/RemLIgH583z+5P2BQr+SRPoV0LcI+51HD8TFlbBQ9FLQIDAQABAoGAQX/1OFLBXY8aGsq/aztQGvXBJf18B5uiDTrgzDun+ThXBBF3J4zs0ewBIDcMEnPCa5TPpfu6D0SJSUquD5mF7Y+ccAR6wI6igpaCqWCWgx/X7FbvKhBGjOm2pfPqck6S6zQOof906BcA3hBJOkODZPLOoo+rXpqwTi3RcV8GweECQQDoJXrt75vNI3utkkk7P3MhHOFvD81g3vYw6wPsx47cMyOnYYgya0gJ2o3U0DtUIFieewdACxoYVBfkQcsMewaFAkEAy8S2pZi9X4H4jN71mQ/X40UD6IziL5sD3qyvNrcQM/ErLHLacNtjB8cGy+B7C2ozfEf3lF5tLO8jvy9zfaQoiQJBANsN6ua0b67t6ZmKbUHUCH5ZczvKjID5QxQ735NBZ0PPmbgq50q0QuDRc346E5G5iAXbj6bWEwSb7YN8te4L9MUCQQCVJulWr0W2uikX3D/DiQBKgAMLXsxVck9T1+zszPTUQGyMvYk9YKjNUZac9zS5t0P2batAdBnP8T+mOvJ7fgSZAkEA5qqxzlw6CKKJzYG5i1HiOKQ+X38SuN8UCsvyzCYbszYftnElQ1M8c9p5NeNiiUd0oMVrgcXpBEzbhOhMa3BRZQ==",
		
		//异步通知地址
		'notify_url' => "http://qa.aixingfu.net/v1/api-payment/ali-pay-app-notify",
		
		//同步跳转
		'return_url' => "http://qa.aixingfu.net/v1/api-payment/ali-pay-app-notify",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDDI6d306Q8fIfCOaTXyiUeJHkrIvYISRcc73s3vF1ZT7XN8RNPwJxo8pWaJMmvyTn9N4HQ632qJBVHf8sxHi/fEsraprwCtzvzQETrNRwVxLO5jVmRGi60j8Ue1efIlzPXV9je9mkjzOmdssymZkh2QhUrCmZYI/FCEa3/cNMW0QIDAQAB",
);