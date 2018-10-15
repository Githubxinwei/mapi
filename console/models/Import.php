<?php
namespace console\models;
class Import extends \yii\base\Model
{
     public static function Handle()
     {
         return [
//             ['./console/data/20170801/20170807fq/fqxiaoshou0725.xls','丰庆路','丰庆路游泳健身馆','one'],
//             ['./console/data/20170801/20170807fq/fqxiaoshou0727.xls','丰庆路','丰庆路游泳健身馆','one'],
//             ['./console/data/20170801/20170813fq/fqxiaoshou0809.xls','丰庆路','丰庆路游泳健身馆','one'],
//             ['./console/data/20170801/20170813fq/fqxiaoshou0810.xls','丰庆路','丰庆路游泳健身馆','one'],
//             ['./console/data/20170801/fq20170920/fqxiaoshou811.xls','丰庆路','丰庆路游泳健身馆','one'],
//             ['./console/data/20170801/fq20170920/fqxiaoshou814.xls','丰庆路','丰庆路游泳健身馆','one'],
//             ['./console/data/20170801/fq20170920/fqxiaoshou815.xls','丰庆路','丰庆路游泳健身馆','one'],
//             ['./console/data/20170801/fq20170920/fqxiaoshou817.xls','丰庆路','丰庆路游泳健身馆','one'],
//             ['./console/data/20170801/fq20170920/fqxiaoshou818.xls','丰庆路','丰庆路游泳健身馆','one'],
//             ['./console/data/20170801/fq20170920/fqxiaoshou821.xls','丰庆路','丰庆路游泳健身馆','one'],
//             ['./console/data/20170801/fq20170920/fqxiaoshou822.xls','丰庆路','丰庆路游泳健身馆','one'],
//             ['./console/data/20170801/fq20170920/fqxiaoshou823.xls','丰庆路','丰庆路游泳健身馆','one'],
//             ['./console/data/20170801/fq20170920/fqxiaoshou824.xls','丰庆路','丰庆路游泳健身馆','one'],
//             ['./console/data/20170801/fq20170920/fqxiaoshou825.xls','丰庆路','丰庆路游泳健身馆','one'],
//             ['./console/data/20170801/fq20170920/fqxiaoshou828.xls','丰庆路','丰庆路游泳健身馆','one'],
//             ['./console/data/20170801/fq20170920/fqxiaoshou901.xls','丰庆路','丰庆路游泳健身馆','one'],
//             ['./console/data/20170801/fq20170920/fqxiaoshou904.xls','丰庆路','丰庆路游泳健身馆','one'],
//             ['./console/data/20170801/fq20170920/fqxiaoshou905.xls','丰庆路','丰庆路游泳健身馆','one'],
//             ['./console/data/20170801/fq20170920/fqxiaoshou908.xls','丰庆路','丰庆路游泳健身馆','one'],
//             ['./console/data/20170801/fq20170920/fqxiaoshou912.xls','丰庆路','丰庆路游泳健身馆','one'],
//             ['./console/data/20170801/fq20170920/fqxiaoshou914.xls','丰庆路','丰庆路游泳健身馆','one'],
//             ['./console/data/20170801/fq20170920/fqxiaoshou915.xls','丰庆路','丰庆路游泳健身馆','one'],
//             ['./console/data/20170801/fq20170920/fqxiaoshou918.xls','丰庆路','丰庆路游泳健身馆','one'],
//             ['./console/data/20170801/dv20180917/dvxiaoshou906.xls','大卫城','大卫城店（尊爵汇）','one'],
//             ['./console/data/20170801/dv20180917/dvxiaoshou911.xls','大卫城','大卫城店（尊爵汇）','one'],
//             ['./console/data/20170801/20170907dv/dvxiaoshou830.xls','大卫城','大卫城店（尊爵汇）','one'],
//             ['./console/data/20170801/20170907dv/dvxiaoshou831.xls','大卫城','大卫城店（尊爵汇）','one'],
//             ['./console/data/20170801/20170907dv/dvxiaoshou904.xls','大卫城','大卫城店（尊爵汇）','one'],
//             ['./console/data/20170801/20170907dv/dvxiaoshou905.xls','大卫城','大卫城店（尊爵汇）','one'],
//             ['./console/data/20170801/dsh2017911/dsh919xiaoshou.xls','大上海','大上海瑜伽健身馆','four'],
//             ['./console/data/20170801/dsh2017911/xiaoshou914.xls','大上海','大上海瑜伽健身馆','two'],
//             ['./console/data/20170801/dsh2017911/dsh919xiaoshou.xls','大上海','大上海瑜伽健身馆','two'],
//             ['./console/data/20170607/dashanghaiXiaoALL.xls','大上海','大上海瑜伽健身馆','two'],
//             ['./console/data/20170607/dashanghaiXiaoshou.xls','大上海','大上海瑜伽健身馆','two'],
//               ['./console/data/20170821dxl/dxlxiaoshou0922.xls','大学路','大学路舞蹈健身馆','one'],
//             ['./console/data/20170610/dashanghaiShengji2014.xls','大上海','大上海瑜伽健身馆','two'],
//             ['./console/data/20170613/dashanghaiXiaoshou.xls','大上海','大上海瑜伽健身馆','two'],
//             ['./console/data/20170928dv/dvsale1.xls','大卫城','大卫城店（尊爵汇）','one'],
//             ['./console/data/20171001fq/20170924/salecard.xls','丰庆路','丰庆路游泳健身馆','one'],
//             ['./console/data/20171001fq/20170925/salecard.xls','丰庆路','丰庆路游泳健身馆','one'],
//             ['./console/data/20171001fq/20170926/salecard.xls','丰庆路','丰庆路游泳健身馆','one'],
//             ['./console/data/20171001fq/20170927/salecard.xls','丰庆路','丰庆路游泳健身馆','one'],
//             ['./console/data/20171001fq/20170928/salecard.xls','丰庆路','丰庆路游泳健身馆','one'],
//             ['./console/data/20171001fq/20170929/salecard.xls','丰庆路','丰庆路游泳健身馆','one'],
//             ['./console/data/20171020fq/10.12/10.12salecard.xls','丰庆路','丰庆路游泳健身馆','one'],
//             ['./console/data/20171020fq/10.13-10.16/10.13-10.16salecard.xls','丰庆路','丰庆路游泳健身馆','one'],
//             ['./console/data/20171020fq/10.17/10.17salecard.xls','丰庆路','丰庆路游泳健身馆','one'],
//             ['./console/data/20171013dsh/zhangying/salecard.xlsx','大上海','大上海瑜伽健身馆','five']
//             ['./console/data/20171013dance&yoga/7.13-7.17/7.13-17salecard.xls','大学路','大学路瑜伽健身馆','one'],
//             ['./console/data/20171013dance&yoga/7.20/7.17-7.19salecard.xls','大学路','大学路瑜伽健身馆','one'],
//             ['./console/data/20171013dance&yoga/7.21/7.21salecard.xls','大学路','大学路瑜伽健身馆','one'],
//             ['./console/data/20171013dance&yoga/7.27/7.27salecard.xls','大学路','大学路瑜伽健身馆','one'],
//             ['./console/data/20171013dance&yoga/7.28/7.28salecard.xls','大学路','大学路瑜伽健身馆','one'],
//             ['./console/data/20171013dance&yoga/7.28-8.2/7.1-7.31salecard.xls','大学路','大学路瑜伽健身馆','one'],
//             ['./console/data/20171013dance&yoga/8.7-8.8/8.7-8.8salecard.xls','大学路','大学路瑜伽健身馆','one'],
//             ['./console/data/20171013dance&yoga/8.9-8.10/8.9-8.10salecard.xls','大学路','大学路瑜伽健身馆','one'],
//             ['./console/data/20171013dance&yoga/9.22-26/9.21salecard.xls','大学路','大学路瑜伽健身馆','one'],
//             ['./console/data/20171013dance&yoga/9.22-26/9.24salecard.xls','大学路','大学路瑜伽健身馆','one'],
//             ['./console/data/20171013dance&yoga/2015/2008.1-2017.6salecard1.xls','大学路','大学路瑜伽健身馆','one'],
//             ['./console/data/20171013dance&yoga/2015/2008.1-2017.6salecard2.xls','大学路','大学路瑜伽健身馆','one'],
//             ['./console/data/20171104dv/salecard.xls','大卫城','大卫城店（尊爵汇）','one'],
//             ['./console/data/20171105denis/salecardgold.xls','花园路','花园路丹尼斯店（尊爵汇）','six'],
//             ['./console/data/20171105denis/salecardsingle.xls','花园路','花园路丹尼斯店（尊爵汇）','six'],
//             ['./console/data/20171107fq/salecard10.27-10.30.xls','丰庆路','丰庆路游泳健身馆','one'],
//             ['./console/data/20171021fqleft/9.30-10.9/9.30-10.9salecard.xls','丰庆路','丰庆路游泳健身馆','one'],
//             ['./console/data/20171021fqleft/10.10/10.10salecard.xls','丰庆路','丰庆路游泳健身馆','one'],
//             ['./console/data/20171108fq/10.31salecard.xls','丰庆路','丰庆路游泳健身馆','one'],
//             ['./console/data/20171109dance&yoga/9.28/dancesalecard.xls','大学路舞蹈','大学路舞蹈健身馆','one'],
//             ['./console/data/20171109dance&yoga/9.28/yogasalecard.xls','大学路瑜伽','大学路瑜伽健身馆','one'],
//             ['./console/data/20171108dance&yoga/dancesalecard.xls','大学路舞蹈','大学路舞蹈健身馆','one'],
//             ['./console/data/20171108dance&yoga/yogasalecard.xls','大学路瑜伽','大学路瑜伽健身馆','one'],
//             ['./console/data/20171109dance&yoga/11.5/dancesalecard.xls','大学路舞蹈','大学路舞蹈健身馆','one'],
//             ['./console/data/20171109dance&yoga/11.5/yogasalecard.xls','大学路瑜伽','大学路瑜伽健身馆','one'],
//             ['./console/data/20171109dance&yoga/11.7/dancesalecard.xls','大学路舞蹈','大学路舞蹈健身馆','one'],
//             ['./console/data/20171109dance&yoga/11.7/yogasalecard.xls','大学路瑜伽','大学路瑜伽健身馆','one'],
//             ['./console/data/20171113fq/fqsalecard.xls','丰庆路','丰庆路游泳健身馆','one'],
//             ['./console/data/20171117sun/sunsalecard.xls','丰庆路','丰庆路游泳健身馆','one'],
//             ['./console/data/20171111dv/salecard.xls','大卫城','大卫城店（尊爵汇）','one'],
 //             ['./console/data/20171111dv/salecard.xls','大卫城','大卫城店（尊爵汇）','one'],
//             ['./console/data/20171120fq/salecard.xls','丰庆路','丰庆路游泳健身馆','one'],
//              ['./console/data/20171122dennis/salecardgold.xls','花园路','花园路丹尼斯店（尊爵汇）','one'],
//             ['./console/data/20171122dennis/salecardsingle.xls','花园路','花园路丹尼斯店（尊爵汇）','one'],
//             ['./console/data/20171122dennis/salecardcommon.xls','花园路','花园路丹尼斯店（尊爵汇）','one'],
//             ['./console/data/20171122dennis/third1124.xls','花园路','花园路丹尼斯店（尊爵汇）','one'],
//             ['./console/data/20171124dv/dvsalecard.xls','大卫城','大卫城店（尊爵汇）','one'],
//             ['./console/data/20171129dv/addsalecard.xls','大卫城','大卫城店（尊爵汇）','one'],
//             ['./console/data/20171122dennis/first1202.xls','花园路','花园路丹尼斯店（尊爵汇）','one'],//花丹一期修改卡名称
//             ['./console/data/20171122dennis/second1202.xls','花园路','花园路丹尼斯店（尊爵汇）','one'],//花丹二期导入
//             ['./console/data/20171122dennis/third1202.xls','花园路','花园路丹尼斯店（尊爵汇）','one'],//花丹三期新增导入
//             ['./console/data/20171116dh/sale1206.xls','帝湖瑜伽','帝湖瑜伽健身馆','two'],
//             ['./console/data/20171122dennis/third1206.xls','花园路','花园路丹尼斯店（尊爵汇）','one'],//花丹三期漏导数据
             ['./console/data/20171220dennis/salecard0202.xls','花园路','花园路丹尼斯店（尊爵汇）','one'],
         ];
     }
     public static function HandleMobile()
    {
        return [
//            ['./console/data/20170801/20170807fq/fqmobile0725.xls','丰庆路'],
//            ['./console/data/20170801/dv20180917/dvmobile906.xls','大卫城'],
//            ['./console/data/20170801/dv20180917/dvmobile911.xls','大卫城'],
//            ['./console/data/20170801/20170907dv/dvmobile831.xls','大卫城'],
//            ['./console/data/20170801/20170907dv/dvmobile903.xls','大卫城'],
//            ['./console/data/20170801/20170907dv/dvmobile904.xls','大卫城'],
//            ['./console/data/20170801/20170907dv/dvmobile905.xls','大卫城'],
//             ['./console/data/20170821dxl/dxlmobile0922.xls','大学路舞蹈'],
//              ['./console/data/20170602/dihuMobile.xls','帝湖'],
//            ['./console/data/20170601/dashanghaiMobile.xls','大上海'],
//            ['./console/data/dashanghaiMobile.xls','大上海'],
//            ['./console/data/fengqingMobile.xls','丰庆路'],
//            ['./console/data/20171013dance&yoga/2015/2008.1-2017.6mobile1.xls','大学路'],
//            ['./console/data/20171013dance&yoga/2015/2008.1-2017.6mobile2.xls','大学路'],
//            ['./console/data/20171013dance&yoga/2015/2008.1-2017.6mobile3.xls','大学路'],
//            ['./console/data/20171104dv/mobile.xls','大卫城'],
//            ['./console/data/20171013dance&yoga/2015/2008.1-2017.6mobile3.xls','大学路'],
//            ['./console/data/20171031duplicate/mobile/mobile1.xls','大上海'],
//            ['./console/data/20171031duplicate/mobile/mobile2.xls','大上海'],
//            ['./console/data/20171031duplicate/mobile/mobile3.xls','大上海'],
//            ['./console/data/20171107fq/mobile10.27-10.30.xls','丰庆路'],
//            ['./console/data/20171021fqleft/9.30-10.9/9.30-10.9mobile.xls','丰庆路'],
//            ['./console/data/20171021fqleft/10.10/10.10mobile.xls','丰庆路'],
//            ['./console/data/20171108fq/10.31mobile.xls','丰庆路'],
//            ['./console/data/20171109dance&yoga/9.28/mobile.xls','大学路'],
//            ['./console/data/20171108dance&yoga/mobile.xls','大学路'],
//            ['./console/data/20171109dance&yoga/11.5/mobile.xls','大学路'],
//            ['./console/data/20171109dance&yoga/11.7/mobile.xls','大学路'],
//            ['./console/data/20171113fq/fqmobile.xls','丰庆路'],
            ['./console/data/20171106denis/mobile.xls','花园路丹尼斯'],
        ];
    }
    public static function  HandleNumber()
    {
        return [
//            ['./console/data/20170801/20170813fq/fqbuka0809.xls'],
//            ['./console/data/20170801/20170813fq/fqbuka0810.xls'],
//            ['./console/data/20170801/fq20170920/fqbuka811.xls'],
//            ['./console/data/20170801/fq20170920/fqbuka817.xls'],
//            ['./console/data/20170801/fq20170920/fqbuka818.xls'],
//            ['./console/data/20170801/fq20170920/fqbuka821.xls'],
//            ['./console/data/20170801/fq20170920/fqbuka828.xls'],
//            ['./console/data/20170801/fq20170920/fqbuka901.xls'],
//            ['./console/data/20170801/fq20170920/fqbuka904.xls'],
//            ['./console/data/20170801/fq20170920/fqbuka908.xls'],
//            ['./console/data/20170801/fq20170920/fqbuka911.xls'],
//            ['./console/data/20170801/fq20170920/fqbuka912.xls'],
//            ['./console/data/20170801/fq20170920/fqbuka914.xls'],
//            ['./console/data/20170801/fq20170920/fqbuka917.xls'],
//            ['./console/data/20170801/fq20170920/fqbukaAll.xls'],
//            ['./console/data/20170801/dv20180917/dvbuka906.xls'],
//            ['./console/data/20170801/dv20180917/dvbuka911.xls'],
//            ['./console/data/20170801/dv20180917/dvbukaAll.xls'],
//            ['./console/data/20170801/20170907dv/dvbuka830.xls'],
//            ['./console/data/20170801/20170907dv/dvbuka831.xls'],
//            ['./console/data/20170801/dsh20170904/dshbuka908.xls'],
//            ['./console/data/20170801/20170806dsh/dshbuka0801.xls'],
//            ['./console/data/20170801/20170727fq/fqbuka0731.xls'],
//            ['./console/data/20170801/20170806fq/fqbuka0803.xls'],
//            ['./console/data/20170801/20170807dwc/dvbuka728.xls'],
//            ['./console/data/20170629dh/dhBuKa625.xls'],
//            ['./console/data/20170630dsh/dshBuKa625.xls'],
//            ['./console/data/20170630dsh/dshBuKa627.xls'],
//            ['./console/data/20170928dv/dvbuka001.xls'],
//            ['./console/data/20171001fq/20170924/fqbuka.xls'],
//            ['./console/data/20171001fq/20170925/fqbuka.xls'],
//            ['./console/data/20171001fq/20170928/fqbuka.xls'],
//            ['./console/data/20171001fq/20170929/fqbuka.xls'],
//            ['./console/data/20171020fq/10.13-10.16/10.13-10.16buka.xls'],
//            ['./console/data/20171001fq/20170929/fqbuka.xls'],
//            ['./console/data/20171013dance&yoga/7.13-7.17/7.13-7.17buka.xls'],
//            ['./console/data/20171013dance&yoga/7.20/7.17-7.19buka.xls'],
//            ['./console/data/20171013dance&yoga/7.27/7.27buka.xls'],
//            ['./console/data/20171013dance&yoga/7.28/7.28buka.xls'],
//            ['./console/data/20171013dance&yoga/8.7-8.8/8.7-8.8buka.xls'],
//            ['./console/data/20171013dance&yoga/8.9-8.10/8.9-8.10buka.xls'],
//            ['./console/data/20171013dance&yoga/9.28/buka/2014.xls'],
//            ['./console/data/20171013dance&yoga/9.28/buka/2015.xls'],
//            ['./console/data/20171013dance&yoga/9.28/buka/2016.xls'],
//            ['./console/data/20171013dance&yoga/9.28/buka/2017.1-2017.8.xls'],
//            ['./console/data/20171013dance&yoga/9.28/buka/2017.9-2099.xls'],
//            ['./console/data/20171104dv/buka.xls'],
//            ['./console/data/20171031duplicate/buka/buka.xls'],
//            ['./console/data/20171107fq/buka10.27-10.30.xls'],
//            ['./console/data/20171021fqleft/9.30-10.9/9.30-10.9buka.xls'],
//            ['./console/data/20171021fqleft/10.10/10.10buka.xls']
//            ['./console/data/20171108fq/10.31buka.xls'],
            ['./console/data/20171116dh/buka.xls'],
        ];
    }
    public static function  HandleSendMember()
    {
        return [
            ['./console/data/20180125yasr/yxsend.xlsx'],
        ];
    }
    public static function  HandleEmployee()
    {
        return [
            ['./console/data/employee.xlsx'],
        ];
    }
    public static function  HandleLeave()
    {
        return [
            ['./console/data/20170626dsh/dshQingJia614.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170626dsh/dshQingJia616.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170626dsh/dshQingJia619.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170626dsh/dshQingJia621.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170626dsh/dshQingJia622.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170626dsh/dshQingJia6202.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170630dsh/dshQingJia626.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170723/20170712dsh/dshQingJia702.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170723/20170720dsh/dshqingjia704.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170723/20170720dsh/dshqingjia711.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170723/20170720dsh/dshqingjia713.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170723/20170720dsh/dshqingjia719.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170801/20170727dsh/dshqingjia0727.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170801/20170813dsh/dshqingjia0809.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170801/20170813dsh/dshqingjia0810.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170801/dsh20170904/dshqingjia811.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170801/dsh20170904/dshqingjia814.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170801/dsh20170904/dshqingjia815.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170801/dsh20170904/dshqingjia817.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170801/dsh20170904/dshqingjia820.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170801/dsh20170904/dshqingjia823.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170801/dsh20170904/dshqingjia827.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170801/dsh20170904/dshqingjia830.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170801/dsh20170904/dshqingjia831.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170801/20170905dsh/dshqingjia904.xls','大上海','大上海瑜伽健身馆'],
        ];
    }
    public static function  HandleTransfer()
    {
        return [
            ['./console/data/employee.xlsx'],
        ];
    }
    public static function  HandleCabinet()
    {
        return [
            ['./console/data/20170801/20170905dsh/dshguizi904.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170801/20170905dsh/dshguizi901.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170801/dsh20170904/dshguizi830.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170801/dsh20170904/dshguizi829.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170801/dsh20170904/dshguizi827.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170801/dsh20170904/dshguizi824.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170801/dsh20170904/dshguizi823.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170801/dsh20170904/dshguizi822.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170801/dsh20170904/dshguizi821.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170801/dsh20170904/dshguizi820.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170801/dsh20170904/dshguizi817.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170801/dsh20170904/dshguizi816.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170801/dsh20170904/dshguizi815.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170801/dsh20170904/dshguizi814.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170801/dsh20170904/dshguizi811.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170801/20170813dsh/dshguizi0810.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170801/20170813dsh/dshguizi0809.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170801/20170806dsh/dshguizi0801.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170801/20170727dsh/dshguizi0727.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170723/20170720dsh/dshguizi719.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170723/20170720dsh/dshguizi718.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170723/20170720dsh/dshguizi713.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170723/20170720dsh/dshguizi711.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170723/20170720dsh/dshguizi704.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170723/20170712dsh/dshGuiZi702.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170630dsh/dshGuiZi627.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170630dsh/dshGuiZi626.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170630dsh/dshGuiZi625.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170626dsh/dshGuiZi616.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170626dsh/dshGuiZi619.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170626dsh/dshGuiZi621.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170626dsh/dshGuiZi622.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170626dsh/dshGuiZi6201.xls','大上海','大上海瑜伽健身馆'],
            ['./console/data/20170626dsh/dshGuiZiAll.xls','大上海','大上海瑜伽健身馆'],
        ];
    }
    public static function  HandleCharge()
    {
        return [
//            ['./console/data/20170801/fq20170920/fqsijiao.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20170801/fq20170920/fqsijiao811.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20170801/fq20170920/fqsijiao814.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20170801/fq20170920/fqsijiao815.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20170801/fq20170920/fqsijiao817.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20170801/fq20170920/fqsijiao823.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20170801/fq20170920/fqsijiao824.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20170801/fq20170920/fqsijiao825.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20170801/fq20170920/fqsijiao828.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20170801/fq20170920/fqsijiao908.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20170801/fq20170920/fqsijiao912.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20170801/fq20170920/fqsijiao914.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20170801/fq20170920/fqsijiao915.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20170801/fq20170920/fqsijiao919.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20170801/20170813fq/fqsijiao0809.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20170801/20170813fq/fqsijiao0810.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20170801/20170807fq/fqsijiao0725.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20170801/20170807fq/fqsijiao0727.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20170801/20170806fq/fqsijiao0802.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20170801/20170727fq/fqsijiao0730.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20170801/20170727fq/fqsijiao0731.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20170629fq/fqSiJiaoShouFei615.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20170629fq/fqSiJiaoShouFei618.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20170629fq/fqSiJiaoShouFei619.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20170629fq/fqSiJiaoShouFei620.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20170629fq/fqSiJiaoShouFei621.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20170629fq/fqSiJiaoShouFei622.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20170629fq/fqSiJiaoShouFei625.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20170629fq/fqSiJiaoShouFei626.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20170629fq/fqSiJiaoShouFei628.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20170801/20170806dsh/dshsijiao0801.xls','大上海','大上海瑜伽健身馆'],
//            ['./console/data/20170801/dv20180917/dvsijiao906.xls','大卫城','大卫城店（尊爵汇）'],
//            ['./console/data/20170801/dv20180917/dvsijiao911.xls','大卫城','大卫城店（尊爵汇）'],
//            ['./console/data/20170801/dsh2017911/dsh919sijiao.xls','大上海','大上海瑜伽健身馆'],
//            ['./console/data/20170801/20170907dv/dvsijiao803.xls','大卫城','大卫城店（尊爵汇）'],
//            ['./console/data/20170723/20170723dv/dvSiJiaoShouFei717.xls','大卫城','大卫城店（尊爵汇）'],
//            ['./console/data/20170625dvc/dvSiJiaoShouFei.xls','大卫城','大卫城店（尊爵汇）'],
//            ['./console/data/20170928dv/dvsijiao1.xls','大卫城','大卫城店（尊爵汇）'],
//            ['./console/data/20171001fq/20170924/salesijiao.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20171001fq/20170925/salesijiao.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20171001fq/20170926/salesijiao.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20171001fq/20170927/salesijiao.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20171001fq/20170928/salesijiao.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20171020fq/10.13-10.16/10.13-10.15charge.xls','丰庆路','丰庆路游泳健身馆','one'],
//            ['./console/data/20171013dance&yoga/7.13-7.17/7.13-17charge.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171013dance&yoga/7.20/7.17-7.19charge.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171013dance&yoga/7.21/7.21charge.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171013dance&yoga/7.27/7.27charge.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171013dance&yoga/7.28/7.28charge.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171013dance&yoga/7.28-8.2/7.1-7.31charge.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171013dance&yoga/8.7-8.8/8.7-8.8charge.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171013dance&yoga/8.9-8.10/8.9-8.10charge.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171013dance&yoga/9.22-26/9.21charge.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171013dance&yoga/2015/2008.1-2017.6charge.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171104dv/charge.xls','大卫城','大卫城店（尊爵汇）'],
//            ['./console/data/20171019dsh/charge.xls','大上海','大上海瑜伽健身馆'],
//            ['./console/data/20171107fq/charge10.27-10.30.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20171021fqleft/10.10/10.10charge.xls','丰庆路','丰庆路游泳健身馆','one'],
//            ['./console/data/20171108fq/10.31charge.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20171109dance&yoga/9.28/charge.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171108dance&yoga/charge.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171109dance&yoga/11.5/charge.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171109dance&yoga/11.7/charge.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171113fq/fqcharge.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20171117sun/suncharge.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20171120fq/charge.xls','丰庆路','丰庆路游泳健身馆'],
            ['./console/data/20171116dh/charge.xls','帝湖瑜伽','帝湖瑜伽健身馆'],
        ];
    }
    //艾博所有会员
    public static function  HandleCardExpire()
    {
        return [
//            ['./console/data/20170801/dsh2017911/expireAll.xls','大上海','大上海瑜伽健身馆','one'],
//            ['./console/data/20170801/dsh2017911/expireAll2017.xls','大上海','大上海瑜伽健身馆','four'],
//            ['./console/data/20170801/dsh2017911/expireAll2016.xls','大上海','大上海瑜伽健身馆','four'],
//            ['./console/data/20170801/dv20180917/dvexpire.xls','大卫城','大卫城店（尊爵汇）','one'],
//            ['./console/data/20170801/fq20170920/fqexpireAll.xls','丰庆路','丰庆路游泳健身馆','one'],
//            ['./console/data/20170928dv/dvcardexpire1.xls','大卫城','大卫城店（尊爵汇）','one'],
//            ['./console/data/20170928dv/dvcardexpire2.xls','大卫城','大卫城店（尊爵汇）','one'],
//            ['./console/data/20171011dsh/zhangying/cardexpire.xls','大上海','大上海瑜伽健身馆','four'],
//            ['./console/data/20171013dsh/jing/cardexpire.xls','大上海','大上海瑜伽健身馆','one']
//            ['./console/data/20171013dance&yoga/7.13-7.17/7.13-17cardexpire.xls','大学路','大学路舞蹈馆和瑜伽馆','one'],
//            ['./console/data/20171013dance&yoga/7.20/7.17-7.19cardexpire.xls','大学路','大学路舞蹈馆和瑜伽馆','one'],
//            ['./console/data/20171013dance&yoga/7.21/7.21cardexpire.xls','大学路','大学路舞蹈馆和瑜伽馆','one'],
//            ['./console/data/20171013dance&yoga/7.27/7.27cardexpire.xls','大学路','大学路舞蹈馆和瑜伽馆','one'],
//            ['./console/data/20171013dance&yoga/7.28-8.2/7.28-8.2cardexpire.xls','大学路','大学路舞蹈馆和瑜伽馆','one'],
//            ['./console/data/20171013dance&yoga/8.7-8.8/8.7-8.8cardexpire.xls','大学路','大学路舞蹈馆和瑜伽馆','one'],
//            ['./console/data/20171013dance&yoga/8.9-8.10/8.9-8.10cardexpire.xls','大学路','大学路舞蹈馆和瑜伽馆','one'],
//            ['./console/data/20171013dance&yoga/9.22-26/9.27cardexpire.xls','大学路','大学路舞蹈馆和瑜伽馆','one'],
//            ['./console/data/20171013dance&yoga/9.28/cardexpire/2008.xls','大学路','大学路舞蹈馆和瑜伽馆','one'],
//            ['./console/data/20171013dance&yoga/9.28/cardexpire/2009.xls','大学路','大学路舞蹈馆和瑜伽馆','one'],
//            ['./console/data/20171013dance&yoga/9.28/cardexpire/2010.xls','大学路','大学路舞蹈馆和瑜伽馆','one'],
//            ['./console/data/20171013dance&yoga/9.28/cardexpire/2011.xls','大学路','大学路舞蹈馆和瑜伽馆','one'],
//            ['./console/data/20171013dance&yoga/9.28/cardexpire/2012.xls','大学路','大学路舞蹈馆和瑜伽馆','one'],
//            ['./console/data/20171013dance&yoga/9.28/cardexpire/2013.1-2013.6.xls','大学路','大学路舞蹈馆和瑜伽馆','one'],
//            ['./console/data/20171013dance&yoga/9.28/cardexpire/2013.7-2013.12.xls','大学路','大学路舞蹈馆和瑜伽馆','one'],
//            ['./console/data/20171013dance&yoga/9.28/cardexpire/2014.1-2014.6.xls','大学路','大学路舞蹈馆和瑜伽馆','one'],
//            ['./console/data/20171013dance&yoga/9.28/cardexpire/2014.7-2014.12.xls','大学路','大学路舞蹈馆和瑜伽馆','one'],
//            ['./console/data/20171013dance&yoga/9.28/cardexpire/2015.1-2015.6.xls','大学路','大学路舞蹈馆和瑜伽馆','one'],
//            ['./console/data/20171013dance&yoga/9.28/cardexpire/2015.7-2015.12.xls','大学路','大学路舞蹈馆和瑜伽馆','one'],
//            ['./console/data/20171013dance&yoga/9.28/cardexpire/2016.1-2016.6.xls','大学路','大学路舞蹈馆和瑜伽馆','one'],
//            ['./console/data/20171013dance&yoga/9.28/cardexpire/2016.7-2016.12.xls','大学路','大学路舞蹈馆和瑜伽馆','one'],
//            ['./console/data/20171013dance&yoga/9.28/cardexpire/2017.1-2017.8.xls','大学路','大学路舞蹈馆和瑜伽馆','one'],
//            ['./console/data/20171013dance&yoga/9.28/cardexpire/2017.9-2099.xls','大学路','大学路舞蹈馆和瑜伽馆','one'],
//            ['./console/data/20171130twomonth/cardexpire.xls','大卫城','大卫城店（尊爵汇）','one'],
//            ['./console/data/20171130twomonth/cardexpire.xls','大卫城','大卫城店（尊爵汇）','one'],
//            ['./console/data/20171019dsh/cardexpire.xls','大上海','大上海瑜伽健身馆','one'],
//            ['./console/data/20171031duplicate/cardexpire/cardexpire1.xls','大上海','大上海瑜伽健身馆','one'],
//            ['./console/data/20171031duplicate/cardexpire/cardexpire2.xls','大上海','大上海瑜伽健身馆','one'],
//            ['./console/data/20171031duplicate/cardexpire/cardexpire3.xls','大上海','大上海瑜伽健身馆','one'],
//            ['./console/data/20171031duplicate/cardexpire/cardexpire4.xls','大上海','大上海瑜伽健身馆','one'],
//            ['./console/data/20171031duplicate/cardexpire/cardexpire5.xls','大上海','大上海瑜伽健身馆','one'],
//            ['./console/data/20171031duplicate/cardexpire/cardexpire6.xls','大上海','大上海瑜伽健身馆','one'],
//            ['./console/data/20171109dance&yoga/9.28/dancecardexpire.xls','大学路舞蹈','大学路舞蹈健身馆','one'],
//            ['./console/data/20171109dance&yoga/9.28/yogacardexpire.xls','大学路瑜伽','大学路瑜伽健身馆','one'],
//            ['./console/data/20171108dance&yoga/dancecardexpire.xls','大学路舞蹈','大学路舞蹈健身馆','one'],
//            ['./console/data/20171108dance&yoga/yogacardexpire.xls','大学路瑜伽','大学路瑜伽健身馆','one'],
//            ['./console/data/20171109dance&yoga/11.5/dancecardexpire.xls','大学路舞蹈','大学路舞蹈健身馆','one'],
//            ['./console/data/20171109dance&yoga/11.5/yogacardexpire.xls','大学路瑜伽','大学路瑜伽健身馆','one'],
//            ['./console/data/20171109dance&yoga/11.7/dancecardexpire.xls','大学路舞蹈','大学路舞蹈健身馆','one'],
//            ['./console/data/20171109dance&yoga/11.7/yogacardexpire.xls','大学路瑜伽','大学路瑜伽健身馆','one'],
//            ['./console/data/20171116dh/expire.xls','帝湖瑜伽','帝湖瑜伽健身馆','one'],
//            ['./console/data/20171116dh/expire_all.xls','帝湖瑜伽','帝湖瑜伽健身馆','one'],
            ['./console/data/20171116dh/expire_new.xls','帝湖瑜伽','帝湖瑜伽健身馆','one'],
        ];
    }
    public static function  HandleEndCharge()
    {
        return [
//            ['./console/data/20170801/dv20180917/dvendcharge.xls','大卫城','大卫城店（尊爵汇）'],
//            ['./console/data/20170801/fq20170920/fqendchargeAll.xls','丰庆路','丰庆路游泳健身馆'],
//            ['./console/data/20170928dv/dvendcharge1.xls','大卫城','大卫城店（尊爵汇）'],
//            ['./console/data/20170928dv/dvendcharge2.xls','大卫城','大卫城店（尊爵汇）'],
//            ['./console/data/20171013dsh/zhangying/endcharge.xls','大上海','大上海瑜伽健身馆'],
//            ['./console/data/20171013dance&yoga/7.13-7.17/7.13-17endcharge.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171013dance&yoga/7.20/7.17-7.19endcharge.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171013dance&yoga/7.21/7.21endcharge.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171013dance&yoga/7.27/7.27endcharge.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171013dance&yoga/7.28/7.28endcharge.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171013dance&yoga/7.28-8.2/7.28-8.2endcharge.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171013dance&yoga/8.7-8.8/8.7-8.8endcharge.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171013dance&yoga/8.9-8.10/8.9-8.10endcharge.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171013dance&yoga/9.28/endcharge/2008.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171013dance&yoga/9.28/endcharge/2009.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171013dance&yoga/9.28/endcharge/2010.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171013dance&yoga/9.28/endcharge/2011.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171013dance&yoga/9.28/endcharge/2012.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171013dance&yoga/9.28/endcharge/2013.1-2013.6.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171013dance&yoga/9.28/endcharge/2013.7-2013.12.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171013dance&yoga/9.28/endcharge/2014.1-2014.6.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171013dance&yoga/9.28/endcharge/2014.7-2014.12.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171013dance&yoga/9.28/endcharge/2015.1-5015.6.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171013dance&yoga/9.28/endcharge/2015.7-2015.12.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171013dance&yoga/9.28/endcharge/2016.1-2016.6.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171013dance&yoga/9.28/endcharge/2016.7-2016.12.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171013dance&yoga/9.28/endcharge/2017.9-2099.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171013dance&yoga/2015/2008.1-2017.6endcharge1.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171013dance&yoga/2015/2008.1-2017.6endcharge2.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171013dance&yoga/2015/2008.1-2017.6endcharge3.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171013dance&yoga/2015/2008.1-2017.6endcharge4.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171013dance&yoga/2015/2008.1-2017.6endcharge5.xls','大学路','大学路舞蹈馆和瑜伽馆'],
//            ['./console/data/20171031duplicate/endcharge/endcharge1.xls','大上海','大上海瑜伽健身馆','one'],
//            ['./console/data/20171031duplicate/endcharge/endcharge2.xls','大上海','大上海瑜伽健身馆','one'],
//            ['./console/data/20171031duplicate/endcharge/endcharge3.xls','大上海','大上海瑜伽健身馆','one'],
//            ['./console/data/20171031duplicate/endcharge/endcharge4.xls','大上海','大上海瑜伽健身馆','one'],
//            ['./console/data/20171031duplicate/endcharge/endcharge5.xls','大上海','大上海瑜伽健身馆','one'],
//            ['./console/data/20171031duplicate/endcharge/endcharge6.xls','大上海','大上海瑜伽健身馆','one'],
            ['./console/data/20171109dance&yoga/9.28/endcharge.xls','大学路','大学路舞蹈馆和瑜伽馆'],
            ['./console/data/20171108dance&yoga/endcharge.xls','大学路','大学路舞蹈馆和瑜伽馆'],
            ['./console/data/20171109dance&yoga/11.5/endcharge.xls','大学路','大学路舞蹈馆和瑜伽馆'],
            ['./console/data/20171109dance&yoga/11.7/endcharge.xls','大学路','大学路舞蹈馆和瑜伽馆'],
        ];
    }
    //艾博所有会员
    public static function  HandleMember()
    {
        return [
            ['./console/data/20170630ab/abwushiiweishuju.xlsx','艾搏','艾搏万达尊爵会'],
        ];
    }
    //艾博新会员
    public static function  HandleMemberXiao()
    {
        return [
//            ['./console/data/20170630ab/shujuwenti091203.xlsx','艾搏','艾搏尊爵汇馆','two'],
//            ['./console/data/20170630ab/xiaoshou.xlsx','艾搏','艾搏万达尊爵会','one'],
//            ['./console/data/20171110ab/salecard.xls','艾搏','艾搏尊爵汇馆','two'],
//            ['./console/data/20171111ab/11salecard.xls','艾搏','艾搏尊爵汇馆','two'],
//            ['./console/data/20171117ab/absalecard.xls','艾搏','艾搏尊爵汇馆','two'],
//            ['./console/data/20171124ab/salecard.xls','艾搏','艾搏尊爵汇馆','two'],
//            ['./console/data/20171129ab/salecard.xls','艾搏','艾搏尊爵汇馆','two'],
            ['./console/data/20171228ab/card.xls','艾搏','艾搏尊爵汇馆','two'],
        ];
    }
    //艾博新会员
    public static function  HandleMemberYaXiao()
    {
        return [
            ['./console/data/yx20170908/yxxiaoshou0202.xlsx','亚星','亚星游泳健身馆（预售）','two'],
            ['./console/data/yx20170908/yxxiaoshou03.xls','亚星','亚星游泳健身馆（预售）','two'],
            ['./console/data/yx20170908/yxxiaoshou04.xls','亚星','亚星游泳健身馆（预售）','two'],
            ['./console/data/yx20170908/yxxiaoshou12.xls','亚星','亚星游泳健身馆（预售）','two'],
            ['./console/data/yx20170908/yxxiaoshou801.xls','亚星','亚星游泳健身馆（预售）','two'],
//            ['./console/data/20170630ab/xiaoshou.xlsx','艾搏','艾搏万达尊爵会','one'],
        ];
    }
    //艾博新会员
    public static function  HandleMemberXiaoTwo()
    {
        return [
            ['./console/data/20170630ab/abxiaoshou03.xlsx','艾搏','艾搏万达尊爵汇','two'],
//            ['./console/data/20170630ab/xiaoshou.xlsx','艾搏','艾搏万达尊爵会','one'],
        ];
    }
    //艾博加2月会员会员
    public static function  HandleMemberTwoXiao()
    {
        return [
            ['./console/data/20170630ab/xiaoShouTwo.xlsx','艾搏','艾搏万达尊爵会'],
        ];
    }
    //艾博加3月会员会员
    public static function  HandleMemberThreeXiao()
    {
        return [
            ['./console/data/20170630ab/xiaoShouThree.xlsx','艾搏','艾搏万达尊爵会'],
        ];
    }
    //艾博加3月会员会员
    public static function  HandleMemberSwim()
    {
        return [
            ['./console/data/20170630ab/youyong.xlsx','艾搏','艾搏万达尊爵会'],
        ];
    }
    //艾博加3月会员会员
    public static function  HandleMemberCharge()
    {
        return [
//            ['./console/data/20170630ab/abShengYuKeShi517.xlsx','private','艾搏'],
            ['./console/data/20170929ab/aiboprivatelesson.xls','private','艾搏'],
        ];
    }
    //艾博加3月会员会员
    public static function  HandleMemberChargeNew()
    {
        return [
            ['./console/data/20170630ab/charge20170803.xls','private','艾搏'],
        ];
    }


    //艾搏两个闫超数据合并
    public static function HandleYan()
    {
        return [
            ['./console/data/20171009yanchao/yanchao.xls', 'private', '艾搏']
        ];
            
    }

    //会员名重复问题数据修改
    public static function HandleUnitData()
    {
        return [
            ['./console/data/20171009xiayuxin/xiayuxin.xls','private','艾搏']
        ];
    }

    //修复大上海张莹的数据问题
    public static function HandleZhang()
    {
        return [
            ['./console/data/20171013dsh/zhangying/zhangying.xlsx']
        ];
    }
    
    //删除艾搏会员杨帆26月卡
    public static function HandleDelCard()
    {
        return [
            ['./console/data/20171019duoyu/duoyu.xls','private','艾搏']
        ];
    }

    //大上海卖错卡数据修复
    public static function HandleCorrectCard()
    {
        return [
            ['./console/data/20171018card/correctcard.xls']
        ];
    }
    
    //各店会员卡归类
    public static function HandleCardCategory()
    {
        return [
//            ['./console/data/20171024cardcategory/dv/BC12MD.xls'],    //已导入
//            ['./console/data/20171024cardcategory/dv/BC24MD.xls'],    //已导入
//            ['./console/data/20171024cardcategory/dv/BC36MD.xls'],    //已导入
//            ['./console/data/20171024cardcategory/dv/GC36.xls'],      //已导入
//            ['./console/data/20171024cardcategory/dv/GC60.xls'],      //已导入
            ['./console/data/20171024cardcategory/dv/GCS36.xls'],     //售卖场馆没有大卫城
//            ['./console/data/20171024cardcategory/dv/Y12MMD.xls'],    //售卖场馆没有大卫城
//            ['./console/data/20171024cardcategory/dv/ZBC12.xls'],     //售卖场馆没有大卫城
//            ['./console/data/20171024cardcategory/dv/ZGC12.xls'],     //售卖场馆没有大卫城

//            ['./console/data/20171024cardcategory/eq/BC60MD.xls'],    //已导入
//            ['./console/data/20171024cardcategory/eq/FT12MD.xls'],    //已导入
//            ['./console/data/20171024cardcategory/eq/FT24MD.xls'],    //已导入
//            ['./console/data/20171024cardcategory/eq/GC60.xls'],      //已导入
//            ['./console/data/20171024cardcategory/eq/T36MD.xls'],     //已导入
//            ['./console/data/20171024cardcategory/eq/Y12 MMD.xls'],   //已导入
//            ['./console/data/20171024cardcategory/eq/YT12 SD.xls'],   //已导入
//            ['./console/data/20171024cardcategory/eq/YT12MD.xls'],    //已导入
//            ['./console/data/20171024cardcategory/eq/YT24 MD.xls'],   //已导入
//            ['./console/data/20171024cardcategory/eq/YT24 SD.xls'],   //已导入
//            ['./console/data/20171024cardcategory/eq/YT30 MD.xls'],   //已导入
//            ['./console/data/20171024cardcategory/eq/YT60MD.xls'],    //已导入
//            ['./console/data/20171024cardcategory/eq/YC60MSTD.xls'],   //大上海没有这个卡种

//            ['./console/data/20171024cardcategory/fq/FT12MD.xls'],     //已导入
//            ['./console/data/20171024cardcategory/fq/YT30 MD.xls'],      //更换匹配卡种  PT30MD->YT30 MD
//            ['./console/data/20171024cardcategory/fq/FC60MSTD.xls'],     //更换匹配卡种  YT60MSTD->FC60MSTD
//            ['./console/data/20171024cardcategory/fq/BC36MD.xls'],     //丰庆店没有这个卡种
//            ['./console/data/20171024cardcategory/fq/BC60MD.xls'],     //丰庆店没有这个卡种
//            ['./console/data/20171024cardcategory/fq/CS.xls'],         //丰庆店没有这个卡种
//            ['./console/data/20171024cardcategory/fq/FT24MD.xls'],     //丰庆店没有这个卡种
//            ['./console/data/20171024cardcategory/fq/FT36MD.xls'],     //丰庆店没有这个卡种
//            ['./console/data/20171024cardcategory/fq/GC60.xls'],       //丰庆店没有这个卡种
//            ['./console/data/20171024cardcategory/fq/PT60MD.xls'],     //丰庆店没有这个卡种
//            ['./console/data/20171024cardcategory/fq/Y12MMD.xls'],     //丰庆店没有这个卡种
//            ['./console/data/20171024cardcategory/fq/Y12MSD.xls'],     //丰庆店没有这个卡种
//            ['./console/data/20171024cardcategory/fq/YT12MD.xls'],     //丰庆店没有这个卡种
//            ['./console/data/20171024cardcategory/fq/YT12MSTD.xls'],   //丰庆店没有这个卡种
//            ['./console/data/20171024cardcategory/fq/YT12SD.xls'],     //丰庆店没有这个卡种
//            ['./console/data/20171024cardcategory/fq/YT24MD.xls'],     //丰庆店没有这个卡种
//            ['./console/data/20171024cardcategory/fq/YT24SD.xls'],     //丰庆店没有这个卡种

//            ['./console/data/20171024cardcategory/dh/BC60MD.xls'],    //已导入
//            ['./console/data/20171024cardcategory/dh/FT12MD.xls'],    //已导入
//            ['./console/data/20171024cardcategory/dh/FT24MD.xls'],    //已导入
//            ['./console/data/20171024cardcategory/dh/Y12MMD1.xls'],    //已导入
//            ['./console/data/20171024cardcategory/dh/Y12MMD2.xls'],    //已导入
//            ['./console/data/20171024cardcategory/dh/Y12MMD3.xls'],    //已导入
//            ['./console/data/20171024cardcategory/dh/Y12MMD4.xls'],    //已导入
//            ['./console/data/20171024cardcategory/dh/YT12MD.xls'],    //已导入
//            ['./console/data/20171024cardcategory/dh/YT12SD.xls'],    //已导入
//            ['./console/data/20171024cardcategory/dh/YT24MD.xls'],    //已导入
//            ['./console/data/20171024cardcategory/dh/YT24SD.xls'],    //已导入
//            ['./console/data/20171024cardcategory/dh/YT60MD.xls'],    //已导入
//            ['./console/data/20171024cardcategory/dh/YT60MD (2).xls'],    //更换匹配卡种  YT6MD->YT60MD
//            ['./console/data/20171024cardcategory/dh/YC60MSTD.xls'],   //帝湖店没有这个卡种
//            ['./console/data/20171024cardcategory/dh/YC60MTD.xls'],    //帝湖店没有这个卡种
//            ['./console/data/20171024cardcategory/dh/YT12FMD.xls'],    //帝湖店没有这个卡种
//            ['./console/data/20171024cardcategory/dh/YT12MMSTD.xls'],  //帝湖店没有这个卡种
//            ['./console/data/20171024cardcategory/dh/YT12MSTD.xls'],   //帝湖店没有这个卡种
//            ['./console/data/20171024cardcategory/dh/YT24 MDA2.xls'],  //帝湖店没有这个卡种

//            ['./console/data/20171024cardcategory/yg/BC60MD.xls'],     //已导入
//            ['./console/data/20171024cardcategory/yg/YT12MD.xls'],     //大学路舞蹈馆和瑜伽馆没有这个卡种
//            ['./console/data/20171024cardcategory/yg/YT12SD.xls'],     //大学路舞蹈馆和瑜伽馆没有这个卡种
//            ['./console/data/20171024cardcategory/yg/YT24MDA2.xls'],   //大学路舞蹈馆和瑜伽馆没有这个卡种

//            ['./console/data/20171024cardcategory/dx/FT12MD.xls'],    //已导入
//            ['./console/data/20171024cardcategory/dx/FT24MD.xls'],    //已导入
//            ['./console/data/20171024cardcategory/dx/Y12MMD.xls'],    //已导入
//            ['./console/data/20171024cardcategory/dx/BC60MD.xls'],    //已导入
//            ['./console/data/20171024cardcategory/dx/BC36MD.xls'],       //这次加上了
//            ['./console/data/20171024cardcategory/dx/DXDT12MD.xls'],     //更换匹配卡种  DT12MD->DXDT12MD
//            ['./console/data/20171024cardcategory/dx/DXDT24MD.xls'],     //更换匹配卡种  DT24MD->DXDT24MD
//            ['./console/data/20171024cardcategory/dx/D12MMD.xls'],     //大学路舞蹈馆和瑜伽馆没有这个卡种
//            ['./console/data/20171024cardcategory/dx/DC60MSTD.xls'],   //大学路舞蹈馆和瑜伽馆没有这个卡种
//            ['./console/data/20171024cardcategory/dx/DC60MTD.xls'],    //大学路舞蹈馆和瑜伽馆没有这个卡种
//            ['./console/data/20171024cardcategory/dx/F12MMD.xls'],     //大学路舞蹈馆和瑜伽馆没有这个卡种

//             ['./console/data/20171024cardcategory/yx/GC60.xls'],       //亚星没有这个卡种，匹配帝湖店
//            ['./console/data/20171024cardcategory/yx/BC60MD.xls'],    //亚星没有这个卡种
//            ['./console/data/20171024cardcategory/yx/FT24MD.xls'],    //亚星没有这个卡种
//            ['./console/data/20171024cardcategory/yx/SC60.xls'],     //亚星没有这个卡种，匹配帝湖店,帝湖店也没有这个卡种
//            ['./console/data/20171024cardcategory/yx/YT24MD.xls'],   //亚星没有这个卡种
//            ['./console/data/20171024cardcategory/yx/YT24MSTD.xls'], //亚星没有这个卡种
//            ['./console/data/20171024cardcategory/yx/YT60MD.xls'],   //亚星没有这个卡种
        ];
    }

    //大卫城四个潜在会员改成会员
    public static function HandleTurnMembers()
    {
        return [
            ['./console/data/20171022dv/dvmembers.xls']
        ];
    }

    //删除艾搏会员张凯多导入的卡
    public static function HandleDelKai()
    {
        return [
            ['./console/data/20171720kai/delkai.xls']
        ];
    }

    //大上海会员卡时间问题
    public static function HandleTime()
    {
        return [
            ['./console/data/20171009dsh/dshtime.xls']
        ];
    }

    //修复大上海张静的数据问题
    public static function HandleJing()
    {
        return [
            ['./console/data/20171013dsh/jing/jing.xls']
        ];
    }


    //大卫金爵带人问题
    public static function HandleBring()
    {
        return [
//            ['./console/data/20171027bring/bring.xls'],
            ['./console/data/20171027bring/denisbring.xls']
        ];
    }
    
    //私教上课
    public static function HandleTakeClass()
    {
        return [
//            ['./console/data/20171020fq/10.12/10.12takeClass.xls'],
//            ['./console/data/20171020fq/10.13-10.16/10.13-10.16takeClass.xls'],
//            ['./console/data/20171020fq/10.17/10.17takeClass.xls'],
//            ['./console/data/20171107fq/takeClass10.27-10.30.xls']
//            ['./console/data/20171107fq/takeClass10.27-10.30.xls'],
//            ['./console/data/20171021fqleft/9.30-10.9/9.30-10.9takeClass.xls','丰庆路'],
//            ['./console/data/20171021fqleft/10.10/10.10takeClass.xls','丰庆路'],
//            ['./console/data/20171108fq/10.31takeClass.xls'],
//            ['./console/data/20171109dance&yoga/9.28/takeClass.xls'],
//            ['./console/data/20171108dance&yoga/takeClass.xls'],
//            ['./console/data/20171109dance&yoga/11.5/takeClass.xls'],
//            ['./console/data/20171109dance&yoga/11.7/takeClass.xls'],
            ['./console/data/20171117sun/suntakeClass.xls'],
        ];
    }

    //导入大学路会员的手机号
    public static function HandlePhone()
    {
        return [
            ['./console/data/20171030dx/phone.xls']
        ];
    }

    //大学路问题数据修复
    public static function HandleCollege()
    {
        return [
            ['./console/data/20171030dx/card.xls'],
        ];
    }

    //解决会员卡重名问题:1、按照手机号码把会员卡转给对应的会员
    public static function HandleDuplicateCard()
    {
        return [
            ['./console/data/20171031duplicate/cardduplicate/cardduplicate1.xls','我爱运动瑜伽健身','大上海瑜伽健身馆'],
            ['./console/data/20171031duplicate/cardduplicate/cardduplicate2.xls','我爱运动瑜伽健身','大上海瑜伽健身馆'],
            ['./console/data/20171031duplicate/cardduplicate/cardduplicate3.xls','我爱运动瑜伽健身','大上海瑜伽健身馆'],
            ['./console/data/20171031duplicate/cardduplicate/cardduplicate4.xls','我爱运动瑜伽健身','大上海瑜伽健身馆'],
            ['./console/data/20171031duplicate/cardduplicate/cardduplicate5.xls','我爱运动瑜伽健身','大上海瑜伽健身馆'],
        ];
    }

    //大上海的会员改为大卫城会员
    public static function HandleDv()
    {
        return [
            ['./console/data/20171111dv/card.xls'],
        ];
    }

    //转卡逻辑
    public static function HandleZhuanKa()
    {
        return [
//            ['./console/data/20171113fq/zhuanka.xls'],
//            ['./console/data/20171108dance&yoga/zhuanka1.xls'],
//            ['./console/data/20171109dance&yoga/9.28/zhuanka2.xls'],
//            ['./console/data/20171109dance&yoga/11.7/zhuanka4.xls'],
//            ['./console/data/20171123dv/dvzhuanka.xls'],
//            ['./console/data/20171123dv/zhuanka1.xls'],
            ['./console/data/20171116dh/zhuanka.xls'],
        ];
    }

    //大卫城私教课程匹配与剩余课程数量更新
    public static function HandleMatchCourse()
    {
        return [
//            ['./console/data/20171121dv/charge.xls'],
//            ['./console/data/20171124dv/dvcharge.xls'],
//            ['./console/data/20171129dv/charge.xls'],
//            ['./console/data/20171129dv/addcharge.xls'],
//            ['./console/data/20171116dh/charge_match.xls'],
//            ['./console/data/20171215ab/charge.xls'],
//            ['./console/data/20171220ab/charge.xls'],
//            ['./console/data/20171221ab/charge.xls'],
//            ['./console/data/20171228ab/abcharge.xls'],
//            ['./console/data/20180102ab/charge.xls'],
            ['./console/data/20180128yx/yxcharge.xls'],
        ];
    }

    //删除问题数据
    public static function HandleDelMember()
    {
        return [
            ['./console/data/20171122dennis/salecardcommon.xls'],
            ['./console/data/20171122dennis/salecardgold.xls'],
            ['./console/data/20171122dennis/salecardsingle.xls'],
        ];
    }

    //修改大卫城金爵会员卡的办卡时间
    public static function HandleCorrectTime()
    {
        return [
            ['./console/data/20171123dv/time.xls'],
        ];
    }
    
    //大学路杜剑数据修复
    public static function HandleDuJian()
    {
        return [
            ['./console/data/20171130dx/card.xls'],
        ];
    }
    
    //亚星会员卡销售
    public static function HandleYaXing()
    {
        return [
//            ['./console/data/20171201yx/201707/7salecard.xls'],
//            ['./console/data/20171201yx/201708/8salecard.xls'],
//            ['./console/data/20171201yx/201709/9salecard.xls'],
            ['./console/data/20180117yx/card.xls'],
        ];
    }

    //亚星补卡
    public static function HandleBuKa()
    {
        return [
//            ['./console/data/20171201yx/201709/buka.xls'],
            ['./console/data/20180117yx/buka.xls'],
        ];
    }
    
    //亚星送人卡补资料
    public static function HandleAddProfile()
    {
        return [
            ['./console/data/20171201yx/201709/buziliao.xls'],
        ];
    }

    //亚星转卡
    public static function HandleZhuanRang()
    {
        return [
//            ['./console/data/20171201yx/201709/zhuanka.xls'],
            ['./console/data/20180117yx/zhuanka.xls'],
        ];
    }

    //修改张曙霞数据
    public static function HandleZhangShuXia()
    {
        return [
            ['./console/data/20171130yoga/zhangshuxia.xls'],
            ['./console/data/20171130yoga/yuanyao.xls'],
            ['./console/data/20171130yoga/yvke.xls'],
        ];
    }
    //修改张曙霞数据
    public static function HandleDelay()
    {
        return [
            ['./console/data/20180210deaty/dsh11.xls'],
            ['./console/data/20180210deaty/dsh12.xls'],
        ];
    }

    //花丹店金爵卡消费记录
    public static function HandleXiaoFei()
    {
        return [
            ['./console/data/20180115hd/card.xls'],
        ];
    }

    //花丹店会员卡请假数据
    public static function HandleDayOff()
    {
        return [
            ['./console/data/20180115hd/card.xls'],
        ];
    }

}