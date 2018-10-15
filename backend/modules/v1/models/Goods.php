<?php
	
namespace backend\modules\v1\models;

use Yii;
	

class Goods extends \common\models\Goods
{
	public function fields(){
		return [
			'id',
			'goods_name',
			'unit_price' => function($model){
				$money = GoodsStandard::find()->select('price')->where(['goods_id'=>$model->id])->asArray()->one();
				if (!empty($money)){
					return $money['price'];
				}
				return '';

			},
			'venues' => function($model){
				if (empty($model->sell_venue_ids)) return [];
				$ids = explode(',',$model->sell_venue_ids);
				$venues = Organization::find()->select('name')->where(['id'=>$ids])->asArray()->all();
				return array_column($venues,'name');
			},
			'pic' => function($model){
				if (empty($model->pic_ids)) return [];
				$ids = explode(',',$model->pic_ids);
				$imgs = ImageManagement::find()->where(['id'=>$ids])->select('url')->asArray()->one();
				if (isset($imgs['url']) && !empty($imgs['url'])){
					return $imgs['url'];
				}
				return '';
			},
            'standardSet',
			'spec' => function($model){
				if (empty(json_decode($model->standardSet,true))) {
					return 0;
				}
				return 1;
			},
		];
	}
}