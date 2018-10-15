<?php
/**
 * Created by PhpStorm.
 * User: lihuien
 * Date: 2017/4/25
 * Time: 17:22
 */

namespace backend\modules\v1\models;

use common\models\relations\EvaluateRelations;
use common\models\MemberCourseOrderDetails;
use yii\base\Model;
use yii\data\Pagination;
class Evaluate extends \common\models\Evaluate
{
    public $member_id;
    public $consumption_type;
    public $display_status;
    public $venue_id;
    public $company_id;
    public $content;
    public $label_id;
    public $star_level;
    public $consumption_type_id;
    public $enclosure;

    public function rules()
    {
        return [
            [['member_id','consumption_type','consumption_type_id','display_status','venue_id','company_id','content','label_id','star_level'], 'required'],
            [['company_id', 'venue_id' ,'star_level',], 'integer'],
            [['content','label_id'], 'string'],
            ['consumption_type', 'in', 'range' =>['teamClass', 'privateClass','smallClass']],
            ['company_id', 'default', 'value'=>1],
            [['enclosure'], 'file','skipOnEmpty' => false, 'extensions'=>'jpg,jpeg,png,gif', 'maxFiles'=>4, 'maxSize'=>2048*1024],
        ];
    }

    public function beforeSave($insert)
    {
        if($insert && $this->validate() && isset($this->enclosure)){
            $enclosure = [];
            foreach ($this->enclosure as $pic){
                $imgName = uniqid(md5(microtime(true))) . '.' . $pic->extension;
                $err = Func::uploadFile($pic->tempName, $imgName);
                if(!empty($err)){
                    $this->addErrors(['enclosur'=>'上传失败']);
                    return FALSE;
                }
                $pics[] = Func::getImgUrl($imgName);
            }
            $this->enclosure = json_encode($pics);
        }
        return parent::beforeSave($insert);
    }

    /**
     * @param $courseId 团课私课id
     * @param $type teamClass,privateClass
     * @param $venueId
     * @return
     */
    public function getAllEvaluate($courseId,$type,$venueId,$page,$pageSize){
        $courseId = [31449,26852,25835,31589];
        $query =\common\models\Evaluate::find()
            ->alias('e')
            ->joinWith('memberDetails md',false)
            ->where(['consumption_type_id'=>$courseId,'consumption_type'=>$type,'venue_id'=>$venueId])
            ->orderBy('e.create_at desc')
            ->select('e.content,e.star_level,e.create_at,e.enclosure,md.nickname,md.motto');
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>2]);
        $models = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->asArray()
            ->all();
        foreach ($models as $v){
            $data['items'][] = [
                'star_level'=>$v['star_level'],
                'create_at'=>date('Y.m.d',$v['create_at']),
                'enclosure'=> $v['enclosure']=='null' ? null : \Qiniu\json_decode($v['enclosure']),
                'nickname'=>$v['nickname']=='' ? '默认昵称' : $v['nickname'],
                'content'=>$v['content'],
                'motto'=>$v['motto']== 0 ? '' : $v['motto'],
            ];
        }
        $data['_links']['self']['href'] = $pages->createUrl($page,$pageSize,true);
        $data['_meta']['totalCount'] = $countQuery->count();
        $data['_meta']['pageCount'] = $pages->getPageCount();
        $data['_meta']['currentPage'] = $pages->getPage();
        $data['_meta']['perPage'] = $pages->getPageSize();
        return $data;
    }
}