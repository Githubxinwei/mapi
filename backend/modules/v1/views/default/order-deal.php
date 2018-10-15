<?php
use common\models\Func;
Yii::$app->layout = NULL;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>协议</title>
    <style type="text/css">
        header h2{
            font-size: 18px;
            color: #4389FF;
        }
        header p{
            color: #666666;
            font-size: 14px;
            margin:  0 0 10px 0;
        }
        header span{
            color: #999999;
        }
        .xyName{
            width: 80%;
            height: 50px;
            text-align: center;
            line-height: 50px;
            background: #146CFF;
            color: #FFFFFF;
            font-size: 16px;
            border-radius: 4px;
        }
        .numAndTime p{
            color: #666666;
            font-size: 12px;
            margin: 10px 0 0 0;
        }
        .numAndTime span{
            color: #999999;
        }
        main h3{
            font-size: 14px;
            color: #333333;
            margin-bottom: 0;
        }
        main p{
            color: #666666;
            font-size: 14px;
            margin-top: 0;
        }
        footer div{
            font-size: 14px;
            color: #333333;
            display: inline-block;
        }
        .bottom{
            font-size: 14px;
            color: #333333;
        }
    </style>
</head>

<body>
<header>
    <h2>订单详情</h2>
    <p><span>甲方：</span><?=$name?></p>
    <p><span>乙方：</span><?=Func::getRelationVal($model, 'company', 'name')?></p>
    <p><span>身份证号：</span><?=Func::getRelationVal($model, 'memberDetails', 'id_card')?></p>
    <p><span>手机号：</span><?=$mobile;?></p>
    <p><span>订单编号：</span><?=$model->order_number?></p>
    <?php if ($model->consumption_type == 'card' || $model->consumption_type == ''){ ?>
    <p><span>会员卡名称：</span><?=$model->product_name?></p>
    <p><span>会员卡类型：</span><?=$card_name?></p>
        <p><span>金额：</span><?=$model->total_price?> 元</p>
        <p><span>有效期：</span><?=Func::getRelationVal($model, 'memberCard', 'duration')?> 天</p>
    <?php } elseif ($model->consumption_type == 'chargeGroup'){?>
        <p><span>课程名称：</span><?=$model->product_name?></p>

    <?php } elseif ($model->consumption_type == 'charge'){?>
        <p><span>课程名称：</span><?=$model->product_name?></p>
        <p><span>课程节数：</span><?php $data = \backend\models\MemberCourseOrder::find()->alias('mco')->select('mco.deadline_time,mco.product_name,mco.money_amount,mco.set_number ,sum(mco.money_amount) as money ,mco.id')
                ->joinWith('memberCourseOrderDetails mcod')
                ->where(['mco.id'=>$model->consumption_type_id,'mco.pay_status'=>1])->groupBy('mco.product_name')->asArray()->all();
            foreach ($data as $a){
                echo $a['product_name'].'&nbsp&nbsp&nbsp'.$a['money_amount'] / $a['set_number'] .'元/节&nbsp共'.$a['money'].'元<br>';
            }
            ?></p>
        <p><span>金额：</span><?=$model->total_price?> 元</p>
        <p><span>有效期：</span><?=date('Y-m-d',Func::getRelationVal($model, 'memberCourseOrder', 'deadline_time'))?></p>
    <?php } ?>



</header>
<div class="xyName"><?=Func::getRelationVal($model, 'deal', 'name')?></div>
<div class="  ">
    <p><span>合同编号：</span><?=Func::getRelationVal($model, 'deal', 'deal_number')?> </p>
    <p><span>签订时间：</span><?=date('Y-m-d',$model->pay_money_time)?></p>
</div>
<main>
    <?=Func::getRelationVal($model, 'deal', 'intro')?>
</main>
<footer>
    <?php if (!empty($model->sign)){?>
    <div>甲方：<img style="width: 30%;vertical-align: middle;" src="<?=isset($model->sign) ?$model->sign:''?>" /></div>
    <?php } ?>
</footer>
</body>
</html>


