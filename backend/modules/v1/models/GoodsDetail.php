<?php

namespace backend\modules\v1\models;

use Yii;
use common\models\Func;

class GoodsDetail extends \common\models\Goods
{
	public function fields(){
		return [
			'id',
			'goods_name',
			'monthSaleNum' => function($model) {
				$num = $this->getMealMonthSaleNum($model->id);
				if (!empty($num)){
					return $num;
				}
				return '';
			},
			'unit_price'=>function($model){
				$money = GoodsStandard::find()->select('price')->where(['goods_id'=>$model->id])->asArray()->one();
				if (!empty($money)){
					return $money['price'];
				}
				return '';
				
			},
			'spec' => function($model){
				$standard = GoodsStandard::find()->select('standard')->where(['goods_id'=>$model->id])->asArray()->one();
				if (!empty($standard)){
					return 1;
				}
				return 0;
			},
			'venues'=>function($model){
				if (empty($model->sell_venue_ids)) return [];
				$ids = explode(',',$model->sell_venue_ids);
				$venues = Organization::find()->select('name')->where(['id'=>$ids])->asArray()->all();
				return array_column($venues,'name');
			},
			'pics'=>function($model){
				if (empty($model->pic_ids)) return [];
				$ids = explode(',',$model->pic_ids);
				$imgs = ImageManagement::find()->where(['id'=>$ids])->select('url')->asArray()->all();
				if (isset($imgs) && !empty($imgs)){
					$url = [];
					foreach ($imgs as $k => $v) {
						$url[] = $v['url'];
					}
					return $url;
				}
				return '';
			},
			'video',
			'describe',
			'details',
		];
	}
	/**
	 * 会员端 - API - 计算健康餐月售量
	 * @author  xinwei <xinwei@itsport.club>
	 * @create 2018/07/11
	 */
	public function getMealMonthSaleNum($goods_id)
	{
		$date = date('Y-m-d',time());
		$curMonthFirstDay = Func::getCurMonthFirstDay($date);
		$curMonthLastDay = Func::getCurMonthFirstDay($date);
		$num = Order::find()
			->alias('o')
			->joinWith(['memberMealRecord mmr'],FALSE)
			->where(['mmr.goods_id' => $goods_id,'o.status' => 9])
			->andWhere(['between',"DATE_FORMAT(from_unixtime(o.order_time),'%Y-%m-%d')",$curMonthFirstDay,$curMonthLastDay])
			->select('mmr.id')
			->count();
		return $num;
	}
}