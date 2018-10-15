<?php
namespace backend\modules\v1\models;

use yii\base\Model;
use Yii;

class UploadForm extends Model
{
    public $file;

    public function rules()
    {
        return [
            ['file', 'file', 'skipOnEmpty' => false, 'maxSize'=>2048*1024],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $imgName = uniqid(md5(microtime(true))) . '.' . $this->file->extension;
            $err = Func::uploadFile($this->file->tempName, $imgName);
            if(!empty($err)){
                $this->addErrors(['file'=>'上传失败']);
                return FALSE;
            }
            return ['url'=>Func::getImgUrl($imgName)];
        } else {
            return FALSE;
        }
    }
}