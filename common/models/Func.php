<?php
namespace common\models;

use common\models\base\CardCategory;
use common\models\base\CardCategoryType;
use common\models\VenueLimitTimes;
use common\models\MemberCardTime;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii;
use common\components\UrlPager;
use backend\models\UploadForm;
use yii\web\UploadedFile;
class Func extends Model
{
    /**
     * 云运动 - 查询数据 - 获取分页
     * @author lihuien<lihuien@itsports.club>
     * @create 2017/3/30
     * @param $query
     * @param int $limit
     * @return ActiveDataProvider
     */
    public static function getDataProvider($query,$limit = 5)
    {
        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [
                'pageSize' => $limit,
                'totalCount' => $query->count(),
            ],
        ]);
        return $dataProvider;
    }

    /**
     * 云运动 - 查询数据 - 获取本周开始结束时间
     * @author 李慧恩 <lihuien@itsports.club>
     * @create 2017/4/13
     * @param $attr //参数 例如获取周 天 月
     * @param $bool //获取开始还是结束
     * @return int|string
     */
    public static function getGroupClassDate($attr,$bool)
    {
        if($attr == 'd'){
            if($bool){
                return  date('Y-m-d',time()).' 00:00:00';
            }else{
                return date('Y-m-d',time()).' 23:59:59';
            }
        }else if ($attr == 'w'){
            if($bool){
//                return date('Y-m-d',mktime(0,0,0,date('m'),date('d')-date('w')+1-7,date('Y'))).' 00:00:00';  //上周
                return date("Y-m-d",mktime(0,0,0,date("m"),date("d")-date("w")+1,date("Y"))).' 00:00:00';  //本周
            }else{
//                return date('Y-m-d',mktime(23,59,59,date('m'),date('d')-date('w')+7-7,date('Y'))).' 23:59:59';
                return date("Y-m-d",mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y"))).' 23:59:59';
            }
        }else{
            if($bool){
               return date('Y-m-d',mktime(0,0,0,date('m'),1,date('Y'))).' 00:00:00';
            }else{
                return date('Y-m-d',mktime(23,59,59,date('m'),date('t'),date('Y'))).' 23:59:59';
            }
        }
    }

    /**
     * 云运动 - 查询数据 - 获取本周开始结束时间
     * @author 焦冰洋 <jiaobingyang@itsports.club>
     * @create 2017/12/15
     * @param $attr //参数 例如获取 天 周 月 季度 年
     * @param $bool //获取开始还是结束
     * @return int|string
     */
    public static function getTokenClassDate($attr,$bool)
    {
        if($attr == 'd'){
            if($bool){
                return  date('Y-m-d',time()).' 00:00:00';
            }else{
                return date('Y-m-d',time()).' 23:59:59';
            }
        }else if ($attr == 'w'){
            $datew = date('w') ?: 7;
            if($bool){
                return date("Y-m-d",mktime(0,0,0,date("m"),date("d")-$datew+1,date("Y"))).' 00:00:00';  //本周
            }else{
                return date("Y-m-d",mktime(23,59,59,date("m"),date("d")-$datew+7,date("Y"))).' 23:59:59';
            }
        }else if ($attr == 'm'){
            if($bool){
                return date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),1,date("Y")));
            }else{
                return date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("t"),date("Y")));
            }
        }else if ($attr == 's'){
            $season = ceil((date('n'))/3);
            if($bool){
                return date('Y-m-d H:i:s', mktime(0, 0, 0,$season*3-3+1,1,date('Y')));
            }else{
                return date('Y-m-d H:i:s', mktime(23,59,59,$season*3,date('t',mktime(0, 0 , 0,$season*3,1,date("Y"))),date('Y')));
            }
        }else{
            if($bool){
                return date('Y-m-d',mktime(0,0,0,1,1,date('Y'))).' 00:00:00';
            }else{
                return date('Y-m-d',mktime(23,59,59,12,31,date('Y'))).' 23:59:59';
            }
        }
    }
    /**
     * 云运动 - 分页 - 获取格式化样式
    * @author lihuien<lihuien@itsports.club>
     * @create 2017/3/30
     * @param $pagination
     * @param $fun
     * @return string
     * @throws \Exception
     */
    public static function getPagesFormat($pagination,$fun = 'replacementPages' )
    {
        $pages      = UrlPager::widget([
            'pagination'     => $pagination,
            'firstPageLabel' => '首页',
            'prevPageLabel'  => '上一页',
            'nextPageLabel'  => '下一页',
            'lastPageLabel'  => '尾页',
            'jsPageFun'      => $fun
        ]);
        return $pages;
    }
    /**
     *后台会员管理 - 会员信息 - 出生日期，转换成年龄
     * @author Huang Pengju <huangpengju@itsports.club>
     * @create 2017/3/30
     * @return bool|string
     */
    public static function getMemberAge($MemberAge)
    {
        return date('Y',time()) - date('Y',strtotime($MemberAge));
    }

    /**
     *后台会员管理 - 会员信息 - 年龄，转换成出生日期
     * @author Huang Pengju <huangpengju@itsports.club>
     * @create 2017/3/31
     * @param $birth_date
     * @param $age
     * @return string
     */
    public static function setMemberAge($birth_date,$age)
    {
        $MonthDay    = date('m-d',strtotime($birth_date)); //截取出生日期的月份天数
        $NowYear     = date('Y',time());                   //获取当前年
        $lastYear    = $NowYear - $age;                    //获取修改年龄后的年数
        $birth_dates = $lastYear.'-'.$MonthDay;            //拼接新的出生日期
        return $birth_dates;
    }

    /**
     * 后台会员管理 - 信息 - 设置日期
     * @param $param1
     * @param $param2
     * @return int
     */
    public static function setTimeHour($param1,$param2)
    {
        if($param1){
            if(isset($param2)){
                $date = $param1.' '.$param2;
            }else{
                $date = $param1;
            }
        }else{
            return null;
        }
        return strtotime($date);
    }

    /**
     * 云运动 - 会员管理 -  批量生成会员
     * @throws yii\db\Exception
     * @author lihuien<lihuien@itsports.club>
     * @create 2017/4/1
     */
    public static function setMemberBase()
    {
        $queryBuilder = Yii::$app->db->createCommand();
        $sql = $queryBuilder->batchInsert('{{%member}}',
            ['username', 'password','mobile','register_time','status'],
            [
            ['Tom',mt_rand(100000,999999),'15676566711',time(),0],
            ['Jane',mt_rand(100000,999999),'15676566722',time(),0],
            ['Linda',mt_rand(100000,999999),'15676566733',time(),0],
            ['Xiao',mt_rand(100000,999999),'15676566744',time(),0],
            ['ming',mt_rand(100000,999999),'15676566755',time(),0],
            ['haha',mt_rand(100000,999999),'15676566766',time(),0],
            ['hehhe',mt_rand(100000,999999),'15676566777',time(),0],
            ['heine',mt_rand(100000,999999),'15676566788',time(),0],
            ['heihei',mt_rand(100000,999999),'15676566799',time(),0],
           ]);
        $int = $sql->execute();
        if($int){
            $data = CardCategoryType::find()->all();
            if(isset($data) && empty($data)){
                self::setCardType();
                self::setCard();
            }
            self::setMemberDetail($queryBuilder,$int);
            self::setMemberCard($queryBuilder,$int);
            return true;
        }else{
            return false;
        }

    }
    /**
     * 云运动 - 会员管理 -  批量生成卡类型
     * @throws yii\db\Exception
     * @author lihuien<lihuien@itsports.club>
     * @create 2017/4/11
     */
    public static function setCardType(){
        $queryBuilder = Yii::$app->db->createCommand();
        $sql = $queryBuilder->batchInsert('{{%card_category_type}}',
            ['type_name','create_at'],
            [
                ['时间卡',time()], ['次卡',time()], ['充值卡',time()], ['混合卡',time()],
            ]);
        $sql->execute();
    }
    /**
     * 云运动 - 会员管理 -  批量生成卡种数据
     * @throws yii\db\Exception
     * @author lihuien<lihuien@itsports.club>
     * @create 2017/4/11
     */
    public static function setCard()
    {
        $queryBuilder = Yii::$app->db->createCommand();
        $sql = $queryBuilder->batchInsert('{{%card_category}}',
            ['category_type_id',
              'card_name',
              'create_at',
              'class_server_id',
              'server_combo_id',
              'sell_end_time',
              'venue_id',
              'create_id',
              'active_time',
              'status',
              'sell_price',
              'times',
            ],
            [
                [mt_rand(1,4),['尊爵卡','瑜伽卡','爵士卡','肚皮舞卡'][array_rand(['尊爵卡','瑜伽卡','爵士卡','肚皮舞卡'],1)].mt_rand(100,time()),time(),1,1,mt_rand(946659661,time()),mt_rand(1,3),1,mt_rand(10,60),mt_rand(1,3),mt_rand(10000,999999),mt_rand(10,100)],
                [mt_rand(1,4),['尊爵卡','瑜伽卡','爵士卡','肚皮舞卡'][array_rand(['尊爵卡','瑜伽卡','爵士卡','肚皮舞卡'],1)].mt_rand(100,time()),time(),1,1,mt_rand(946659661,time()),mt_rand(1,3),1,mt_rand(10,60),mt_rand(1,3),mt_rand(10000,999999),mt_rand(10,100)],
                [mt_rand(1,4),['尊爵卡','瑜伽卡','爵士卡','肚皮舞卡'][array_rand(['尊爵卡','瑜伽卡','爵士卡','肚皮舞卡'],1)].mt_rand(100,time()),time(),1,1,mt_rand(946659661,time()),mt_rand(1,3),1,mt_rand(10,60),mt_rand(1,3),mt_rand(10000,999999),mt_rand(10,100)],
                [mt_rand(1,4),['尊爵卡','瑜伽卡','爵士卡','肚皮舞卡'][array_rand(['尊爵卡','瑜伽卡','爵士卡','肚皮舞卡'],1)].mt_rand(100,time()),time(),1,1,mt_rand(946659661,time()),mt_rand(1,3),1,mt_rand(10,60),mt_rand(1,3),mt_rand(10000,999999),mt_rand(10,100)],
                [mt_rand(1,4),['尊爵卡','瑜伽卡','爵士卡','肚皮舞卡'][array_rand(['尊爵卡','瑜伽卡','爵士卡','肚皮舞卡'],1)].mt_rand(100,time()),time(),1,1,mt_rand(946659661,time()),mt_rand(1,3),1,mt_rand(10,60),mt_rand(1,3),mt_rand(10000,999999),mt_rand(10,100)],
                [mt_rand(1,4),['尊爵卡','瑜伽卡','爵士卡','肚皮舞卡'][array_rand(['尊爵卡','瑜伽卡','爵士卡','肚皮舞卡'],1)].mt_rand(100,time()),time(),1,1,mt_rand(946659661,time()),mt_rand(1,3),1,mt_rand(10,60),mt_rand(1,3),mt_rand(10000,999999),mt_rand(10,100)],
                [mt_rand(1,4),['尊爵卡','瑜伽卡','爵士卡','肚皮舞卡'][array_rand(['尊爵卡','瑜伽卡','爵士卡','肚皮舞卡'],1)].mt_rand(100,time()),time(),1,1,mt_rand(946659661,time()),mt_rand(1,3),1,mt_rand(10,60),mt_rand(1,3),mt_rand(10000,999999),mt_rand(10,100)],
                [mt_rand(1,4),['尊爵卡','瑜伽卡','爵士卡','肚皮舞卡'][array_rand(['尊爵卡','瑜伽卡','爵士卡','肚皮舞卡'],1)].mt_rand(100,time()),time(),1,1,mt_rand(946659661,time()),mt_rand(1,3),1,mt_rand(10,60),mt_rand(1,3),mt_rand(10000,999999),mt_rand(10,100)],
                [mt_rand(1,4),['尊爵卡','瑜伽卡','爵士卡','肚皮舞卡'][array_rand(['尊爵卡','瑜伽卡','爵士卡','肚皮舞卡'],1)].mt_rand(100,time()),time(),1,1,mt_rand(946659661,time()),mt_rand(1,3),1,mt_rand(10,60),mt_rand(1,3),mt_rand(10000,999999),mt_rand(10,100)],
            ]);
        $sql->execute();
    }
    /**
     * * 云运动 - 会员管理 -  批量生成会员详情
     * @throws yii\db\Exception
     * @author lihuien<lihuien@itsports.club>
     * @create 2017/4/1
     * @param $queryBuilder
     * @param $int
     */
    public static function setMemberDetail($queryBuilder,$int)
    {
        $idArr = Member::find()->orderBy('id DESC')->limit($int)->all();
        if(isset($idArr)){
            $nameArr = ['梅长苏','东方不败','曾小贤','胡一菲','夏目','金木研','路飞','佐助','詹姆斯','库里','杜兰特'];
            foreach ($idArr as $v){ 
                $birth = mt_rand(1980,2010).'-'.mt_rand(1,12).'-'.mt_rand(1,30);
                $name = $nameArr[array_rand($nameArr,1)].$v['id'];
                $sql = $queryBuilder->batchInsert('{{%member_details}}',
                    ['member_id', 'name','sex','birth_date','recommend_member_id'],
                    [
                        [$v['id'] ,$name,mt_rand(1,2),$birth,0],
                    ]);
                $int = $sql->execute();
            }
        }
    }

    /**
     * 云运动 - 会员管理 -  批量生成会员卡
     * @throws yii\db\Exception
     * @author lihuien<lihuien@itsports.club>
     * @create 2017/4/1
     * @param $queryBuilder
     * @param $int
     */
    public static function setMemberCard($queryBuilder,$int)
    {
        $idArr = Member::find()->orderBy('id DESC')->limit($int)->all();
        $cardId = CardCategory::find()->select('id')->orderBy('id DESC')->all();
        if(isset($idArr)){
            foreach ($idArr as $k=>$v){
                $sql = $queryBuilder->batchInsert('{{%member_card}}',
                    ['card_category_id',
                     'card_number',
                     'member_id',
                     'status',
                     'payment_type',
                     'is_complete_pay',
                     'active_time',
                     'invalid_time',
                     'level'],
                    [
                        [(int)$cardId[$k]['id'],(int)$v['id'] ,(int)$v['id'], 1,1,1,mt_rand(946659661,1199120461),mt_rand(1199120461,time()),1],
                    ]);
                $int = $sql->execute();
            }
        }
    }

    /**
     * @云运动 - 发送验证码
     * @author Xingsonglin <xingsonglin@itsports.club>
     * @create 2017/4/5
     * @param $to  要发送的手机号码
     * @param $code  验证码
     * @return json  status 状态:success/error   fee 条数
     * @inheritdoc
     */
    public static function sendCode($to,$code){
        $model = new Sms();
        $msg = $model->sendCode($to,$code);
        return json_decode($msg,true);
    }
    /**
     * @云运动 - 会员卡激活成功发送信息
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/7/13
     * @param $to  要发送的手机号码
     * @return json  status 状态:success/error   fee 条数
     * @inheritdoc
     */
    public static function sendMessage($to,$cardName){
        $model = new Sms();
        $msg = $model->sendMessage($to,$cardName);
        return json_decode($msg,true);
    }
    /**
     * @云运动 - 发送预约成功
     * @author Huangpengju <Huangpengju@itsports.club>
     * @create 2017/5/12
     * @param $to  要发送的手机号码
     * @param $info  发送成功信息
     * @return json  status 状态:success/error   fee 条数
     * @inheritdoc
     */
    public static function sendInfo($to,$companyId,$venueId,$course,$classDate,$start,$end){
        $model = new Sms();
        $msg = $model->sendInfo($to,$companyId,$venueId,$course,$classDate,$start,$end);
        return json_decode($msg,true);
    }
    /**
     * @云运动 - 会员购买私课 - 发送短信
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/7/21
     * @param $to 手机号
     * @inheritdoc
     */
    public static function memberCharge($to){
        $model = new Sms();
        $msg = $model->memberCharge($to);
        return json_decode($msg,true);
    }
    /**
     * @云运动 - 售卡成功发送短信
     * @author 朱梦珂 <zhumengke@itsports.club>
     * @create 2017/6/5
     * @param $to  要发送的手机号码
     * @param $info  发送的信息内容
     * @return json  status 状态:success/error   fee 条数
     * @inheritdoc
     */
    public static function sendSellCardInfo($to,$info){
        $model = new Sms();
        $msg = $model->sendSellCardInfo($to,$info);
        return json_decode($msg,true);
    }
    /**
     * @云运动 - 发送预约成功
     * @author Huanghua <Huanghua@itsports.club>
     * @create 2017/5/12
     * @param $to  要发送的手机号码
     * @param $course  课程名
     * @param $info  信息 会员下课成功
     * @return json  status 状态:success/error   fee 条数
     * @inheritdoc
     */
    public static function sendClassInfo($to,$course,$info){
        $model = new Sms();
        $msg = $model->sendClassInfo($to,$course,$info);
        return json_decode($msg,true);
    }
    /**
     * 上传图片路径到七牛
     * @author xingsonglin
     * @param $filePath  //图片本地路径
     * @param $fileName  //图片名称(包含扩展名)
     * @return array key文件名
     * @throws \Exception
     */
    public static function uploadFile($filePath,$fileName){
        $model = new Qiniu();
        $return = $model->uploadFile($filePath,$fileName);
        return $return;
    }

    /**
     * 上传图片路径到七牛并回调到服务器
     * @author xingsonglin
     * @param $filePath  图片本地路径
     * @param $fileName  图片名称(包含扩展名)
     * @return array key文件名
     * @throws \Exception
     */
    public static function uploadFileCallback($filePath,$fileName){
        $model = new Qiniu();
        $return = $model->uploadFileCallback($filePath,$fileName);
        return $return;
    }
    /**
     * 上传图片回调函数
     * @author xingsonglin
     * @return json key文件名
     * @throws \Exception
     */

    public static function callBack()
    {
        $model = new Qiniu();
        $return = $model->callBack();
        return $return;
    }

    /**
     * 删除七牛上的图片
     * @author xingsonglin
     * @param $fileName  图片名称(包含扩展名)
     * @return bool
     * @throws \Exception
     */

    public static function deleteImg($fileName)
    {
        $model = new Qiniu();
        $return = $model->deleteImg($fileName);
        return $return;
    }

    /**
     * @desc 得到图片完整路径
     * @author xingsonglin
     * @param $fileName //文件名称
     * @return string 文件路径
     *
     */
    public static function getImgUrl($fileName){
        $model = new Qiniu();
        $return = $model->getImgUrl($fileName);
        return $return;
    }
    /**
     * 后台 - 上传图片 - 公共调用七牛类
     * @return string
     * @author 李慧恩
     * @create 2017-4-6
     * @param
     */
    public static function uploadImage()
    {
        $upload = new UploadForm();
        if (\Yii::$app->request->isPost) {
            $upload->imageFile = UploadedFile::getInstance($upload, 'imageFile');
            if ($upload->imageFile && ($upload->imageFile->type == "image/jpeg" || $upload->imageFile->type == "image/pjpeg" || $upload->imageFile->type == "image/png" || $upload->imageFile->type == "image/x-png" || $upload->imageFile->type == "image/gif")) {
                if($upload->imageFile->size > 2000000){
                    return json_encode(['status' => 'error', 'data' => '上传的文件太大']);
                }
                $extend     = explode("." , $upload->imageFile->name);
                $extends    = end($extend);
                $photoName  = rand(1,999999).time().'.'.$extends;
                $upload->imageFile->name = $photoName;
                self::uploadFile($upload->imageFile->tempName,$upload->imageFile->name);
                $url = self::getImgUrl($upload->imageFile->name);
                return json_encode(['status' => 'success','imgLink' => $url]);
            }else{
                return json_encode(['status' => 'error', 'data' =>'图片格式出现错误']);
            }
        }
        return json_encode(['status' => 'error', 'data' =>'上传出错']);
    }
    /**
     * 云运动 - 会员管理 - 二维转一维
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/13
     * @param $data array //多维数组
     * @return bool
     */
    public static function setArrayByTwoArray($data)
    {
        $arr = [];
        if(is_array($data) && !empty($data)){
            foreach ($data as $k=>$v){
                if(is_array($v) && !empty($v)){
                    foreach ($v as $key=>$value){
                        if(is_array($value) && !empty($value)){
                            foreach ($value as $e){
                                $arr[] = $e;
                            }
                        }
                    }
                }
            }
        }
        return $arr;
    }

    /**
     * 云运动 - 会员管理 - 环境判断
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/13
     * @return bool
     */
    public static function autoGeneration()
    {
        if(YII_ENV != 'dev'){
            return false;           //product线上环境
        }else{
            return true;            //qa测试环境 
        }
    }

    /**
     * 云运动 - 公共转换时间戳 - mkTime方法
     * @param $m int 月份
     * @param $d int 号
     * @param $y int 年份
     * @return int
     */
   public static function getMkTimeDate($m,$d,$y)
   {
      return  mktime(0,0,0,$m,$d,$y);
   }

    /**
     * 云运动 - 卡种属性 - 定义数组公用
     * @author Huang Pengju <huangpengju@itsports.club>
     * @create 2017/4/27
     * @return array
     */
    public function setCardAttributes()
    {
        return [
            ['key'=>'1','val'=>'个人'],
            ['key'=>'2','val'=>'家庭'],
            ['key'=>'3','val'=>'公司']
        ];
    }
    /**
     * 云运动 - 卡种属性 - 隐藏手机号部分数字
     * @author Huang Pengju <huangpengju@itsports.club>
     * @create 2017/4/27
     * @param  $attr
     * @param  $type
     * @return array
     */
    public static function phoneHide($attr,$type = 'mobile',$len=9)
    {
        if($type == 'name'){
            return str_replace(substr($attr,$len), '...', $attr);
        }
        return str_replace(substr($attr, 3, 6), '******', $attr);
    }

    /**
     * 云运动 - 迈步 - 办卡发送短信
     * @author 李慧恩 <lihuien@itsports.club>
     * @create 2017/6/21
     * @param $to       //发给谁
     * @param $cardName  //卡名
     * @param $deal     //合同名
     * @return string
     */
    public static function sellCardSendCode($to,$cardName,$deal)
    {
        $model = new Sms();
        $msg   = $model->setSellCardCode($to,$cardName,$deal);
        return json_encode($msg,true);
    }
    /**
     * 云运动 - 迈步 - 办卡发送短信
     * @author 李慧恩 <lihuien@itsports.club>
     * @create 2017/6/21
     * @param $to       //发给谁
     * @param $cardName  //卡名
     * @param $deal     //合同名
     * @return string
     */
    public static function memberBirthSendCode($to,$cardName)
    {
        $model = new Sms();
        $msg   = $model->setMemberBirthCode($to,$cardName);
        return json_encode($msg,true);
    }
    /**
     * 云运动 - 迈步 - 开业发送短信
     * @author 李慧恩 <lihuien@itsports.club>
     * @create 2017/6/21
     * @param $to       //发给谁
     * @param $str
     * @return string
     */
    public static function comeMemberSendCode($to,$str,$code)
    {
        $model = new Sms();
        $msg   = $model->setComeInMaiBuCode($to,$str,$code);
        return json_encode($msg,true);
    }
    /**
     * 云运动 - 会员维护 - 发送健身目标短信、发送饮食计划短信
     * @author zhumengke <zhumengke@itsports.club>
     * @create 2017/11/27
     * @param $to      //发给谁
     * @param $name    //卡名
     * @param $content //合同名
     * @return string
     */
    public static function sendFitnessDiet($to,$content)
    {
        $model = new Sms();
        $msg   = $model->sendFitnessDiet($to,$content);
        return json_encode($msg,true);
    }
    /**
     * 云运动 - 更柜管理 - 给会员提醒柜子
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/12/15
     * @param $to      //发给谁
     * @param $name    //用户名
     * @param $typeTame //区域名
     * @param $cabinetNumber//柜子编号
     * @param $endRent//到期时间
     * @return string
     */
    public static function sendCabinetData($to,$name,$typeTame,$cabinetNumber,$endRent)
    {
        $model = new Sms();
        $msg   = $model->sendCabinet($to,$name,$typeTame,$cabinetNumber,$endRent);
        return json_encode($msg,true);
    }
    /**
     * 云运动 - 迈步 - 办卡发送短信
     * @author 李慧恩 <lihuien@itsports.club>
     * @create 2017/6/21
     * @param $to       //发给谁
     * @param $cardName  //卡名
     * @param $url     //合同名
     * @return string
     */
    public static function sellAliCardSendCode($to,$cardName,$url)
    {
        $model = new Sms();
        $msg   = $model->setAliPaySellCardCode($to,$cardName,$url);
        return json_encode($msg,true);
    }
    /**
     * 云运动 - 迈步 - 返回订单号
     * @author 李慧恩 <lihuien@itsports.club>
     * @create 2017/6/21
     * @return string
     */
    public static function setOrderNumber()
    {
        $mTime     = explode(' ',microtime());
        $startTime = $mTime[1].($mTime[0]*1000);
        $timeArr   = explode('.',$startTime);
        if(is_array($timeArr) && isset($timeArr[0])){
           return  $timeArr[0].mt_rand(100,999);
        }
        return $startTime.mt_rand(100,999);
    }
    /**
     * 会员端 - API - 判断是否存在有效取餐码
     * @author 辛威 <xinwei@itsports.club>
     * @create 2018/07/11
     * @return string
     */
    public function getValidTakeMealCode($takeMealCode)
    {
        $data = MemberTakeMealRecord::find()
            ->where(['meal_code' => $takeMealCode])
            ->andWhere(['code_status' => 1])
            ->select('id')
            ->asArray()
            ->one();
        return $data;
    }
    /**
     * 会员端 - API - 返回取餐码
     * @author 辛威 <xinwei@itsports.club>
     * @create 2018/07/11
     * @return string
     */
    public static function getTakeMealCode()
    {
        $mkCode =  mt_rand(100001,999999);
        $flag = true;
        $takeMealCode = $mkCode;
        while($flag){
            $data = self::getValidTakeMealCode($takeMealCode);
            if(!$data){
                $flag = false;
            }
        }
        return $takeMealCode;
    }
    /**
     * 会员端 - API - 获取本月的第一天
     * @author 辛威 <xinwei@itsports.club>
     * @create 2018/07/13
     * @return string
     */
    public static function getCurMonthFirstDay($date) {
        return date('Y-m-01', strtotime($date));
    }
    /**
     * 会员端 - API - 获取本月的最后一天
     * @author 辛威 <xinwei@itsports.club>
     * @create 2018/07/13
     * @return string
     */
    public static function getCurMonthLastDay($date) {
        return date('Y-m-d', strtotime(date('Y-m-01', strtotime($date)) . ' +1 month -1 day'));
    }
    /**
     * 云运动 - 迈步 - 返回值
     * @author 李慧恩 <lihuien@itsports.club>
     * @create 2017/6/21
     * @param  $arr
     * @param  $name
     * @return string
     */
    public static function setReturnMessageArr($arr,$name)
    {
        if(isset($arr) && !empty($arr)){
            foreach ($arr as  $k=>$v){
                return $v[0];
            }
        }
        return $name;
    }

    /**
     * 云运动 - 迈步 - 返回关联值
     * 类似$model->member->memberCard->card_number用此方法防止因数据不符报错终止运行
     * 用法：Func::getRelationVal($model, 'member', 'memberCard', 'card_number')
     * @author zhangxiaobing <zhangxiaobing@itsports.club>
     * @create 2017/12/16
     * @param  $model
     * @param  $args
     * @return string
     */
    public static function getRelationVal()
    {
        $args = func_get_args();
        $model = array_shift($args);
        foreach ($args as $arg){
            if(!isset($model->$arg)) return '';
            $model = $model->$arg;
        }
        return $model;
    }

    /**
     * @云运动 - 微信公众号 - 去除合同富文本标签
     * @author 焦冰洋<jiaobingyang@itsports.club>
     * @param  $data
     * @create 2018/3/6
     * @inheritdoc
     */
    public static function format($data)
    {
        $content_01 = $data;//从数据库获取富文本content
        $content_02 = htmlspecialchars_decode($content_01);//把一些预定义的 HTML 实体转换为字符
        $content_03 = str_replace("&nbsp;","",$content_02);//将空格替换成空
        $contents   = strip_tags($content_03);//函数剥去字符串中的 HTML、XML 以及 PHP 的标签,获取纯文本内容
        return $contents;
    }

    /**
     * 运运动-七牛云上传base64文件
     * @author   yanghuilei<yanghuilei@itsport.club>
     * @createAt 2018/3/7
     * @param $base64_str
     * @param $fileName
     * @return mixed
     */
    public static function uploadBase64($fileName, $base64_str)
    {
        $qinNiu   = new Qiniu();
        $imgReUrl = $qinNiu->uploadBase64($fileName, $base64_str);
        return $imgReUrl;
    }
    /**
<<<<<<< HEAD
     * 会员端-进馆记录 (根据会员卡)
     * @author 辛威<xinwei@itsport.club>
     * @createAt 2018/06/21
     * @note 备注: 无
     * @tip 提示: 无
     * @param $cards
     * @param $start
     * @param $end
     * @param $venueId
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function EntryRecords($cards, $start, $end, $venueId = null)
    {
        $model = EntryRecord::find()
            ->where([
                'and',
                ['member_card_id' => $cards],
                ['between', 'entry_time', $start, $end]
            ]);

        $model->andFilterWhere(['venue_id' => $venueId]);
        $data = $model->asArray()->all();

        if(empty($data)) $data = [];
        return $data;
    }
    /**
     * 会员端-会员卡通店次数限制  包括（次/月、次/周）
     * @author 辛威<xinwei@itsport.club>
     * @createAt 2018/06/21
     * @note 备注: 无
     * @tip 提示: 无
     * @param $card 会员卡id
     * @param $time 课程开始时间
     * @param $venueId  场馆id
     * @return array | string
     */
    public static function cardEntryLimit($card, $venueId, $time)
    {
        $time = $time ?: time();
        // 获取会员卡通店数据
        $limitModel = VenueLimitTimes::find()
            ->where([
                'and',
                ['member_card_id' => $card],
                [
                    'or',
                    ['venue_id' => $venueId],
                    ['like', 'venue_ids', '"' . $venueId . '"']
                ]
            ])
            ->one();
        // 模型为null return exception
        if(empty($limitModel)) return 'exception';
        // 没有通店限制次数
        if($limitModel->week_times < 1 && $limitModel->total_times < 1){
            //没有通店次数限制,判断是否有通店时间限制
            $applyStart = $limitModel->apply_start;
            $applyEnd = $limitModel->apply_end;
            if (!empty($applyStart) && !empty($applyEnd)){
                if(date('H:i', $applyStart) <= date('H:i', $time) && date('H:i', $time) <= date('H:i', $applyEnd)){
                    return true;
                }
                return false;
            }elseif(!empty($applyStart)){
                if(date('H:i', $applyStart) <= date('H:i', $time)){
                    return true;
                }
                return false;
            }elseif(!empty($applyEnd)){
                if(date('H:i', $time) <= date('H:i', $applyEnd)){
                    return true;
                }
                return false;
            }
            //判断是否有进馆限制次数
            $timeModel = MemberCardTime::findOne(['member_card_id' => $card]);
            //无 进馆限制次数
            if(empty($timeModel)) return true;
            //日期 (号)
            $day = json_decode($timeModel->day, true);
            // 周
            $week = json_decode($timeModel->week, true);
            if(isset($day['day']) && !empty($day['day'])){
                //日期
                $nowDay = ltrim(date('d', $time), '0');
                if(in_array($nowDay, $day['day'])){
                    $start = $day['start'];
                    $end = $day['end'];
                    if(empty($start) && empty($end)) return true;
                    if(!empty($start) && !empty($end)){
                        if($start <= date("H:i", $time) && date("H:i", $time) <= $end) return true;
                        return false;
                    }elseif(!empty($start)){
                        if($start <= date("H:i", $time)) return true;
                        return false;
                    }else{
                        if(date("H:i", $time) <= $end) return true;
                        return false;
                    }
                }
                return false;
            }elseif(isset($week['weeks']) && !empty($week['weeks'])){
                //周
                $nowWeek = date('w', $time) ?: 7;
                if(in_array($nowWeek, $week['weeks'])){
                    $start = $week['startTime'];
                    $end = $week['endTime'];
                    if(empty($start) && empty($end)) return true;
                    $key = (int)current(array_keys($week['weeks'], $nowWeek));
                    if(!empty($start) && !empty($end)){
                        $startTimeColumn = $start[$key];
                        $endTimeColumn = $end[$key];
                        if(!empty($startTimeColumn) && !empty($endTimeColumn)){
                            if($startTimeColumn <= date("H:i", $time) && date("H:i", $time) <= $endTimeColumn) return true;
                            return false;
                        }elseif(!empty($startTimeColumn)){
                            if($startTimeColumn <= date("H:i", $time)) return true;
                            return false;
                        }else{
                            if(date("H:i", $time) <= $endTimeColumn) return true;
                            return false;
                        }
                    }elseif(!empty($start)){
                        $startTimeColumn = $start[$key];
                        if(!empty($startTimeColumn)){
                            if($startTimeColumn <= date("H:i", $time)) return true;
                            return false;
                        }
                        return true;
                    }else{
                        $endTimeColumn = $end[$key];
                        if(!empty($endTimeColumn)){
                            if(date("H:i", $time) <= $endTimeColumn) return true;
                            return false;
                        }
                        return true;
                    }
                }
                return false;
            }else{
                //没日期数据和没周数据 return true
                return true;
            }
        }
        // 次/周
        if($limitModel->week_times >= 1) return ['week' => $limitModel->overplus_times, 'start' => $limitModel->apply_start, 'end' => $limitModel->apply_end];
        // 次/月
        return ['month' => $limitModel->total_times, 'start' => $limitModel->apply_start, 'end' => $limitModel->apply_end];
    }
    /**
     * 会员端-进馆时间、进馆次数限制
     * @author 辛威<xinwei@itsport.club>
     * @createAt 2018/06/13
     * @note 备注: 无
     * @tip 提示: 无
     * @param $scenario
     * @param $cardId 会员卡id
     * @param $time 课程开始时间
     * @param $venueId 场馆id
     * @return bool
     */
    public static function entryVenueLimitSize($cardId, $venueId, $scenario = 'ENTRY_VENUE', $time = null)
    {
        $time = $time ?: time();
        //卡通店限制
        $reArr = Func::cardEntryLimit($cardId, $venueId, $time);
        //不能通店
        if ($reArr === 'exception') return 'noConnectedVenue';
        //没有进馆限制 return true
        if ($reArr === true) return true;
        //您的进馆时间与卡限制时间不对应
        if ($reArr === false) {
            if ($scenario == 'ENTRY_VENUE') {
                //进馆时间和您卡属性限制进馆时间不符
                return 'overEntryTime';
            }
            return 'overEntryTime';
        }
        //有通店限制
        //1. 周
        if (isset($reArr['week']) && !empty($reArr['week'])) {
            $entryRecord = Func::EntryRecords($cardId, _makeTime('week')['start'], _makeTime('week')['end'], $venueId);
            //当周进馆次数
            $entrySize = count($entryRecord);
            //周限制次数
            $limitSize = (int)$reArr['week'];
            // 进馆次数等于限制次数 看有没有出馆记录 没的话可以出去
            if ($entrySize == $limitSize) {
                // 预约团课限制
                if ($scenario != 'ENTRY_VENUE') {
                    return false;
                }
                $recordArr = array_column($entryRecord, 'leaving_time', 'entry_time');
                krsort($recordArr);
                reset($recordArr);
                $reArr = current($recordArr);
                if (empty($reArr)) {
                    return true;
                }
                return false;
            };
            // 超了 return false
            if ($entrySize > $limitSize) return false;
            // 没超 没有进馆时间段限制 return true
            if (empty($reArr['start']) && empty($reArr['end'])) return true;
            // 没超 有进馆时间段限制, 进馆或预约时间, 符合卡属性限制 return true
            $start = $reArr['start'] ? date('H:i', $reArr['start']) : null;
            $validTime = date('H:i', $time);
            $end = $reArr['end'] ? date('H:i', $reArr['end']) : null;
            if ($start <= $validTime && $validTime <= $end) {
                return true;
            } elseif ($start <= $validTime && $end === null) {
                return true;
            } elseif ($end >= $validTime && $start === null) {
                return true;
            } else {
                //进馆或预约时间, 不符合卡属性限制
                //return false
                if ($scenario == 'ENTRY_VENUE') {
                    return 'overEntryTime';
                }
                return 'overEntryTime';
            }
        }
        //2. 月
        $entryRecord = Func::EntryRecords($cardId, _makeTime('month')['start'], _makeTime('month')['end'], $venueId);
        $entrySize = count($entryRecord);
        $limitSize = (int)$reArr['month'];
        if ($entrySize == $limitSize) {
            // 预约团课限制
            if ($scenario != 'ENTRY_VENUE') {
                return false;
            }
            $recordArr = array_column($entryRecord, 'leaving_time', 'entry_time');
            krsort($recordArr);
            reset($recordArr);
            $reArr = current($recordArr);
            if (empty($reArr)) return true;
            return false;
        };
        if ($entrySize > $limitSize) return false;
        if (empty($reArr['start']) && empty($reArr['end'])) return true;
        $start = $reArr['start'] ? date('H:i', $reArr['start']) : null;
        $validTime = date('H:i', $time);
        $end = $reArr['end'] ? date('H:i', $reArr['end']) : null;
        if ($start <= $validTime && $validTime <= $end) {
            return true;
        } elseif ($start <= $validTime && $end === null) {
            return true;
        } elseif ($end >= $validTime && $start === null) {
            return true;
        } else {
            if ($scenario == 'ENTRY_VENUE') {
                return 'overEntryTime';
            }
            return 'overEntryTime';
        }
    }
    /*
     * 会员端-验证手机号码和座机号码
     * @author   xinwei<xinwei@itsport.club>
     * @createAt 2018/07/04
     */
    public static function validPhone($phone)
    {
        //手机号码
        $isMob = "/^1[34578]{1}\d{9}$/";
        //座机号码
        $isTel = "/^([0-9]{3,4}-)?[0-9]{7,8}$/";
        if ((!preg_match($isMob,$phone)) && (!!preg_match($isTel,$phone))) {
            return false;
        }
        return true;
        
    }
}