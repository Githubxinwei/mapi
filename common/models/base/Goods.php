<?php
	
	namespace common\models\base;
	
	use Yii;
	
	/**
	 * This is the model class for table "{{%goods}}".
	 *
	 * @property string $id 自增ID
	 * @property string $goods_type_id 商品类型id
	 * @property string $goods_brand 商品品牌
	 * @property string $goods_name 商品名称
	 * @property string $unit_price 商品单价
	 * @property string $goods_model 商品型号
	 * @property string $goods_producer 商品生产商
	 * @property string $goods_supplier 商品供应商
	 * @property string $create_time 创建时间
	 * @property string $venue_id 场馆id
	 * @property string $company_id 公司id
	 * @property int $goods_attribute 1:商品 2:赠品
	 * @property string $unit 商品单位
	 * @property string $store_id 仓库id
	 * @property int $department_id 部门id
	 * @property int $type 类型 1 仓储类型 2 餐饮商品类
	 * @property int $supplier_type 供货方 1 自己 2 第三方
	 * @property string $supplier_id 第三方供应商ID
	 * @property string $describe 描述
	 * @property string $note 说明
	 * @property string $group_ids 分组 关联cloud_group表
	 * @property string $sell_venue_ids 在售场馆IDs
	 * @property string $pic_ids 图片IDs 关联cloud_image_management表
	 * @property string $video 视频URL
	 * @property int $shelf_type 上架状态 1 立即上架, 2 暂不上架, 3 定时上架
	 * @property string $shelf_time 当上架状态为3时，存在上架时间
	 * @property string $update_at 更新时间
	 * @property string $create_id 创建人ID
	 * @property string $details 详情
	 * @property string $standardSet 规格名和规格值
	 * @property int $is_delete 软删标记 0 未删除 1 已删除
	 */
	class Goods extends \yii\db\ActiveRecord
	{
		/**
		 * @inheritdoc
		 */
		public static function tableName()
		{
			return '{{%goods}}';
		}
		
		/**
		 * @inheritdoc
		 */
		public function rules()
		{
			return [
				[['goods_type_id', 'create_time', 'venue_id', 'company_id', 'goods_attribute', 'store_id', 'department_id', 'type', 'supplier_type', 'supplier_id', 'shelf_type', 'create_id', 'is_delete'], 'integer'],
				[['shelf_time', 'update_at'], 'safe'],
				[['details', 'standardSet'], 'string'],
				[['goods_brand', 'goods_name', 'unit_price', 'goods_model', 'goods_producer', 'goods_supplier', 'video'], 'string', 'max' => 200],
				[['unit'], 'string', 'max' => 3],
				[['describe', 'note', 'group_ids', 'sell_venue_ids'], 'string', 'max' => 255],
				[['pic_ids'], 'string', 'max' => 110],
			];
		}
		
		/**
		 * @inheritdoc
		 */
		public function attributeLabels()
		{
			return [
				'id' => 'ID',
				'goods_type_id' => 'Goods Type ID',
				'goods_brand' => 'Goods Brand',
				'goods_name' => 'Goods Name',
				'unit_price' => 'Unit Price',
				'goods_model' => 'Goods Model',
				'goods_producer' => 'Goods Producer',
				'goods_supplier' => 'Goods Supplier',
				'create_time' => 'Create Time',
				'venue_id' => 'Venue ID',
				'company_id' => 'Company ID',
				'goods_attribute' => 'Goods Attribute',
				'unit' => 'Unit',
				'store_id' => 'Store ID',
				'department_id' => 'Department ID',
				'type' => 'Type',
				'supplier_type' => 'Supplier Type',
				'supplier_id' => 'Supplier ID',
				'describe' => 'Describe',
				'note' => 'Note',
				'group_ids' => 'Group Ids',
				'sell_venue_ids' => 'Sell Venue Ids',
				'pic_ids' => 'Pic Ids',
				'video' => 'Video',
				'shelf_type' => 'Shelf Type',
				'shelf_time' => 'Shelf Time',
				'update_at' => 'Update At',
				'create_id' => 'Create ID',
				'details' => 'Details',
				'standardSet' => 'Standard Set',
				'is_delete' => 'Is Delete',
			];
		}
	}