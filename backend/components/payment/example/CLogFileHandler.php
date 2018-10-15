<?php
/**
 * Created by PhpStorm.
 * User: lihuien
 * Date: 2017/7/7
 * Time: 20:30
 */

namespace backend\components\payment\example;
class CLogFileHandler implements ILogHandler
{
    private $handle = null;

    public function __construct($file = '')
    {
        $this->handle = fopen($file,'a');
    }

    public function write($msg)
    {
        fwrite($this->handle, $msg, 4096);
    }

    public function __destruct()
    {
        fclose($this->handle);
    }
}