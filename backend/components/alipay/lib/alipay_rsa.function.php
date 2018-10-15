<?php
/**
 * 签名字符串
 * @param int $preStr //需要签名的字符串
 * @return array //签名结果
 */
 function rsaSign($preStr) {
       $public_key = file_get_contents('../payment/web/rsa_private_key.pem');
       $keyId      = openssl_get_privatekey($public_key);
       openssl_sign($preStr, $sign, $keyId);
       openssl_free_key($keyId);
       $sign = base64_encode($sign);
      return $sign;
 }
 /**
  * 验证签名
  * @param $preStr / 需要签名的字符串
  * @param $sign   / 签名结果
  * @return array  /签名结果
  */
    function rsaVerify($preStr, $sign) {
        $sign       = base64_decode($sign);
        $publicKey  = file_get_contents('/payment/rsa_public_key.pem');
        $pKeyId     = openssl_get_publickey($publicKey);
        if ($pKeyId) {
          $verify = openssl_verify($preStr, $sign, $pKeyId);
          openssl_free_key($pKeyId);
        }
        if($verify == 1){
                return true;
        }else{
                return false;
        }
    }