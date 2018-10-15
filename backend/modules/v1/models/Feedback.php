<?php
namespace backend\modules\v1\models;

use common\models\FeedbackType;

class Feedback extends \common\models\Feedback
{
    public function fields(){
        return [
            'id',
            'type_id',
            'from',
            'company_id',
            'venue_id',
            'user_id',
            'content',
            'occurred_at',
            'created_at'=>function($model){
                return date('m月d日 H:i', $model->created_at);
            },
            'updated_at',
            'pics'=>function($model){
                return json_decode($model->pics);
            },
            'reply_time'=>function($model){
                return date('m月d日 H:i', $model->reply_time);
            },
            'reply_content',
            'reply_person',
            'is_read'
        ];
    }
    public function rules()
    {
        return [
            [['type_id','from','venue_id','user_id','content'], 'required'],
            [['type_id', 'company_id', 'venue_id', 'user_id', 'occurred_at'], 'integer'],
            [['content','tags'], 'string'],
            ['from', 'in', 'range' =>['android_customer', 'ios_customer']],
            ['company_id', 'default', 'value'=>1],
            ['pics', 'file', 'extensions'=>'jpg,jpeg,png,gif', 'maxFiles'=>4, 'maxSize'=>2048*1024],
            ['type_id', 'validateTypeId'],
        ];
    }

    public function beforeSave($insert)
    {
        if($insert && $this->validate() && isset($this->pics)){
            $pics = [];
            foreach ($this->pics as $pic){
                $imgName = uniqid(md5(microtime(true))) . '.' . $pic->extension;
                $err = Func::uploadFile($pic->tempName, $imgName);
                if(!empty($err)){
                    $this->addErrors(['pics'=>'上传失败']);
                    return FALSE;
                }
                $pics[] = Func::getImgUrl($imgName);
            }
            $this->pics = json_encode($pics);
        }
        return parent::beforeSave($insert);
    }

    public function validateTypeId($attribute)
    {
        if(!$this->hasErrors()){
            $type = FeedbackType::findOne(['id'=>$this->type_id]);
            if(empty($type)) $this->addError($attribute, '类型ID不符');
        }
    }
}