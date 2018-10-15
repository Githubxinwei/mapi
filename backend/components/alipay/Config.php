<?php
namespace backend\components\alipay;
/**
 * 	配置账号信息
 */
class Config
{
    //=======【基本信息设置】=====================================
    //
    /**
     * TODO: 支付宝商户信息
     * 微信公众号信息配置
     * @var string
     */
    const APPID      = '2017070707675504';
//    const APPID     = 'wx29e459ec27f3a3be';
    const SECRET     = 'MIIEpAIBAAKCAQEA4rggVirbL/ftraaszFPCcRkFw9mYEOsKhR+YuNO8XoGoFc5YsgmPrxVLoeYIr/pgalDVaOzzvEiSh7DkOgNktZrTCwHBUYWU+cS1P5/R+TQbgpZMI1IpqcKWq4ATMFTIwPExet7i4j38PUcpuQmqY8PgAZ4/5kW3o2PKmix7oMWjPBw2ClfN+ritS71QOsZzwam2bWc3TbHLUaYST9kvt8tRNAGgtjUMz/EfHmnPREp6Upb80MM575OTCyaV8292TrHAXZ5QXaGkeYIDmBs+FLj35Hbc2bg/vTwpA6wCWFjEivhJEr5NI0UoQSxoHjYvrehtSMbsQDxif7rIJA559QIDAQABAoIBAF3ih6mHF7tWfPszEU2Pxe5VwVhoDBu9aV7VRcYMEG5GXNei2oHnibOkKuzBOIm/Mhv0m1tW/FvU5bEBJJGNhC/cDFb7hBWBLr77oV7WjPoYhOff22mpBY3CAejFIa0a/wM/HhFWqTetGX46sOFqSkAEBghFgwqVhkVH9sc/cnaReZSsPPzQ4qQdqyFzAHgsmjpu8wQ7lmNl+otPHJlftyAdbBdHEyTQkBpc0NiXapQEg4Z/8abcuURLKea9DT8csuansDbRdL3rBJUMMEKy1aKvAWJ6N6HeurtojlBAPCUq8NIxRgxNWivtZJDb+tvkbkyOZ1E6aD+ESIZ0G46uqUECgYEA/Kvr2/LPV45xyiow7WGAU2bs9d0ecj7n9q+O14A5Cb9HNmltBw5x3nVFYMWdV4TErd0vVgjh7rNaAJWjGbuu5a1izQN5WRIhOB6lXcOIuFbxPRfqf4YSeuGmeakDgghCPyNYLCghqXc6fJLNGL0kW4Ee1uKTse1wDikIbxHqmcUCgYEA5bSvwZIO9le/ebBS5KweLJaujSlzRy9dwhhCmmi6c7fu37mn//AAoyc+TQQURQEeST8fmfaZBu5rCVfRuNUJh0nGWCNaB8LjDv8oP+13BbZ973CIr0d08EHfYKg4TWZK0TwZ3u1kC/qHFhAvwH/87t7HrujPl8RDAS42y6K50nECgYEA+G49YCwzyz3GCeVD6BKGrvozIGaBygip4DWq954d9sR4SzqLNXXxZa3eQhDq7CMhkmU4ZpojMqRraMm37NRJWZAJ5dN1m6skuF4Bb9iSc9UgKlZ6fVogj2uQWGxig5aFNDkXN4tZ1ZriCZv5RkqqFcI1xUhPuZDgLTwRC8wM45UCgYBervn7aj8sn8LXYCzrAV92ta3BliJkZ0vYY7OgfibM6DZYleiAAOfiWJy/ljzcrLdVLeXSyukNCF2qfj5+V6IT1/8f+YJ0zIpXdimbIED9Y9nJRrOwvF2hYyW2a+C5Vr7kPEfVsjiZD217VLS6Pod91uqsWF6pikasR+ZiTfMWoQKBgQDlNsko+G3JoY6S/NtMDUNU5CAVqFkAcbltjigOKmQttQBFx+W6WfbhNzrfuOTqA3FBg8P0dn9eunzksDX3nAYp9cx/BZMDX4gU1/GUpeaxIy2bSvIxdrdSbk3mDR8qO8nL5/zlNpnGpd9wwj/dFm3xulUFK2AnzYxiJ60iHfFCYg==';
//	const APPSECRET = 'd23386f6f837453440cc9d2f7c539d13';
    // 迈步 私钥
    const MB_APPID  = "2017091108675479";
    const MB_SECRET = "MIICXgIBAAKBgQC4yBrNmztd7TJTeRkzs6XW32pv4Sq8Uu5SouqZ0Zvtn1v8hedODcdeJp/xdLD+bB0KcwQH7WYs1b7OIp3wMW3w0MDmBh/hpbib2wZoWsK198EY+rAf55VaGduwIB2u1/RemLIgH583z+5P2BQr+SRPoV0LcI+51HD8TFlbBQ9FLQIDAQABAoGAQX/1OFLBXY8aGsq/aztQGvXBJf18B5uiDTrgzDun+ThXBBF3J4zs0ewBIDcMEnPCa5TPpfu6D0SJSUquD5mF7Y+ccAR6wI6igpaCqWCWgx/X7FbvKhBGjOm2pfPqck6S6zQOof906BcA3hBJOkODZPLOoo+rXpqwTi3RcV8GweECQQDoJXrt75vNI3utkkk7P3MhHOFvD81g3vYw6wPsx47cMyOnYYgya0gJ2o3U0DtUIFieewdACxoYVBfkQcsMewaFAkEAy8S2pZi9X4H4jN71mQ/X40UD6IziL5sD3qyvNrcQM/ErLHLacNtjB8cGy+B7C2ozfEf3lF5tLO8jvy9zfaQoiQJBANsN6ua0b67t6ZmKbUHUCH5ZczvKjID5QxQ735NBZ0PPmbgq50q0QuDRc346E5G5iAXbj6bWEwSb7YN8te4L9MUCQQCVJulWr0W2uikX3D/DiQBKgAMLXsxVck9T1+zszPTUQGyMvYk9YKjNUZac9zS5t0P2batAdBnP8T+mOvJ7fgSZAkEA5qqxzlw6CKKJzYG5i1HiOKQ+X38SuN8UCsvyzCYbszYftnElQ1M8c9p5NeNiiUd0oMVrgcXpBEzbhOhMa3BRZQ==";
}
