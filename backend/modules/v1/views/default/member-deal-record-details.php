<?php
/**
 * Created by PhpStorm.
 * User: Xin Wei
 * Date: 2018/7/16
 * Time: 15:40
 * Desc:会员合同记录详情
 */
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
    <p><span>甲方：</span><?=$arr['username']?></p>
    <p><span>乙方：</span><?=$arr['venueName']?></p>
    <p><span>身份证号：</span><?=$arr['id_card']?></p>
    <p><span>手机号：</span><?=$arr['mobile']?></p>
    <p><span>订单编号：</span><?=!empty($arr['order_number'])?$arr['order_number']:$arr['orderNumber']?></p>
    <?php if ($arr['type'] == 1){ ?>
        <p><span>会员卡名称：</span><?=$arr['card_name']?></p>
        <p><span>会员卡类型：</span>
           <?php
            if ($arr['card_type'] == 1) {
                echo '瑜伽';
            } elseif ($arr['card_type'] == 2) {
                echo '健身';
            } elseif ($arr['card_type'] == 3) {
                echo '舞蹈';;
            } elseif ($arr['card_type'] == 4) {
                echo '综合';
            } elseif ($arr['card_type'] == 5) {
                echo '体验';
            }
           ?>
        </p>
        <p><span>金额：</span><?=$arr['totalPrice']?> 元</p>
        <p><span>有效期：</span><?=$arr['duration']?> 天</p>
    <?php } elseif (($arr['type'] == 2) && ($arr['consumption_type'] == 'chargeGroup')){?>
        <p><span>课程名称：</span><?=$arr['product_name']?></p>

    <?php } elseif ((($arr['type'] == 2)) && ($arr['consumption_type'] == 'charge')){?>
        <p><span>课程名称：</span><?=$arr['product_name']?></p>
        <p><span>课程节数：</span><?php $data = \backend\models\MemberCourseOrder::find()->alias('mco')->select('mco.deadline_time,mco.product_name,mco.money_amount,mco.set_number ,sum(mco.money_amount) as money ,mco.id')
                ->joinWith('memberCourseOrderDetails mcod')
                ->where(['mco.id'=>$arr['consumption_type_id'],'mco.pay_status'=>1])->groupBy('mco.product_name')->asArray()->all();
            foreach ($data as $a){
                echo $a['product_name'].'&nbsp&nbsp&nbsp'.$a['money_amount'] / $a['set_number'] .'元/节&nbsp共'.$a['money'].'元<br>';
            }
            ?></p>
        <p><span>金额：</span><?=$arr['total_price']?> 元</p>
        <p><span>有效期：</span><?=date('Y-m-d',$arr['deadline_time'])?></p>
    <?php } ?>



</header>
<div class="xyName"><?=$arr['dealName']?></div>
<div class="  ">
    <p><span>合同编号：</span><?=$arr['deal_number']?> </p>
    <p><span>签订时间：</span><?=date('Y-m-d',$arr['create_at'])?></p>
</div>
<main>
    <?=$arr['intro']?>
</main>
<footer>
    <?php if (!empty($arr['sign']) || !empty($arr['orderSign'])){?>
        <div>甲方：<img style="width: 30%;vertical-align: middle;" src="<?=isset($arr['sign']) ? $arr['sign'] : $arr['orderSign']?>" /></div>
    <?php } ?>
</footer>
</body>
</html>