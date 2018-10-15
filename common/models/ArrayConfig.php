<?php
namespace common\models;

use yii\base\Model;
use yii;
class ArrayConfig extends Model
{
    /**
     * 云运动 - 员工职位 - 定义数组公用
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/8/15
     * @return array
     */
    public function setEmployeePosition()
    {
        return [
            ['key'=>'1','val'=>'销售总监'],
            ['key'=>'2','val'=>'销售助理'],
            ['key'=>'3','val'=>'销售经理'],
            ['key'=>'4','val'=>'销售副理'],
            ['key'=>'5','val'=>'销售主管'],
            ['key'=>'6','val'=>'销售主任'],
            ['key'=>'7','val'=>'销售组长'],
            ['key'=>'8','val'=>'资深销售'],
            ['key'=>'9','val'=>'普通销售']
        ];
    }
    /**
     * 云运动 - 员工职位 - 定义卡种名称数组
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/8/15
     * @return array
     */
    public static function setCardNameArr()
    {
        return [
            'one'   => ['PT12/24MD赠月卡','赠半年卡','赠年卡','赠卡','赠季卡','YJN-001-1-H','YJN-001-1-Z','YJY-001-1-H','YJY-001-3-Z','YJY-001-6','YJY-001-6-H','YJY-001-6-Z','ZJ-001-Z','置换月卡','公司置换卡','置换季卡','置换年卡','置换半年卡','置换卡','员工福利卡','员工福利年','活动月卡','活动半年卡','活动年卡','活动季卡','学生暑期卡','YGFLK-001-1','特惠卡','300元瘦身体验卡','运动概念卡','五年卡','vip全程通5年尊爵卡','ZJ-001','大学路连廊','EQ-VIP瑜伽卡','EQ黑卡','VIP三年卡','单人1年vip','vip卡','V家庭金B卡','金卡','羽毛球金卡','Vip金卡','单人金卡','水晶卡','十年卡','PT30MD','瑜伽卡A+','瑜伽卡B','D瑜伽带人A卡','D瑜伽B卡','瑜伽两周卡','瑜伽B+','EQ瑜珈多节卡','D瑜珈卡A','瑜珈综合卡','VIP两年卡','EQ-VIP瑜伽卡','半年卡','YJN-001-1','YJN-001-2','YJN-001-3','YJY-001-1','YJY-001-2','YJY-001-3','店庆卡','Student cardT2M','单人三年卡','三年卡','单人1年卡','闲时年卡','团购次卡','单次卡','20次卡','50次卡','100次卡','24次卡','6次卡','4次卡','10次卡','30次卡','40次卡','全民健身8次卡','350次卡','300次卡','200次卡','750次卡','120次次卡','15次卡','180次卡','JSC-001-50','YJY/N-001-H','GC60','GCS36','终身卡','EQ黑卡','12M MD','T60MD','BC36MD','TT12MD','BC60'],
            'two'   => ['FT12/24M','EQ健身卡','D健身卡A','D健身卡B','年卡','双人年卡','半年卡','JSN-001-1','JSN-001-1-Z','JSN-001-2','一卡通','店庆卡','单人三年卡','三年卡','单人1年卡','学生季卡','FT12/24M'],
            'three' => ['舞蹈年卡D1'],
            'four'  => ['PT12/24SD','瑜伽单节年卡','YJN-001-1-D','EQ瑜珈单节卡']
        ];
    }
    /**
     * 云运动 - 员工职位 - 定义卡种名称数组
     * @author Huang hua <huanghua@itsports.club>
     * @create 2017/8/15
     * @return array
     */
    public static function setVipCardNameArr()
    {
        return ['EQ黑卡','T60MD','BC36MD','TT12MD','BC60','GCS36','GC60'];
    }
}