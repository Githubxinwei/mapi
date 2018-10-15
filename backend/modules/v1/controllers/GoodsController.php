<?php
namespace backend\modules\v1\controllers;

use backend\modules\v1\models\Goods;
use backend\modules\v1\models\GoodsDetail;
use backend\modules\v1\models\GoodsStandard;
use backend\modules\v1\models\Group;
use backend\modules\v1\models\ImageManagement;
use backend\modules\v1\models\Order;
use Yii;
use yii\data\ActiveDataProvider;

class GoodsController extends BaseController
{
	public $modelClass = 'backend\modules\v1\models\Goods';
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create'],$actions['delete'],$actions['view']);
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }
	/**
	 * @api {get} /v1/goods   获取商品列表
	 * @apiVersion  1.8.1
	 * @apiName        获取商品列表
	 * @apiGroup       goods
	 * @apiPermission 管理员
	 * @apiParam  {int}          venueId            场馆ID
	 * @apiParam  {int}          pid           		分类父ID
	 * @apiParam  {int}          id                分类ID
	 * @apiParamExample {json} 请求参数
	 *   GET /v1/goods
	 *   {
	 *        "venueId":59                         //场馆ID
	 *   }
	 * @apiDescription   获取商品列表
	 * <br/>
	 * <span><strong>作    者：</strong></span>辛威<br/>
	 * <span><strong>邮    箱：</strong></span>xinwei@itsprts.club
	 * <span><strong>创建时间：</strong></span>2018/07/12
	 * @apiSampleRequest  http://qamemberapi.xingfufit.cn/v1/goods
	 * @apiSuccess (返回值) {string} data
	 * @apiSuccessExample {json}返回值详情(成功时)
	 * {
	 *  "code": 1,              //成功标识
	 *  "status": "success",    //成功标识
	 *  "message":"请求成功！"    //成功提示信息
	 * }
	 * @apiSuccessExample {json}返回值详情(失败时)
	 * {
	 *  "code": 0,              //失败标识
	 *  "status": "error",      //失败标识
	 *  "message":"网络错误，请稍后重试!"    //失败提示信息
	 * }
	 */
	public function prepareDataProvider()
	{
		$pid = Yii::$app->request->get('pid', 0);
		$id = Yii::$app->request->get('id', 0);
		$venueId = Yii::$app->request->get('venueId', 0);
		$query = Goods::find()->alias('gd')->where(['gd.type'=>2,'gd.shelf_type'=>1,'gd.is_delete'=>0]);
		if ($venueId) $query->andWhere("FIND_IN_SET(".$venueId.",gd.sell_venue_ids)");
		if ($pid !==0 && $id !==0){
			$query->andWhere("FIND_IN_SET(".$pid.",gd.group_ids)")->andWhere("FIND_IN_SET(".$id.",gd.group_ids)");
		}elseif ($pid ==0 && $id ==0){

		}else{
			$ids = '';
			if ($pid !==0){
				$ids = 	$pid;
			}
			if ($id !==0){
				$ids = 	$id;
			}
			$query->andWhere("FIND_IN_SET(".$ids.",gd.group_ids)");
		}
		return  new ActiveDataProvider(['query' => $query]);
	}
	/**
	 * @api {get} /v1/goods/goods-specifications   获取商品规格
	 * @apiVersion  1.8.1
	 * @apiName        获取商品规格
	 * @apiGroup       goods-specifications
	 * @apiPermission 管理员
	 * @apiParam  {int}          id            商品id
	 * @apiParamExample {json} 请求参数
	 *   GET /v1/goods/goods-specifications
	 *   {
	 *        "id":23                         	//商品id
	 *   }
	 * @apiDescription   获取商品规格
	 * <br/>
	 * <span><strong>作    者：</strong></span>辛威<br/>
	 * <span><strong>邮    箱：</strong></span>xinwei@itsprts.club
	 * <span><strong>创建时间：</strong></span>2018/07/12
	 * @apiSampleRequest  http://qamemberapi.xingfufit.cn/v1/goods/goods-specifications
	 * @apiSuccess (返回值) {string} data
	 * @apiSuccessExample {json}返回值详情(成功时)
	 * {
	 *  "code": 1,              //成功标识
	 *  "status": "success",    //成功标识
	 *  "message":"请求成功！"    //成功提示信息
	 * }
	 * @apiSuccessExample {json}返回值详情(失败时)
	 * {
	 *  "code": 0,              //失败标识
	 *  "status": "error",      //失败标识
	 *  "message":"网络错误，请稍后重试!"    //失败提示信息
	 * }
	 */
	public function actionGoodsSpecifications(){
        $id = Yii::$app->request->get('id', 0);
        if (!$id) return $this->error('请选择商品!');
		$Specifications = Goods::find()->where(['id'=>$id])->asArray()->one();
		$unit_price = $Specifications['unit_price'];
		
		$money = GoodsStandard::find()->select('price')->where(['goods_id'=>$id])->asArray()->one();
		if (!empty($money)){
			$unit_price =  $money['price'];
		}else{
			$unit_price =  '';
		}
		$imgsid = $Specifications['pic_ids'];
		if (empty($imgsid)){
			$img = '';
		}else{
			$ids = explode(',',$imgsid);
			$img = ImageManagement::find()->select('url')->where(['id'=>$ids])->asArray()->one();
			$id = Yii::$app->request->get('id', 0);
		}
		
		$Specifications = $Specifications['standardSet'];
		$Specifications =  !empty(json_decode($Specifications,true))?json_decode($Specifications,true):[];
		return ['id'=>$id,'img'=>$img,'unit_price'=>$unit_price,'standardSet'=>$Specifications];
	}
	/**
	 * @api {post} /v1/goods/get-money   获取商品价格
	 * @apiVersion  1.8.1
	 * @apiName        获取商品价格
	 * @apiGroup       get-money
	 * @apiPermission 管理员
	 * @apiParam  {int}          id            商品id
	 * @apiParam  {int}          keyword       关键字(standard)
	 * @apiParamExample {json} 请求参数
	 *   POST /v1/goods/get-money
	 *   {
	 *        "id":23                         	//商品id
	 *   }
	 * @apiDescription   获取商品价格
	 * <br/>
	 * <span><strong>作    者：</strong></span>辛威<br/>
	 * <span><strong>邮    箱：</strong></span>xinwei@itsprts.club
	 * <span><strong>创建时间：</strong></span>2018/07/12
	 * @apiSampleRequest  http://qamemberapi.xingfufit.cn/v1/goods/get-money
	 * @apiSuccess (返回值) {string} data
	 * @apiSuccessExample {json}返回值详情(成功时)
	 * {
	 *  "code": 1,              //成功标识
	 *  "status": "success",    //成功标识
	 *  "message":"请求成功！"    //成功提示信息
	 * }
	 * @apiSuccessExample {json}返回值详情(失败时)
	 * {
	 *  "code": 0,              //失败标识
	 *  "status": "error",      //失败标识
	 *  "message":"网络错误，请稍后重试!"    //失败提示信息
	 * }
	 */
	public function actionGetMoney(){
		$id = Yii::$app->request->post('id', 0);
		$keyword = Yii::$app->request->post('keyword', 0);
		if (empty($keyword)) return $this->error('参数异常!');
		$money = GoodsStandard::find()->where(['goods_id'=>$id])->andWhere(['like', 'standard', $keyword])->asArray()->one();
		if (!empty($money)) {
			return ['price' => $money['price']];
		}
		return ['price' => "0"];
	}
	/**
	 * @api {get} /v1/goods/goods-group   获取商品分类
	 * @apiVersion  1.8.1
	 * @apiName        获取商品分类
	 * @apiGroup       goods-group
	 * @apiPermission 管理员
	 * @apiDescription   获取商品分类
	 * <span><strong>作    者：</strong></span>辛威<br/>
	 * <span><strong>邮    箱：</strong></span>xinwei@itsprts.club
	 * <span><strong>创建时间：</strong></span>2018/07/12
	 * @apiSampleRequest  http://qamemberapi.xingfufit.cn/v1/goods/goods-group
	 * @apiSuccess (返回值) {string} data
	 * @apiSuccessExample {json}返回值详情(成功时)
	 * {
	 *  "code": 1,              //成功标识
	 *  "status": "success",    //成功标识
	 *  "message":"请求成功！"    //成功提示信息
	 * }
	 * @apiSuccessExample {json}返回值详情(失败时)
	 * {
	 *  "code": 0,              //失败标识
	 *  "status": "error",      //失败标识
	 *  "message":"网络错误，请稍后重试!"    //失败提示信息
	 * }
	 */
	public function actionGoodsGroup(){
		$group = Group::find()->select('id,pid,title')->where(['is_delete'=>0])->asArray()->all();
		return array_values(self::RunNewData($group));
	}
	protected function error($msg, $data=[], $code=0)
	{
		$return = ['code'=>$code, 'status'=>'error', 'message'=>$msg];
		if(!empty($data)) $return['data'] = $data;
		return $return;
	}
	public static function RunNewData($data)
	{
		$new_data =[];
        $new_data['title'] = [
            'id' => '',
            'pid' => '',
            'title' => '全部',
            'children' => []
        ];
		foreach ($data as $key => $value)
		{
			if($value['pid'] == 0)
			{
				if(!in_array($value, $new_data))
				{
					$new_data[$key] = $value;
					foreach($data as $k => $v)
					{
						if($value['id'] == $v['pid'])
						{
							$new_data[$key]['children'][] = $v;
						}
//                        self::$new_data[$key]['a'] = false;

					}
					if(empty($new_data[$key]['children'])){
                        $new_data[$key]['children'] = [];
                    }

				}
			}
		}
		return $new_data;
	}
	/**
	 * @api {get} /v1/goods/get-goods-detail   获取商品详情
	 * @apiVersion  1.8.1
	 * @apiName        获取商品详情
	 * @apiGroup       get-goods-detail
	 * @apiPermission 管理员
	 * @apiParam  {int}          id            商品id
	 * @apiParamExample {json} 请求参数
	 *   GET /v1/goods/get-goods-detail
	 *   {
	 *        "id":23                         	//商品id
	 *   }
	 * @apiDescription   获取商品详情
	 * <br/>
	 * <span><strong>作    者：</strong></span>辛威<br/>
	 * <span><strong>邮    箱：</strong></span>xinwei@itsprts.club
	 * <span><strong>创建时间：</strong></span>2018/07/12
	 * @apiSampleRequest  http://qamemberapi.xingfufit.cn/v1/goods/get-goods-detail
	 * @apiSuccess (返回值) {string} data
	 * @apiSuccessExample {json}返回值详情(成功时)
	 * {
	 *  "code": 1,              //成功标识
	 *  "status": "success",    //成功标识
	 *  "message":"请求成功！"    //成功提示信息
	 * }
	 * @apiSuccessExample {json}返回值详情(失败时)
	 * {
	 *  "code": 0,              //失败标识
	 *  "status": "error",      //失败标识
	 *  "message":"网络错误，请稍后重试!"    //失败提示信息
	 * }
	 */
	public function actionGetGoodsDetail(){
		$goods_id = Yii::$app->request->get('id',0);
		if (!$goods_id) return $this->error('请选择商品!');
		$query = GoodsDetail::find()->where(['id'=>$goods_id]);
		$data =  new ActiveDataProvider(['query'=>$query]);
		return $data->getModels();
	}
}
