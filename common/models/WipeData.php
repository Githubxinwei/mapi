<?php

namespace common\models;

use backend\models\AuthMenu;
use backend\models\Employee;
use common\models\base\Admin;
use common\models\base\Approval;
use common\models\base\ApprovalRole;
use common\models\base\ApprovalType;
use common\models\base\Auth;
use backend\models\SmsRecord;
use common\models\base\AboutClass;
use common\models\base\BindPack;
use common\models\base\Cabinet;
use common\models\base\CardCategory;
use common\models\base\ChargeClass;
use common\models\base\Classroom;
use common\models\base\Config;
use common\models\base\Course;
use common\models\base\Deal;
use common\models\base\EntryRecord;
use common\models\base\Functional;
use common\models\base\GiftDay;
use common\models\base\Goods;
use common\models\base\GroupClass;
use common\models\base\ImageManagement;
use common\models\base\ImageManagementType;
use common\models\base\MemberCardTime;
use common\models\base\MachineRecord;
use common\models\base\Member;
use common\models\base\MemberAccount;
use common\models\base\MemberCabinet;
use common\models\base\MemberCard;
use common\models\base\MemberCourseOrder;
use common\models\base\MemberDetails;
use common\models\base\MissAboutSet;
use common\models\base\Mobilise;
use common\models\base\Module;
use common\models\base\Order;
use common\models\base\Organization;
use common\models\base\Position;
use common\models\base\Role;
use common\models\base\Server;
use common\models\base\ConsumptionHistory;
use common\models\base\StoreHouse;
use common\models\base\VenueLimitTimes;
use common\models\base\LeaveRecord;
use common\models\base\BindMemberCard;
use yii\base\Model;
use yii\helpers\VarDumper;

class WipeData extends Model
{
    const MEMBER_TABLE = ['cloud_member', 'cloud_member_details', 'cloud_consumption_history', 'cloud_member_card', 'cloud_member_cabinet', 'cloud_venue_limit_times'];
    const CARD_TABLE = ['cloud_card_category', 'cloud_card_time', 'cloud_limit_card_number', 'cloud_bind_pack'];
    const ABOUT_TABLE = ['cloud_about_class'];
    const CABINET_TABLE = ['cloud_cabinet', 'cloud_cabinet_type'];
    const GROUP_TABLE = ['cloud_group_class'];
    const CHARGE_TABLE = ['cloud_charge_class', 'cloud_course_package_detail'];
    const VENUE_TABLE = ['cloud_organization', 'cloud_classroom', 'cloud_seat'];
    const ORDER_TABLE = ['cloud_order'];
    const STAFF_TABLE = ['cloud_employee'];
    const SETTING = ['会员管理', '卡种管理', '约课管理', '柜子管理', '订单管理', '团课管理', '私教管理', '组织架构', '员工管理'];
    const SETTING_TABLE = ['member', 'card', 'about', 'cabinet', 'order', 'group', 'charge', 'venue', 'yee'];
    const Tag = ['user', 'credit-card', 'align-justify', 'tags'];
    const Icon = ['btn-danger', 'btn-info', 'btn-warning', 'btn-primary'];
    const WHITE_ARR = ['白毛巾', '白色毛巾'];
    const VIOLE_ARR = ['紫色毛巾', '紫毛巾'];
    public $tablePrefix = 'cloud_';
    public $whiteId;         //白色毛巾
    public $violetId;        //紫色毛巾
    public $memberId;
    public $memberCardId;
    public $venueId;
    public $error = [];
    public $pid;
    public $menuPid;

    /**
     * 云运动 - 数据库批量删除 - 执行sql
     * @param $sql
     * @return array
     */
    public function getDelSqlData($sql)
    {
        $queryBuilder = \Yii::$app->db->createCommand()->delete($sql);
        return $queryBuilder->execute();
    }

    /**
     * 云运动 - 数据库批量删除 - 执行查询数据
     * @author lihuien<lihuien@itsports.club>
     * @param $sql
     * @return array
     */
    public function getSqlData($sql)
    {
        return \Yii::$app->db->createCommand($sql)->queryAll();
    }

    /**
     * 云运动 - 数据库批量删除 - 入口文件
     * @author lihuien<lihuien@itsports.club>
     * @param $table string 入口
     * @return array
     */
    public function inlet($table)
    {
        switch ($table) {
            case 'member':
                $this->delAssociation(self::MEMBER_TABLE);
                return true;
            case 'order':
                $this->delAssociation(self::ORDER_TABLE);
                return true;
            case 'card':
                $this->delAssociation(self::CARD_TABLE);
                return true;
            case 'cabinet':
                $this->delAssociation(self::CABINET_TABLE);
                return true;
            case 'group':
                $this->delAssociation(self::GROUP_TABLE);
                return true;
            case 'charge':
                $this->delAssociation(self::CHARGE_TABLE);
                return true;
            case 'about':
                $this->delAssociation(self::ABOUT_TABLE);
                return true;
            case 'venue':
                $this->delAssociation(self::VENUE_TABLE);
                return true;
            case 'yee':
                $this->delAssociation(self::STAFF_TABLE);
                return true;
            default:
                $tableArr = $this->getTableAll();
                $this->delAssociation($tableArr);
                return true;
        }
    }

    /**
     * 云运动 - 数据库批量删除 - 删除表数据
     * @author lihuien<lihuien@itsports.club>
     * @param $data array 入口
     * @param $type
     * @return array
     */
    public function delAssociation($data, $type = 'array')
    {
        if ($type == 'array') {
            foreach ($data as $k => $v) {
                if ($this->getSelectTable($v)) {
                    $this->getDelSqlData($v);
                }
            }
        } else {
            $this->getDelSqlData($data);
        }
    }

    /**
     * 云论坛 - 数据库 - 获取数据库名称
     * @return array|string
     */
    public function getDatabaseName()
    {
        $sql = 'select database()';
        $name = self::getSqlData($sql);
        if (is_array($name) && !empty($name)) {
            $name = $name[0]['database()'];
        } else {
            $name = 'clouds';
        }

        return $name;
    }

    /**
     * 云运动 - 数据库批量删除 - 生成表sql
     * @author lihuien<lihuien@itsports.club>
     * @param $table string 入口
     * @return array
     */
    public function delTable($table)
    {
        $sql = 'DELETE FROM' . ' ' . $table;
        $posts = $this->getSqlData($sql);
        return $posts;
    }

    /**
     * 云运动 - 数据库批量删除 - 获取所有表名称
     * @author lihuien<lihuien@itsports.club>
     * @return array
     */
    public function getTableAll()
    {
        $sql = 'show table status';
        $posts = $this->getSqlData($sql);
        $data = $this->getTableDetail($posts);
        return $data;
    }

    /**
     * 云运动 - 数据库批量删除 - 获取详细信息
     * @author lihuien<lihuien@itsports.club>
     * @param $posts array
     * @return array
     */
    public function getTableDetail($posts)
    {
        $data = [];
        if ($posts && !empty($posts)) {
            foreach ($posts as $k => $v) {
                if ($v['Name'] == 'cloud_admin' || $v['Name'] == 'cloud_migration') {
                    continue;
                }
                $data[] = $v['Name'];
            }
        }
        return $data;
    }

    /**
     * 云运动 - 数据库批量删除 - 获取删除数据
     * @author lihuien<lihuien@itsports.club>
     * @return array
     */
    public function getTableData()
    {
        $data = [];
        foreach (self::SETTING as $k => $v) {
            $arr = [];
            $arr['name'] = self::SETTING_TABLE[$k];
            $arr['comment'] = $v;
            $arr['tag'] = self::Tag[mt_rand(0, 3)];
            $arr['icon'] = self::Icon[mt_rand(0, 3)];
            $data[] = $arr;
        }
        return $data;
    }

    /**
     * 云运动 - 数据库批量删除 - 查询所有表数据
     * @author lihuien<lihuien@itsports.club>
     * @param $table string 入口
     * @return array
     */
    public function getSelectTable($table)
    {
        $sql = 'SELECT * FROM' . ' ' . $table;
        $num = $this->getSqlData($sql);
        if ($num && count($num)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 云运动 - 数据库批量删除 - 设置毛巾数据
     * @author lihuien<lihuien@itsports.club>
     * @return array
     */
    public function setBindPick()
    {
        $server = $this->getServerOne(self::WHITE_ARR);
        if ($server && !empty($server)) {
            $this->whiteId = $server['id'];
        } else {
            $wid = $this->setServerTowel('白色毛巾');
            $this->whiteId = $wid;
        }
        $serverV = $this->getServerOne(self::VIOLE_ARR);
        if ($serverV && !empty($serverV)) {
            $this->violetId = $serverV['id'];
        } else {
            $vid = $this->setServerTowel('紫色毛巾');
            $this->violetId = $vid;
        }
    }

    /**
     * 云运动 - 数据库批量删除 - 设置服务数据
     * @author lihuien<lihuien@itsports.club>
     * @param  $arr
     * @return array
     */
    public function getServerOne($arr)
    {
        return Server::find()->select('id')->where(['in', 'name', $arr])->one();
    }

    /**
     * 云运动 - 数据库批量删除 - 设置服务数据
     * @author lihuien<lihuien@itsports.club>
     * @param  $name
     * @return array
     */
    public function setServerTowel($name)
    {
        $server = new Server();
        $server->name = $name;
        $server->create_at = time();
        $server->description = '毛巾服务';
        if (!$server->save()) {
            return false;
        }
        return $server->id;
    }

    public function setCardTowel()
    {
        $this->setBindPick();
        $card = CardCategory::find()->all();
        if (!empty($card)) {
            foreach ($card as $k => $v) {
                if (strpos($v['card_name'], '金爵') || strpos($v['card_name'], '尊爵')) {
                    $this->saveBindPack($v['id'], $this->violetId);
                } else {
                    $this->saveBindPack($v['id'], $this->whiteId);
                }
            }
        }
        return $this->error;
    }

    public function saveBindPack($id, $tid)
    {
        $bink = new BindPack();
        $bink->card_category_id = $id;
        $bink->number = 1;
        $bink->polymorphic_id = $tid;
        $bink->polymorphic_type = 'server';
        $bink->status = 2;
        if ($bink->save()) {
            return true;
        }
        return $bink->errors;
    }

    /**
     * 云运动 - 修改 - 获取会员信息
     * @create 2017/5/16
     * @author lihuien <lihuien@itsports.club>
     * @return bool
     */
    public function setMemberInputData()
    {
        return [
            [10200196, '陈建伟', '女', '', '11:50:16', '00:00:00', '', '键盘'],
            [10100281, '孟捷', '女', '', '13:07:34', '00:00:00', '', '键盘'],
            [10300196, '朱云黎', '女', '', '10:59:35', '00:00:00', '', '键盘'],
            [80000159, '李喜慧', '女', '', '07:40:20', '00:00:00', '', '键盘'],
            [10200209, '李本兵', '男', '', '12:16:55', '00:00:00', '', '键盘'],
            [0730000650, '崔新玲', '女', '', '14:39:51', '00:00:00', '', '键盘'],
            [10300196, '朱云黎', '女', '', '07:46:30', '00:00:00', '', '键盘'],
            [10100155, '王翔宇', '男', '', '12:11:39', '00:00:00', '', '键盘'],
        ];
    }

    /**
     * 云运动 - 修改 - 批量生成会员信息
     * @create 2017/5/16
     * @author lihuien <lihuien@itsports.club>
     * @return bool
     */
    public function setMemberBatchData()
    {
        $data = $this->setMemberInputData();
        foreach ($data as $k => $v) {
            $transaction = \Yii::$app->db->beginTransaction();     //事务开始
            try {
                $member = new Member();
                $memberDetail = new MemberDetails();
                $memberCard = new MemberCard();
                $entry = new EntryRecord();

                //插入会员表
                //判断会员是否存在(根据手机号码)

                $memberModel = Member::find()->where(["username" => $v[1]])->asArray()->one();
                if (empty($memberModel)) {
                    $password = '123456';
                    $password = \Yii::$app->security->generatePasswordHash($password);
                    $member->username = isset($v[1]) ? $v[1] : '暂无';
                    $member->mobile = '0';
                    $member->password = $password;
                    $member->register_time = time();
                    $member->counselor_id = 0;
                    $member->status = 1;
                    if (!$member->save()) {
                        $error = $member->errors;
                        return $error;
                    }
                    $this->memberId = $member->id;
                } else {
                    $this->memberId = $memberModel['id'];
                }
                //插入会员详情表
                $memberDetail_model = MemberDetails::find()->where(["member_id" => $this->memberId])->asArray()->one();
                if (empty($memberDetail_model)) {
                    $memberDetail->member_id = $this->memberId;
                    $memberDetail->name = $v[1];
                    $memberDetail->sex = $v[2] == '男' ? 1 : 2;
                    $memberDetail->created_at = time();
                    $memberDetail->recommend_member_id = 0;
                    if (!$memberDetail->save()) {
                        $error = $member->errors;
                        return $error;
                    }
                }
                //判断会员卡号是否存在
                $memberCardModel = MemberCard::find()->where(["card_number" => "$v[0]"])->asArray()->one();
                if (empty($memberCardModel)) {
                    $memberCard->card_number = "$v[0]";
                    $memberCard->create_at = time();
                    $memberCard->active_time = 0;
                    $memberCard->member_id = $this->memberId;
                    $memberCard->card_category_id = 0;
                    $memberCard->payment_type = 1;
                    $memberCard->payment_time = 1;
                    $memberCard->is_complete_pay = 1;
                    $memberCard->level = 1;
                    $memberCard->status = 1;
                    $memberCard->invalid_time = 0;
                    $memberCard->payment_time = 0;
                    if (!$memberCard->save()) {
                        $error = $memberCard->errors;
                        return $error;
                    }
                    $this->memberCardId = $memberCard->id;
                } else {
                    $this->memberCardId = $memberCardModel['id'];
                }
                $this->getVenueId();
                $entry->entry_time = strtotime('2017-5-23 ' . $v[4]);
                $entry->create_at = time();
                $entry->member_id = $this->memberId;
                $entry->member_card_id = $this->memberCardId;
                $entry->venue_id = $this->venueId;
                if (!$entry->save()) {
                    return $entry->errors;
                }
                if ($transaction->commit() !== null) {
                    return false;
                }
            } catch (\Exception $ex) {
                $transaction->rollBack();
                return $ex->getMessage();
            }
        }
    }

    /**
     * 云运动 - 验卡系统 - 获取场馆
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/20
     * @return boolean
     */
    public function getVenueId()
    {
        $venue = Organization::find()->where(['like', 'name', '大上海'])->asArray()->one();
        if (isset($venue['id'])) {
            $this->venueId = $venue['id'];
        } else {
            $this->venueId = 2;
        }
    }

    /**
     * 云运动 - 验卡系统 - 获取场馆
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/20
     * @return boolean
     */
    public static function getMemberCardNoName()
    {
        $memberCard = MemberCard::find()->where(['card_name' => null])->all();
        $num = 0;
        if ($memberCard && !empty($memberCard)) {
            foreach ($memberCard as $k => $v) {
                $member = MemberCard::findOne(['id' => $v['id']]);
                $card = CardCategory::find()->where(['id' => $v['card_category_id']])->asArray()->one();
                $member->card_name = $card['card_name'];
                if (!$member->save()) {
                    return $member->errors;
                }
                $num++;
            }
        }
        return $num;
    }

    /**
     * 云运动 - 验卡系统 - 修改为场馆卡种会籍顾问
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/20
     * @return boolean
     */
    public static function setMemberEmployee()
    {
        $member = \backend\models\Member::find()->joinWith(['memberCard mc' => function ($q) {
            $q->where(['>', 'mc.create_at', strtotime('2017-10-10')]);
            $q->orderBy('mc.create_at DESC')->one();
        }])->asArray()->all();
        $num = 0;
        foreach ($member as $v) {
            if ($v['memberCard'] && $v['memberCard'][0]) {
                $history = ConsumptionHistory::find()->where(['consumption_type_id' => $v['memberCard'][0]['id'], 'consumption_type' => 'card'])->asArray()->one();
                if ($history['seller_id']) {
                    $user = Member::findOne(['id' => $v['id']]);
                    $user->counselor_id = $history['seller_id'];
                    $user->save();
                }
                $num++;
            }
        }
        return $num;
    }

    /**
     * 云运动 - 验卡系统 - 获取场馆
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/20
     * @return boolean
     */
    public static function setApplyCard()
    {
        $ganArr = Organization::find()->where(['or', ['like', 'name', '我爱运动'], ['like', 'name', '艾搏']])->asArray()->all();
        $idArr = array_column($ganArr, 'id');
        $venueArr = Organization::find()->where(['in', 'pid', $idArr])->andWhere(['style' => 2])->asArray()->all();
        $zunArr = Organization::find()->andWhere(['or', ['like', 'name', '大卫城'], ['like', 'name', '艾搏']])->asArray()->all();
        $venueArr = array_column($venueArr, 'id');
        $zunArr = array_column($zunArr, 'id');
        $memberCard = MemberCard::find()->where(['like', 'card_number', '10%', false])->andWhere(['NOT', ['venue_id' => null]])->asArray()->all();
        $num = 0;
        if ($memberCard && !empty($memberCard)) {
            foreach ($memberCard as $k => $v) {
                if (in_array($v['venue_id'], $zunArr)) {
                    foreach ($venueArr as $key => $val) {
                        $limit = VenueLimitTimes::findOne(['member_card_id' => $v['id'], 'venue_id' => $val]);
                        if (empty($limit)) {
                            $times = new VenueLimitTimes();
                            $times->member_card_id = $v['id'];
                            $times->venue_id = $val;
                            $times->total_times = -1;
                            $times->overplus_times = -1;
                            $times->company_id = null;
                            if (in_array($val, $zunArr) && $val != $v['venue_id']) {
                                $times->level = 1;
                            } else {
                                $times->level = 2;
                            }
                            if (!$times->save()) {
                                return $times->errors;
                            }
                        }
                    }
                } else {
                    foreach ($venueArr as $key => $val) {
                        $limit = VenueLimitTimes::findOne(['member_card_id' => $v['id'], 'venue_id' => $val]);
                        if (empty($limit)) {
                            $times = new VenueLimitTimes();
                            $times->member_card_id = $v['id'];
                            $times->venue_id = $val;
                            $times->total_times = -1;
                            if (in_array($val, $zunArr)) {
                                $times->overplus_times = 6;
                            } else {
                                $times->overplus_times = -1;
                            }
                            $times->company_id = null;
                            if ($val == $v['venue_id']) {
                                $times->level = 2;
                            } else {
                                $times->level = 1;
                            }
                            if (!$times->save()) {
                                return $times->errors;
                            }
                        } else {
                            if (in_array($val, $zunArr) && $limit->overplus_times == -1) {
                                $limit->overplus_times = 6;
                                $limit->save();
                            }
                        }
                    }
                }
                $num++;
            }
        }
        return $num;
    }

    /**
     * 云运动 - 验卡系统 - 获取场馆
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/20
     * @return boolean
     */
    public static function setApplyZunCard()
    {
        $ganArr = Organization::find()->where(['or', ['like', 'name', '我爱运动'], ['like', 'name', '艾搏']])->asArray()->all();
        $idArr = array_column($ganArr, 'id');
        $venueArr = Organization::find()->where(['in', 'pid', $idArr])->asArray()->all();
        $zunArr = Organization::find()->andWhere(['or', ['like', 'name', '大卫城'], ['like', 'name', '艾搏']])->asArray()->all();
        $venueArr = array_column($venueArr, 'id');
        $zunArr = array_column($zunArr, 'id');
        $memberCard = MemberCard::find()->where(['like', 'card_number', '80%', false])->andWhere(['NOT', ['venue_id' => null]])->asArray()->all();
        $num = 0;
        if ($memberCard && !empty($memberCard)) {
            foreach ($memberCard as $k => $v) {
                foreach ($venueArr as $key => $val) {
                    $limit = VenueLimitTimes::findOne(['member_card_id' => $v['id'], 'venue_id' => $val]);
                    if (empty($limit)) {
                        $times = new VenueLimitTimes();
                        $times->member_card_id = $v['id'];
                        $times->venue_id = $val;
                        $times->total_times = -1;
                        $times->overplus_times = -1;
                        $times->company_id = null;
                        if (!$times->save()) {
                            return $times->errors;
                        }
                    }
                }
                $num++;
            }
        }
        return $num;
    }

    public static function setMemberVenue()
    {
        $card = MemberCard::find()->where(['venue_id' => null])->asArray()->all();
        $num = 0;
        if (!empty($card)) {
            foreach ($card as $k => $v) {
                $member = Member::findOne(['id' => $v['member_id']]);
                if (isset($member->venue_id) && !empty($member->venue_id)) {
                    $memberCard = MemberCard::findOne(['id' => $v['id']]);
                    $memberCard->venue_id = $member->venue_id;
                    $memberCard->company_id = $member->company_id;
                    $memberCard->save();
                    $num++;
                }
            }
        }
        return $num;
    }

    /**
     * 云运动 - 验卡系统 - 获取场馆
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/20
     * @return boolean
     */
    public static function getMemberCompany()
    {
        $member = Member::find()->where(['or', ['venue_id' => null], ['venue_id' => 0]])->andWhere(['company_id' => null])->asArray()->all();
        $num = 0;
        if ($member && !empty($member)) {
            foreach ($member as $k => $v) {
                $model = Member::findOne(['id' => $v['id']]);
                $card = Employee::find()->where(['id' => $v['counselor_id']])->asArray()->one();
                if (!empty($card)) {
                    $model->venue_id = $card['venue_id'];
                    $model->company_id = $card['company_id'];
                    if (!$model->save()) {
                        return $model->errors;
                    }
                    $num++;
                }
            }
        }
        return $num;
    }

    /**
     * 云运动 - 验卡系统 - 交换卡号
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/20
     * @param  $numberOne
     * @param  $numberTwo
     * @return boolean
     */
    public static function setCardChangeNumber($numberOne, $numberTwo)
    {
        $cardOne = MemberCard::findOne(['card_number' => $numberOne]);//20000006
        $cardTwo = MemberCard::findOne(['card_number' => $numberTwo]);//09010215
        if (empty($cardOne) || empty($cardTwo)) {
            return ['status' => 'error', 'data' => '输入的卡号不正确'];
        }
        $id = $cardOne->id;
        $time = time();
        $cardOne->card_number = "{$time}";
        if (!$cardOne->save()) {
            return ['status' => 'error', 'data' => $cardOne->errors];
        }
        $cardOneId = MemberCard::findOne(['id' => $id]);
        $cardOneId->card_number = $numberTwo;
        $cardTwo->card_number = $numberOne;
        if ($cardTwo->save() && $cardOneId->save()) {
            return ['status' => 'success', 'data' => '操作成功'];
        }
        return ['status' => 'error', 'data' => '卡号交换失败2'];
    }

    /**
     * 云运动 - 验卡系统 - 获取卡
     * @author lihuien <lihuien@itsports.club>
     * @create 2017/4/20
     * @return boolean
     */
    public static function getCardChangeNumber()
    {
        $member = Member::find()
            ->alias('mm')
            ->select('mm.username')
            ->joinWith(['memberCard memberCard'])
            ->asArray()->all();
        return $member;
    }

    /**
     * 批量添加菜单，功能
     */
    public function setModuleModel()
    {
        $topMenu = AuthMenu::MENU;
        $subMenu = AuthMenu::SUB_MENU;
        $func = AuthMenu::FUNC;
        $menuDetail = AuthMenu::MENU_DETAIL;
        $idArr = [];
        foreach ($topMenu as $key => $value) {
            if ($this->getModuleMenu($key)) {
                continue;
            }
            $menu = new Module();
            $menu->name = $value;
            $menu->e_name = $key;
            $menu->pid = 0;
            $menu->create_at = time();
            $menu->update_at = time();
            if ($menu->save()) {
                $idArr[] = $menu->id;
            } else {
                return $menu->errors;
            }
        }
        foreach ($subMenu as $k1 => $v1) {
            $this->pid = isset($idArr[$k1]) ? $idArr[$k1] : 0;
            foreach ($v1 as $k2 => $v2) {
                if ($this->getModuleMenu($k2)) {
                    continue;
                }
                $menu = new Module();
                $menu->name = $v2;
                $menu->e_name = $k2;
                $menu->pid = $this->pid;
                $menu->url = $menuDetail[$k1][$k2];
                $menu->create_at = time();
                $menu->update_at = time();
                if (!$menu->save()) {
                    return $menu->errors;
                }
            }
        }
        foreach ($func as $key => $value) {
            if ($this->getFuncMenu($value[1])) {
                continue;
            }
            $func = new Functional();
            $func->name = $value[0];
            $func->e_name = $value[1];
            $func->note = $value[2];
            $func->create_at = time();
            $func->update_at = time();
            if (!$func->save()) {
                return $func->errors;
            }
        }
        return true;
    }

    public function getModuleMenu($name)
    {
        return Module::find()->where(['e_name' => $name])->asArray()->one();
    }

    public function getFuncMenu($name)
    {
        return Functional::find()->where(['e_name' => $name])->asArray()->one();
    }

    /**
     * 云运动 - 员工管理 - 批量修改员工信息
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/6/24
     * @return boolean
     */
    public function getEmployee()
    {
        $employee = Employee::find()->where(['or', ['company_id' => null], ['venue_id' => null]])->asArray()->all();
        if (!empty($employee)) {
            foreach ($employee as $key => $value) {
                $organizationVenueId = Organization::find()->where(['id' => $value['organization_id']])->one();
                $organizationCompanyId = Organization::find()->where(['id' => $organizationVenueId['pid']])->one();
                $employeeOne = \common\models\base\Employee::findOne(['id' => $value['id']]);
                $employeeOne->venue_id = $organizationCompanyId['id'];
                $employeeOne->company_id = $organizationCompanyId['pid'];
                if (!$employeeOne->save()) {
                    return $employeeOne->errors;
                }
            }
        }
        return true;
    }

    /**
     * 云运动 - 会员详情 - 续费记录到期时间批量修改
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/10/23
     * @return boolean
     */
    public function consumptionUpdate()
    {
        $consumption = ConsumptionHistory::find()->where(['due_date' => null])->andWhere(['consumption_type' => 'card'])->asArray()->all();
        if (!empty($consumption)) {
            foreach ($consumption as $key => $value) {
                $memberCard = MemberCard::find()->where(['id' => $consumption['consumption_type_id']])->asArray()->one();
                $consumptionOne = ConsumptionHistory::findOne(['id' => $value['id']]);
                $consumptionOne->due_date = $memberCard['invalid_time'];
                if (!$consumptionOne->save()) {
                    return $consumptionOne->errors;
                }
            }
        }
        return true;
    }

    /**
     * 云运动 - 会员详情 - 批量头像修改
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/11/29
     * @return boolean
     */
    public function memberPicUpdate()
    {
        $memberDetails = MemberDetails::find()->where(['pic' => ''])->asArray()->all();
        if (!empty($memberDetails)) {
            $memberDetailsId = array_column($memberDetails, 'id');
            $pic = MemberDetails::updateAll(['pic' => null], ['id' => $memberDetailsId]);
        }
        return true;
    }

    /**
     * 云运动 - 会员状态 - 没有会员卡的会员批量修改把会员变为潜在会员
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/11/8
     * @return boolean
     */
    public function memberUpdate()
    {
        $member = \backend\models\Member::find()
            ->alias('mm')
            ->joinWith(['memberCard mc'])
            ->where(['mm.member_type' => 1])
            ->andWhere(['mc.member_id' => null])
            ->asArray()
            ->all();
        if (!empty($member)) {
            $memberId = array_column($member, 'id');
            $updateMember = Member::updateAll(['member_type' => 2], ['id' => $memberId]);
        }
        return true;
    }

    /**
     * 云运动 - 会员详情 - 消费记录花丹场馆id批量修改
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/1/10
     * @return boolean
     */
    public function venueUpdate()
    {
        $member = \backend\models\Member::find()
            ->alias('mm')
            ->where(['mm.member_type' => 1])
            ->andWhere(['mm.venue_id' => 59])
            ->asArray()
            ->all();
        if (!empty($member)) {
            $memberId = array_column($member, 'id');
            $updateConsumption = ConsumptionHistory::updateAll(['venue_id' => 59], ['member_id' => $memberId]);
        }
        return true;
    }

    /**
     * 云运动 - 请假管理 - 批量修改状态
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/1/10
     * @return boolean
     */
    public function studentUpdateData()
    {
        $leave = LeaveRecord::find()
            ->alias('lr')
            ->where(['lr.leave_property' => 3])
            ->andWhere(['or', ['lr.leave_type' => 4], ['lr.leave_type' => 5]])
            ->asArray()
            ->all();
        if (!empty($leave)) {
            $leaveId = array_column($leave, 'id');
            $updateLeave = LeaveRecord::updateAll(['leave_property' => 2], ['id' => $leaveId]);
        }
        return true;
    }

    /**
     * 云运动 - 会员详情 - 会员卡场馆id批量修改
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/1/10
     * @return boolean
     */
    public function venueCardUpdate()
    {
        $memberCard = \backend\models\MemberCard::find()
            ->where(['venue_id' => null])
            ->asArray()
            ->all();
        if (!empty($memberCard)) {
            foreach ($memberCard as $key => $value) {
                $memberVenueId = Member::findOne(['id' => $value['member_id']]);
                $memberCardOne = MemberCard::findOne(['id' => $value['id']]);
                $memberCardOne->venue_id = $memberVenueId['venue_id'];
                if (!$memberCardOne->save()) {
                    return $memberCardOne->errors;
                };
            }
        }
        return true;
    }

    /**
     * 请假管理 - 请假列表 - 批量修改请假状态 区分请假类型和状态
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/1/10
     * @return boolean
     */
    public function leaveRecordData()
    {
        $leaveData = LeaveRecord::find()
            ->asArray()
            ->all();
        if (!empty($leaveData)) {
            foreach ($leaveData as $key => $value) {
                $leaveDataOne = LeaveRecord::findOne(['id' => $value['id']]);
                if ($leaveDataOne['leave_property'] == '4') {
                    $leaveDataOne->type = 2;
                } else {
                    $leaveDataOne->type = $value['leave_property'];
                }
                if (!$leaveDataOne->save()) {
                    return $leaveDataOne->errors;
                };
            }
        }
        return true;
    }

    /**
     * 请假管理 - 请假列表 - 批量修改请假类型
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/1/10
     * @return boolean
     */
    public function updateLeaveRecord()
    {
        $leaveData = LeaveRecord::find()
            ->asArray()
            ->all();
        if (!empty($leaveData)) {
            foreach ($leaveData as $key => $value) {
                $leaveDataOne = LeaveRecord::findOne(['id' => $value['id']]);
                if ($leaveDataOne['leave_type'] == '1') {
                    $leaveDataOne->leave_property = 2;
                }
                if ($leaveDataOne['leave_type'] == '2') {
                    $leaveDataOne->leave_property = 1;
                }
                if ($leaveDataOne['leave_type'] == '4' || $leaveDataOne['leave_type'] == '5') {
                    $leaveDataOne->leave_property = 3;
                }
                if (!$leaveDataOne->save()) {
                    return $leaveDataOne->errors;
                };
            }
        }
        return true;
    }

    /**
     * 公共管理 -会员卡绑定套餐 - 批量插入
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/1/3
     * @param $venueId
     * @return boolean
     */
    public function insertBind($venueId)
    {
        $bindPack = BindPack::find()
            ->asArray()
            ->all();
        foreach ($bindPack as $key => $value) {
            $memberCard = MemberCard::find()->where(['and', ['venue_id' => $venueId], ['card_category_id' => $value['card_category_id']]])->asArray()->all();
            foreach ($memberCard as $k => $v) {
                $bindData = BindMemberCard::findOne(['member_card_id'=>$v['id']]);
                if(empty($bindData)){
                    $model = new BindMemberCard;
                    $model->member_card_id    = $v['id'];
                    $model->polymorphic_id    = $value['polymorphic_id'];
                    $model->polymorphic_type  = $value['polymorphic_type'];
                    $model->number            = $value['number'];
                    $model->status            = $value['status'];
                    $model->company_id        = $value['company_id'];
                    $model->venue_id          = $value['venue_id'];
                    $model->polymorphic_ids   = $value['polymorphic_ids'];
                    if ($model->save() != true) {
                        return $model->errors;
                    }
                }
            }
        }
        return true;

    }

    /**
     * 云运动 - 会员详情 - 消费记录场馆id批量修改
     * @author huanghua <huanghua@itsports.club>
     * @create 2017/1/10
     * @param $venueId
     * @return boolean
     */
    public function ConsumptionUpdateData($venueId)
    {
        $member = \backend\models\Member::find()
            ->alias('mm')
            ->where(['mm.member_type' => 1])
            ->andWhere(['mm.venue_id' => $venueId])
            ->asArray()
            ->all();
        if (!empty($member)) {
            $memberId = array_column($member, 'id');
            $updateConsumption = ConsumptionHistory::updateAll(['venue_id' => $venueId], ['member_id' => $memberId]);
        }
        return true;
    }
//    /**
//     * 云运动 - 会员详情 - 消费记录场馆id批量修改
//     * @author huanghua <huanghua@itsports.club>
//     * @create 2017/1/10
//     * @return boolean
//     */
//    public function ConsumptionUpdateData()
//    {
//        $consumptionHistory = \backend\models\ConsumptionHistory::find()
//            ->alias('ch')
//            ->where(['ch.venue_id'=>null])
//            ->andWhere(['and',['ch.consumption_type'=>'card'],['>','ch.consumption_date',1496250061]])
//            ->select("ch.id,ch.member_id,ch.venue_id,ch.consumption_type,ch.consumption_date")
//            ->asArray()
//            ->all();
//        if(!empty($consumptionHistory)){
//            foreach ($consumptionHistory as $key => $value){
//                $memberVenueId                    = Member::findOne(['id'=>$value['member_id']]);
//                $consumptionHistoryOne            = ConsumptionHistory::findOne(['id'=>$value['id']]);
//                $consumptionHistoryOne->venue_id  = $memberVenueId['venue_id'];
//                if(!$consumptionHistoryOne->save()){
//                    return $consumptionHistoryOne->errors;
//                };
//            }
//        }
//        return true;
//    }


    public function editMemberCard($data)
    {
        if (!$data['id']) {
            return ['status' => 'error', 'message' => '请求失败'];
        }
        $memberCard = MemberCard::findOne(['id' => $data['id']]);
        $consumptionHistoryOne = ConsumptionHistory::find()
            ->where(['and',['member_id'=>$memberCard['member_id']],['consumption_type_id'=>$memberCard['id']]])
            ->orderBy('id DESC')
            ->asArray()
            ->one();
        $consumptionHistory = ConsumptionHistory::findOne(['id' => $consumptionHistoryOne['id']]);
        if ($memberCard['card_number'] == $data['number']) {
            $memberCard->card_number = $data['number'];
        } else {
            $memberCardNumber = MemberCard::findOne(['card_number' => $data['number']]);
            if (!empty($memberCardNumber['card_number'])) {
                return "修改的会员卡号已经存在!";
            }
        }
        $memberCard->card_category_id = $data['cardCategoryId'];
        $memberCard->card_number = $data['number'];
        $memberCard->leave_type = $data['leaveType'];
        if (!empty($data['activeTime'])) {
            $memberCard->active_time = $data['activeTime'];
            $memberCard->status = 1;
        }
        if (empty($data['expireDate'])) {
            return "卡种到期时间不能为空";
        }else{
            $memberCard->invalid_time = strtotime($data['expireDate']) + 86399;
            if (!empty($consumptionHistory)) {
                $consumptionHistory->due_date = strtotime($data['expireDate']) + 86399;
                $consumptionHistory->save();
            }
        }
        if (!empty($data['createTime'])) {
            $memberCard->create_at = $data['createTime'];
            if (!empty($consumptionHistory)) {
                $consumptionHistory->consumption_date = $data['createTime'];
                $consumptionHistory->save();
            }
        }
        if (!empty($data['cardName'])) {
            $memberCard->card_name = $data['cardName'];
        }
        if (!empty($data['attributes'])) {
            $memberCard->attributes = $data['attributes'];
        }
        if (!empty($data['money'])) {
            $memberCard->amount_money = $data['money'];
        }
        if (!empty($data['transferNumber']) || $data['transferNumber'] == 0) {
            $memberCard->transfer_num = $data['transferNumber'];
            $memberCard->surplus = $data['transferNumber'];
        }
        if (!empty($data['postponeDate'])) {
            $postponeDate = strtotime($data['postponeDate']);
            if (!empty($memberCard->status) && $memberCard->status == 4) {
                $time = ($postponeDate - $memberCard->create_at) / (24 * 60 * 60);
                $memberCard->active_limit_time = intval($time);
            } else if (!empty($memberCard->status) && $memberCard->status == 1) {
                $endDate = ($memberCard->invalid_time - $memberCard->active_time);
                $memberCard->active_time = $postponeDate;
                $memberCard->invalid_time = $postponeDate + $endDate;
            }
        }
        if ($memberCard->save()) {
            return true;
        }
        return $memberCard->errors;
    }

    /**
     * 业务后台 - 会员管理 - 提醒会员生日
     * @param $id
     * @return array|bool
     */
    public function memberBirth($id)
    {
        $beginLastWeek = mktime(0, 0, 0, date('m'), date('d') - date('w') + 1 + 7, date('Y'));
        $endLastWeek = mktime(23, 59, 59, date('m'), date('d') - date('w') + 7 + 7, date('Y'));
        $member = \backend\models\Member::find()->alias('mm')
            ->joinWith(['memberDetails md'])
            ->joinWith(['venue venue'])
            ->andFilterWhere([
                'and',
                ['>=', 'date_format(md.birth_date,"%m-%d")', date('m-d', $beginLastWeek)],
                ['<=', 'date_format(md.birth_date,"%m-%d")', date('m-d', $endLastWeek)]
            ])
            ->andWhere(['NOT', ['mm.mobile' => null]])
            ->andWhere(['mm.venue_id' => $id])
            ->asArray()
            ->all();
        return $member;
    }

    /**
     * 业务后台 - 会员管理 - 提醒会员生日
     * @param  $id
     * @return array|bool
     */
    public function memberBirthReminder($id)
    {
        $data = $this->memberBirth($id);
        if (isset($data) && !empty($data)) {
            foreach ($data as $k => $v) {
                $sms = SmsRecord::find()->where(['send_code' => 'ujVCz1', 'mobile' => $v['mobile']])->orderBy('id DESC')->asArray()->one();
                if (date('Y', $sms['create_at']) != date('Y', time())) {
                    Func::memberBirthSendCode($v['mobile'], $v['venue']['name']);
                }
            }
            return true;
        }
        return true;
    }

    /**
     * 业务后台 - 会员管理 - 设置私课课量
     * @return array|bool
     */
    public static function setCourseNum()
    {
        $query = \backend\models\MemberCourseOrderDetails::find()->where(['or', ['product_name' => 'PT'], ['product_name' => 'HS']])->asArray()->all();
        $num = 0;
        if (!empty($query)) {
            foreach ($query as $k => $v) {
                $memberCourse = \backend\models\MemberCourseOrder::find()->alias('mco')
                    ->joinWith(['member member'])
                    ->where(['mco.id' => $v['course_order_id']])
                    ->andWhere(['member.venue_id' => [2, 14]])
                    ->asArray()->one();
                if (!empty($memberCourse)) {
                    $count = \backend\models\MemberCourseOrderDetails::find()->where(['course_order_id' => $v['course_order_id']])->count();
                    if ($count > 1) {
                        $num++;
                    } else {
                        $model = \backend\models\MemberCourseOrderDetails::findOne(['id' => $v['id']]);
                        $model->course_num = $memberCourse['overage_section'];
                        $model->course_name = $model->product_name . '课程';
                        $model->save();
                        $num++;
                    }
                }
            }
        }
        return $num;
    }

    /**
     * 业务后台 - 会员管理 - 设置会员进场管次数
     * @return array|bool
     */
    public static function setLimitTimes()
    {
        $limit = \backend\models\MemberCard::find()->alias('mc')->where(['IS', 'vlt.member_card_id', null])
            ->joinWith(['venueLimitTimesArr vlt'])
            ->asArray()->all();
        $num = 0;
        if (!empty($limit)) {
            foreach ($limit as $key => $value) {
                $num++;
                if (!empty($value['card_category_id'])) {
                    $card = CardCategory::findOne(['id' => $value['card_category_id']]);
                    $limit = LimitCardNumber::find()->where(['card_category_id' => $value['card_category_id'], 'status' => [1, 3]])->asArray()->all();
                    if (isset($limit)) {
                        foreach ($limit as $k => $v) {
                            $organ = Organization::findOne(['id' => $v['venue_id']]);
                            $result = self::setLimitVenue($value['id'], $v, $organ['pid']);
                            if ($result !== true) {
                                return $result;
                            }
                        }
                        $limitOne = LimitCardNumber::find()->where(['card_category_id' => $value['card_category_id'], 'venue_id' => $card['venue_id']])->one();
                        if (!empty($limitOne)) {
                            $result = self::setLimitVenue($value['id'], $limitOne, $organ['pid']);
                            if ($result !== true) {
                                return $result;
                            }
                        }
                    }
                }
            }
        }
        return $num;
    }

    /**
     * 业务后台 - 会员管理 - 设置会员进场管次数
     * @param  $id
     * @param  $data
     * @param  $pid
     * @return array|bool
     */
    public static function setLimitVenue($id, $data, $pid)
    {
        $limitVenue = new VenueLimitTimes();
        $limitVenue->member_card_id = $id;
        $limitVenue->venue_id = $data['venue_id'];
        $limitVenue->total_times = $data['times'];
        if (!empty($v['times'])) {
            $limitVenue->overplus_times = $data['times'];
        } else {
            $limitVenue->overplus_times = $data['week_times'];
        }
        $limitVenue->week_times = $data['week_times'];
        $limitVenue->venue_ids = $data['venue_ids'];
        $limitVenue->company_id = $pid;
        if (!$limitVenue->save()) {
            return $limitVenue->errors;
        }
        return true;
    }

    /**
     * 业务后台 - 会员管理 - 设置为瑜伽馆
     * @return array|bool
     */
    public static function setMemberDanceByMember()
    {
        $card = \backend\models\MemberCard::find()->alias('mc')
            ->joinWith(['member mm'])
            ->where(['like', 'mc.card_number', '0950%', false])
            ->andWhere(['mm.venue_id' => 10])
            ->asArray()->all();
        if (!empty($card)) {
            $mId = array_column($card, 'member_id');
            return Member::updateAll(['venue_id' => 13], ['id' => $mId]);
        }
        return true;
    }

    /**
     * 业务后台 - 会员管理 - 设置为瑜伽馆
     * @return array|bool
     */
    public static function setSendCard()
    {
        $card = MemberCard::findAll(['member_id' => 52219]);
        foreach ($card as $k => $value) {
            if ($k == 0) {
                continue;
            }
            $ma = new MemberAccount();
            $ma->username = '置换卡' . $k;
            $ma->password = '置换卡' . $k;
            $ma->mobile = $k;
            $ma->create_at = time();
            $ma->company_id = 1;
            $ma->save();
            $member = new Member();
            $member->username = '置换卡' . $k;
            $member->password = '置换卡' . $k;
            $member->mobile = "$k";
            $member->register_time = time();
            $member->venue_id = 2;
            $member->company_id = 1;
            $member->member_account_id = $ma->id;
            $member->save();
            if (!$member->save()) {
                return $member->errors;
            }
            $md = new MemberDetails();
            $md->member_id = $member->id;
            $md->name = '置换卡' . $k;
            $md->save();
            MemberCard::updateAll(['member_id' => $member->id], ['id' => $value['id']]);
            ConsumptionHistory::updateAll(['member_id' => $member->id], ['and', ['consumption_type' => 'card'], ['consumption_type_id' => $value['id']]]);
            MemberCourseOrder::updateAll(['member_id' => $member->id], ['member_card_id' => $value['id']]);
        }
        return true;
    }

    /**
     * 业务后台 - 会员管理 - 获取会员信息
     * @param  $keyword
     * @param  $type
     * @return array|bool
     */
    public static function getMemberDanceData($keyword = '0950%', $type)
    {
        $num = 0;
        $card = \backend\models\MemberCard::find()->alias('mc')
            ->joinWith(['member mm'])
            ->where(['like', 'mc.card_number', $keyword, false])
            ->asArray()->all();
        if ($type == 1) {
            $type = [13, 10];
        } elseif ($type == 3) {
            $type = [10];
        } else {
            $type = [13];
        }
        if (!empty($card)) {
            foreach ($card as $k => $v) {
                foreach ($type as $ven) {
                    $return = self::setVenueLimitData($v['id'], $ven);
                    if ($return == 1) {
                        $num++;
                    }
                }
            }
        }
        return $num;
    }

    /**
     * 业务后台 - 会员管理 - 获取会员信息
     * @param  $cardId //卡种
     * @param  $venueId ;
     * @return array|bool
     */
    public static function setVenueLimitData($cardId, $venueId)
    {
        $venueLimit = VenueLimitTimes::findOne(['member_card_id' => $cardId, 'venue_id' => $venueId]);
        if (!empty($venueLimit)) {
            if (!$venueLimit['total_times'] && !$venueLimit['week_times']) {
                $venueLimit->total_times = -1;
                $venueLimit->overplus_times = -1;
                $venueLimit->save();
                return 1;
            }
        } else {
            $limit = new VenueLimitTimes();
            $limit->venue_id = $venueId;
            $limit->member_card_id = $cardId;
            $limit->total_times = -1;
            $limit->overplus_times = -1;
            $limit->level = 1;
            $limit->save();
            return 1;
        }
        return 0;
    }

    /**
     * 业务后台 - 会员管理 - 批量修改无进场管权限的问题
     * @param  $number
     * @return array|bool
     */
    public static function updateMemberCardLimitData($number)
    {
        $num = 0;
        $card = \backend\models\MemberCard::find()->alias('mc')
            ->joinWith(['member mm'])
            ->where(['like', 'mc.card_number', $number . '%', false])
            ->andWhere(['mc.venue_id' => 11])
            ->asArray()->all();
//        var_dump($card);die();
        if (!empty($card)) {
            foreach ($card as $v) {
                $limit = VenueLimitTimes::findOne(['venue_id' => 11, 'member_card_id' => $v['id']]);
                if (empty($limit)) {
                    $times = new VenueLimitTimes();
                    $times->member_card_id = $v['id'];
                    $times->venue_id = 11;
                    $times->total_times = -1;
                    $times->overplus_times = -1;
                    $times->level = $number == '092' ? 1 : 2;
                    $times->save();
                    $num++;
                }
            }
        }
        return $num;
    }

    /**
     * 业务后台 - 会员管理 - 批量修改账号授权
     * @return array|bool
     */
    public static function updateAccountNumber()
    {
        $num = 0;
        $number = 1000;
        $total = 80000;
        for ($i = 0; $i < $total;) {
            $i = $i + $number;
            $member = \backend\models\Member::find()->where(['IS NOT', 'company_id', null])
                ->andWhere(['<>', 'mobile', '0'])
                ->andWhere(['or', ['IS', 'member_account_id', null], ['member_account_id' => 0]])
                ->andWhere(['not in', 'mobile', ['0', 0, '*']])
                ->offset($i)->limit($number)
                ->asArray()->all();
            if (!empty($member)) {
                foreach ($member as $v) {
                    $mc = MemberAccount::findOne(['mobile' => $v['mobile'], 'company_id' => $v['company_id']]);
                    if (empty($mc)) {
                        $ma = new MemberAccount();
                        $ma->mobile = $v['mobile'];
                        $ma->password = $v['password'];
                        $ma->username = $v['username'];
                        $ma->company_id = $v['company_id'];
                        $ma->create_at = time();
                        $ma->save();
                        $maId = $ma->id;
                    } else {
                        $maId = $mc->id;
                    }
                    if (empty($v['member_account_id']) || $v['member_account_id'] == 0) {
                        $memberObj = Member::findOne(['id' => $v['id']]);
                        $memberObj->member_account_id = $maId;
                        $memberObj->save();
                    }
                    $num++;
                }
            }
        }
        var_dump($num);
        die();

        return $num;
    }

    /**
     * 业务后台 - 会员管理 - 批量修改账号授权
     * @return array|bool
     */
    public static function updateAccountDeleteNumber()
    {
        $num = 0;
        $ma = MemberAccount::find()->asArray()->all();
        foreach ($ma as $v) {
            $mm = Member::find()->where(['member_account_id' => $v['id']])->count();
            if ($mm <= 0) {
                $memberAccount = MemberAccount::findOne(['id' => $v['id']])->delete();
                $num++;
            }
        }
        return $num;
    }

    /**
     * 业务后台 - 会员管理 - 批量修改账号授权
     * @return array|bool
     */
    public static function updateChargeTime()
    {
        $num = 0;
        $ma = MemberCourseOrder::find()->where(['>', 'deadline_time', time()])
            ->andWhere(['IS NOT', 'deadline_time', null])
            ->andWhere(['>', 'overage_section', 0])
            ->asArray()->all();
        foreach ($ma as $v) {
            if (!empty($v['deadline_time'])) {
                echo $v['member_card_id'] . '---' . $v['deadline_time'] . '---' . $num . '---' . date('Y-m-d H:i:s', intval($v['deadline_time'])) . '<br>';
                $strtotime = strtotime(date('Y-m-d', intval($v['deadline_time'])) . ' 23:59:59');
                $mco = MemberCourseOrder::findOne(['id' => $v['id']]);
                $mco->deadline_time = $strtotime;
                $mco->save();
                $num++;
            }
        }
        return $num;
    }
    /**
     * 业务后台 - 会员管理 - 批量修改账号授权
     * @return array|bool
     */
    public static function excelTrainingPeriod()
    {
        $num = 0;
        $ma = ConsumptionHistory::find()->where(['and',['category'=>'增训练期'],['consumption_type'=>'card'],['IS NOT','remarks',null]])->asArray()->all();
        if($ma){
            $member = [];
            foreach ($ma as $k=>$v){
                $mc = ConsumptionHistory::find()->where(['consumption_type_id'=>$v['consumption_type_id']])->andWhere(['or',['consumption_type'=>'card'],['consumption_type'=>'办卡消费']])->asArray()->all();
                if(count($mc) == 2) {
                    $number = 1;
                    foreach ($mc as $key=>$value) {
//                        if ($value['due_date'] && !empty($v['consumption_date'])) {
                        $md = MemberCard::findOne(['id' => $v['consumption_type_id']]);
                        if($md->card_number == '0700005886'){
                            echo '--'.self::gerDate($v['consumption_date']).'....';
                            echo '**'.$value['due_date'].'***';
                            echo self::gerDate($value['due_date'])."\n";
                        }
                            if (strtotime(self::gerDate($v['consumption_date']).' 23:59:59') == strtotime(self::gerDate($value['due_date'])." 23:59:59")) {
                                $number = 2;
                                break;
                            }
//                        }
                    }
                    if ($number == 1) {
                        $md = MemberCard::findOne(['id' => $v['consumption_type_id']]);
                        $member[$k]['number'] = $md->card_number;
                        $member[$k]['cDate'] = self::gerDate($v['consumption_date']);
                        $member[$k]['dueDate'] = self::gerDate($v['due_date']);
                    }
                }
            }
//            var_dump($member);die();
            \moonland\phpexcel\Excel::export([
                'models'   =>  $member,
                'fileName' =>  '大上海数据',
                'columns'  =>  [ 'number','cDate','dueDate'],//,'name'
                'headers'  =>  [
                    'number'       => '会员卡号',
//                    'due'       => '消费记录到期日期',
                    'cDate'       => '增训练期开始日期',
                    'dueDate'       => '增训练期结束日期',
//                        'name'       => '姓名',
                ]
            ]);
        }
        return $num;
    }
    public static function gerDate($invalid)
    {
        if(intval($invalid) >= 2145891661){
            $d = new \DateTime("@$invalid");
            $year         = $d->format('Y');
            $month        = $d->format('m');
            $day          = $d->format('d');
        }else{
            $year         = date('Y',$invalid);
            $month        = date('m',$invalid);
            $day          = date('d',$invalid);
        }
        return $year.'-'.$month.'-'.$day;
    }
    /**
     * 业务后台 - 会员管理 - 批量修改账号授权
     * @return array|bool
     */
    public static function updateYaMemberCardTime()
    {
        $num = 0;
        $member = \backend\models\Member::find()->where(['venue_id' => 15, 'member_type' => 1])->asArray()->all();
        if (!empty($member)) {
            foreach ($member as $key => $value) {
                $mc = \common\models\MemberCard::find()->where(['member_id' => $value['id']])
                    ->andWhere(['<=', 'create_at', strtotime('2018-01-31 23:59:59')])
                    ->asArray()->all();
                if (!empty($mc)) {
                    foreach ($mc as $v) {
                        $memberCard = MemberCard::findOne(['id' => $v['id']]);
                        if (!empty($memberCard->active_time) && intval($memberCard->active_time) >= 100) {
                            echo $memberCard->card_number . '---' . $memberCard->active_time . '--%-' . $num . '<br>';
                            $time = intval(strtotime('2018-01-31 23:59:59')) - intval($memberCard->active_time);
                            $memberCard->invalid_time = intval($memberCard->invalid_time) + intval($time);
                            $memberCard->active_time = strtotime('2018-01-31 23:59:59');
                        } else {
                            echo $memberCard->card_number . '---' . $memberCard->active_time . '--*-' . $num;
                            $memberCard->active_time = strtotime('2018-01-31 23:59:59');
                            $memberCard->invalid_time = strtotime('2018-01-31 23:59:59') + intval(intval($memberCard->duration) * 24 * 60 * 60);
                        }
                        if ($memberCard->status != 2 || $memberCard->status != 3) {
                            $memberCard->status = 1;
                        }
                        if (!$memberCard->save()) {
                            return $memberCard->errors;
                        }
                        $num++;
                    }
                }
            }
        }
        return $num;
    }

    /**
     * 业务后台 - 会员管理 - 设置为正式会员
     * @return array|bool
     */
    public static function setMemberTypeByCardId()
    {
        $card = \backend\models\MemberCard::find()->alias('mc')
            ->joinWith(['member mm'])
            ->where(['mm.member_type' => 2])
            ->andWhere(['or', ['<>', 'mc.usage_mode', 2], ['mc.usage_mode' => null]])
            ->asArray()->all();
//        var_dump($card);die();
        if (!empty($card)) {
            $mId = array_column($card, 'member_id');
            return Member::updateAll(['member_type' => 1], ['id' => $mId]);
        }
        return true;
    }

    /**
     * 业务后台 - 会员管理 - 修改更改场馆问题
     * @return array|bool
     */
    public static function setVenueInCompany()
    {
        Admin::updateAll(['company_id' => 1], ['venue_id' => 76]);
        Approval::updateAll(['company_id' => 1], ['venue_id' => 76]);
        ApprovalRole::updateAll(['company_id' => 1], ['venue_id' => 76]);
        ApprovalType::updateAll(['company_id' => 1], ['venue_id' => 76]);
//        Auth::updateAll(['company_id' => 1], ['venue_id' => 76]);
        \Yii::$app->db->createCommand('UPDATE {{%auth}} SET `company_id` = REPLACE(`company_id`, \'"75"\', \'"1"\')')->execute();
        BindPack::updateAll(['company_id' => 1], ['venue_id' => 76]);
        Cabinet::updateAll(['company_id' => 1], ['venue_id' => 76]);
        \common\models\base\CabinetType::updateAll(['company_id' => 1], ['venue_id' => 76]);
        CardCategory::updateAll(['company_id' => 1], ['venue_id' => 76]);
        CardCategoryType::updateAll(['company_id' => 1], ['venue_id' => 76]);
        ChargeClass::updateAll(['company_id' => 1], ['venue_id' => 76]);
        Classroom::updateAll(['company_id' => 1], ['venue_id' => 76]);
        Config::updateAll(['company_id' => 1], ['venue_id' => 76]);
        ConsumptionHistory::updateAll(['company_id' => 1], ['venue_id' => 76]);
        Course::updateAll(['company_id' => 1], ['venue_id' => 76]);
        Deal::updateAll(['company_id' => 1], ['venue_id' => 76]);
        \common\models\base\DealType::updateAll(['company_id' => 1], ['venue_id' => 76]);
        Employee::updateAll(['company_id' => 1], ['venue_id' => 76]);
        EntryRecord::updateAll(['company_id' => 1], ['venue_id' => 76]);
        GiftDay::updateAll(['company_id' => 1], ['venue_id' => 76]);
        Goods::updateAll(['company_id' => 1], ['venue_id' => 76]);
        \common\models\base\GoodsType::updateAll(['company_id' => 1], ['venue_id' => 76]);
        GroupClass::updateAll(['company_id' => 1], ['venue_id' => 76]);
        ImageManagement::updateAll(['company_id' => 1], ['venue_id' => 76]);
        ImageManagementType::updateAll(['company_id' => 1], ['venue_id' => 76]);
        Member::updateAll(['company_id' => 1], ['venue_id' => 76]);
        MemberCard::updateAll(['company_id' => 1], ['venue_id' => 76]);
        MissAboutSet::updateAll(['company_id' => 1], ['venue_id' => 76]);
        Mobilise::updateAll(['company_id' => 1], ['venue_id' => 76]);
        Order::updateAll(['company_id' => 1], ['venue_id' => 76]);
        Position::updateAll(['company_id' => 1], ['venue_id' => 76]);
        Role::updateAll(['company_id' => 1], ['company_id' => 75]);
        SeatType::updateAll(['company_id' => 1], ['venue_id' => 76]);
        Server::updateAll(['company_id' => 1], ['venue_id' => 76]);
        StoreHouse::updateAll(['company_id' => 1], ['venue_id' => 76]);
        SmsRecord::updateAll(['company_id' => 1], ['venue_id' => 76]);
        VenueLimitTimes::updateAll(['company_id' => 1], ['venue_id' => 76]);
        Organization::updateAll(['pid' => 1], ['id' => 76]);
    }

    /**
     * 业务后台 - 会员管理 - 合并会员
     * @return array|bool
     */
    public static function setMergeMember($mid, $cid)
    {
        MemberCard::updateAll(['member_id' => $cid], ['member_id' => $mid]);
        ConsumptionHistory::updateAll(['member_id' => $cid], ['member_id' => $mid]);
        MemberCourseOrder::updateAll(['member_id' => $cid], ['member_id' => $mid]);
        Order::updateAll(['member_id' => $cid], ['member_id' => $mid]);
        AboutClass::updateAll(['member_id' => $cid], ['member_id' => $mid]);
        MemberCabinet::updateAll(['member_id' => $cid], ['member_id' => $mid]);
        return true;
    }

    /**
     * 业务后台 - 会员管理 - 合并会员
     * @return array|bool
     */
    public static function setRenewMemberCard()
    {
        $num = 0;
        $order = Order::find()->where(['or', ['consumption_type' => 'cardRenew'], ['consumption_type' => 'card']])
            ->andWhere(['or', ['note' => '续费'], ['note' => '升级']])->asArray()->all();
        foreach ($order as $value) {
            $mc = MemberCard::findOne(['id' => $value['consumption_type_id']]);
            if (!empty($mc)) {
                $card = CardCategory::findOne(['id' => $mc->card_category_id]);
                if (empty($mc->bring)) {
                    $mc->bring = $card->bring;
                    $mc->card_attribute = $card->card_type;
                    $mc->save();
                }
                $limit = LimitCardNumber::find()->where(['card_category_id' => $mc->card_category_id, 'status' => [1, 3]])->asArray()->all();

                if (!empty($limit)) {
                    foreach ($limit as $v) {//["=","venue_ids",$v['venue_ids']]
                        $venueLimit = VenueLimitTimes::find()->where(['member_card_id' => $mc->id])
                            ->andWhere(['and', ['venue_id' => $v['venue_id']], ['<>', 'venue_id', 0]])->one();
                        if (empty($venueLimit)) {
                            if (!empty($v['venue_ids'])) {
                                $venueLimits = VenueLimitTimes::find()->where("JSON_CONTAINS(venue_ids,'{$v['venue_ids']}')")->asArray()->all();
                                if (!empty($venueLimits)) {
//                                    var_dump($venueLimit);die();
                                    foreach ($venueLimits as $venue) {

                                        if ($mc->id == $venue['member_card_id']) {
                                            $venueLimit = VenueLimitTimes::findOne(['id' => $venue['id']]);
                                            break;
                                        }
                                    }
                                }
                            }
                        }

//                        var_dump($venueLimit);
                        if (!empty($venueLimit)) {
                            $venueLimit->level = $v['level'];
                            $venueLimit->save();
                        }
                    }
                    $num++;
                }
            }
        }
        return $num;
    }

    /**
     * 业务后台 - 会员管理 - 批量修改升级无金额信息
     * @return array|bool
     */
    public static function getMemberCardByPrice()
    {
        $card = MemberCard::find()->alias('mc')->where(['IS', 'amount_money', null])->asArray()->all();
        if (!empty($card)) {
            foreach ($card as $k => $v) {
                $history = ConsumptionHistory::find()->where(['and', ['IS NOT', 'consumption_amount', null], ['consumption_type_id' => $v['id']], ['consumption_type' => 'card']])->one();
                if ($history) {
                    $mc = MemberCard::findOne(['id' => $v['id']]);
                    $mc->amount_money = $history->consumption_amount;
                    $mc->save();
                }
            }
        }
    }

    /**
     * 业务后台 - 会员管理 - 批量删除会员卡消费记录
     * @return array|bool
     */
    public static function delMemberCardHistoryRecord()
    {
        $number = 0;
        $card = \backend\models\MemberCard::find()->alias('mc')
            ->select('mc.id,mc.card_number,count(ch.id) as cid')
            ->joinWith(['consumptionHistory ch'])
            ->where(['ch.consumption_type' => 'card'])
            ->having(['>=', 'cid', 2])->groupBy('ch.consumption_type_id')->asArray()->all();
        if (!empty($card)) {
            foreach ($card as $k => $v) {
                $history = ConsumptionHistory::find()->where(['consumption_type_id' => $v['id']])->andWhere(['or', ['consumption_type' => '办卡消费'], ['consumption_type' => 'card']])->asArray()->all();
                if ($history) {
                    $num = count($history);
                    foreach ($history as $val) {
                        if ((empty($val['consumption_amount']) || empty($val['category'])) && $num >= 2) {
                            $number++;
                            ConsumptionHistory::findOne(['id' => $val['id']])->delete();
                            $num--;
                        }
                    }
                }
            }
        }
        return $number;
    }

    /**
     * 业务后台 - 会员管理 - 批量修改无类型会员卡
     * @return array|bool
     */
    public static function updateMemberCardType()
    {
        $number = 0;
        $cardArr = \backend\models\MemberCard::find()->alias('mc')
            ->where(['and', ['IS', 'card_type', null], ['IS NOT', 'card_category_id', null]])->asArray()->all();
        if (!empty($cardArr)) {
            foreach ($cardArr as $k => $v) {
                $number++;
                $card = CardCategory::findOne(['id' => $v['card_category_id']]);
                $memberCard = MemberCard::findOne(['id' => $v['id']]);
                if (!empty($card)) {
                    $memberCard->card_type = $card->category_type_id;
                    $memberCard->save();
                }
            }
        }
        return $number;
    }

    /**
     * 业务后台 - 会员管理 - 提醒迈步会员开业
     * @return array|bool
     */
    public function sendMaiBuMemberCode($vId, $code)
    {
        $number = 0;
        if ($code == 'uiqq22') {
            $str = '';
        } else {
            $str = '';
        }

        $data = \backend\models\Member::find()->alias('member')->joinWith(['memberCard mc'])
            ->where(['member.venue_id' => $vId, 'member_type' => 1])
            ->andWhere(['not in', 'mobile', [0, '0', '*']])
            ->andWhere(['>', 'invalid_time', time()])
            ->groupBy(['mc.member_id'])
            ->asArray()->all();
        echo count($data) . '<br>';
        if (isset($data) && !empty($data)) {
            foreach ($data as $k => $v) {
                $sms = SmsRecord::find()->where(['mobile' => $v['mobile'], 'send_code' => $code])->asArray()->one();
                if (empty($sms)) {
//                    Func::comeMemberSendCode($v['mobile'],$str,$code);
                }
                $number++;
            }
//            Func::comeMemberSendCode('18503835886',$str,$code);
            return $number;
        }
        return $number;
    }

    /**
     * 业务后台 - 导入升级后卡属性修改 - 修改会员卡详情数据
     * @create 2018/3/8
     * @param  $memberCardId
     * @param  $cardCategoryId
     * @return array|bool
     */
    public function UpdateMemberCardData($memberCardId,$cardCategoryId)
    {
        $memberCard   = MemberCard::findOne(['id' => $memberCardId]);
        $cardCategory = CardCategory::findOne(['id' => $cardCategoryId]);
        if (!empty($memberCard) && !empty($cardCategory)) {
            $time         = json_decode($cardCategory->duration, true);                 //卡种有效期
            $leave        = json_decode($cardCategory->leave_long_limit, true);         //卡种每次请假天数、请假次数
            $studentLeave = json_decode($cardCategory->student_leave_limit, true);      //学生暑寒假请假天数
            $renew        = json_decode($cardCategory->validity_renewal, true);         //卡种有效期续费
            $memberCard->card_category_id    = $cardCategoryId;
//            $memberCard->duration            = $time['day'];
            $memberCard->total_times         = $cardCategory->times;                   //总次数(次卡)
//            $memberCard->card_name           = $cardCategory->card_name;              //卡名
//            $memberCard->another_name        = $cardCategory->another_name;          //另一个卡名
            $memberCard->card_type           = $cardCategory->category_type_id;      //卡类别
            $memberCard->count_method        = $cardCategory->count_method;          //计次方式
            $memberCard->attributes          = $cardCategory->attributes;             //属性
            $memberCard->active_limit_time   = $cardCategory->active_time;            //激活期限
            $memberCard->transfer_num        = $cardCategory->transfer_number;       //转让次数
            $memberCard->surplus             = $cardCategory->transfer_number;       //剩余转让次数
            $memberCard->transfer_price      = $cardCategory->transfer_price;        //转让金额
            $memberCard->recharge_price      = $cardCategory->recharge_price;        //充值卡充值金额
            $memberCard->present_money       = $cardCategory->recharge_give_price;  //买赠金额
            $memberCard->renew_price         = $cardCategory->renew_price;           //续费价
            $memberCard->renew_best_price    = $cardCategory->offer_price;          //续费优惠价
            $memberCard->renew_unit          = $cardCategory->renew_unit;            //续费多长时间/天
            $memberCard->leave_total_days    = $cardCategory->leave_total_days;     //请假总天数
            $memberCard->leave_least_days    = $cardCategory->leave_least_Days;     //每次请假最少天数
            $memberCard->leave_days_times    = json_encode($leave);                   //每次请假天数、请假次数
            $memberCard->student_leave_limit = json_encode($studentLeave);            //学生寒暑假请假天数 次数默认1
            $memberCard->deal_id             = $cardCategory->deal_id;               //合同id
            $memberCard->bring               = $cardCategory->bring;
            $memberCard->ordinary_renewal    = $cardCategory->ordinary_renewal;
            $memberCard->validity_renewal    = json_encode($renew);
            $memberCard->pic                 = $cardCategory->pic;
            $memberCard->type                 = $cardCategory->card_type;
            $memberCard = $memberCard->save() ? $memberCard : $memberCard->errors;
            if ($memberCard->save() != true) {
                return $memberCard->errors;
            }else{
                $limit = $this->saveVenueLimit($memberCard,$cardCategory);
                if($limit !== true){
                    return "进馆次数修改失败!";
                }else{
                    $cardTime = $this->saveCardTime($memberCard,$cardCategory);
                    if($cardTime !== true){
                        return "进馆时间修改失败!";
                    }else{
                        $bindMemberCard = $this->saveBindCard($memberCard,$cardCategory);
                        if($bindMemberCard !== true){
                            return "会员卡套餐修改失败!";
                        }
                    }
                }
            }
            return true;
        }
    }

    /**
     * 云运动 - 导入升级后卡属性修改 - 存储进场次数核算表
     * @author huanghua<huanghua@itsports.club>
     * @create 2018/3/8
     * @return array
     */
    public function saveVenueLimit($memberCard,$cardCategory)
    {
        $limit = LimitCardNumber::find()->where(['card_category_id' => $cardCategory['id'],'status'=>[1,3]])->asArray()->all();
        $limitTimes = VenueLimitTimes::find()->where(['member_card_id' => $memberCard['id']])->asArray()->all();
        if (!empty($limitTimes)){
            VenueLimitTimes::deleteAll(['member_card_id' => $memberCard['id']]);
        }
        if(isset($limit)){
            foreach($limit as $k=>$v){
                $limitVenue = new VenueLimitTimes();
                $limitVenue->member_card_id = $memberCard->id;
                $limitVenue->venue_id       = $v['venue_id'];
                $limitVenue->total_times    = $v['times'];
                if(!empty($v['times'])){
                    $limitVenue->overplus_times = $v['times'];
                }else{
                    $limitVenue->overplus_times = $v['week_times'];
                }
                $limitVenue->week_times     = $v['week_times'];
                $limitVenue->venue_ids      = $v['venue_ids'];
                $limitVenue->company_id     = $cardCategory->company_id;
                $limitVenue->level          = $v['level'];
                $limitVenue->apply_start    = $v['apply_start'];
                $limitVenue->apply_end      = $v['apply_end'];
                $limitVenue->about_limit    = $v['about_limit'];
                if(!$limitVenue->save()){
                    return $limitVenue->errors;
                }
            }
            return true;
        }
        return true;
    }

    /**
     * 云运动 - 导入升级后卡属性修改 - 存储会员卡绑定套餐表
     * @author huanghua<huanghua@itsports.club>
     * @param $memberCard
     * @param $cardCategory
     * @create 2018/3/8
     * @return array
     */
    public function saveBindCard($memberCard,$cardCategory)
    {
        $bindData = BindPack::find()->where(['card_category_id' => $cardCategory['id']])->asArray()->all();
        $bindMemberCard = BindMemberCard::find()->where(['member_card_id' => $memberCard['id']])->asArray()->all();
        if (!empty($bindMemberCard)){
            BindMemberCard::deleteAll(['member_card_id' => $memberCard['id']]);
        }
        if(isset($bindData)){
            foreach($bindData as $k=>$v){
                $memberBindCard = new BindMemberCard();
                $memberBindCard->member_card_id    = $memberCard->id;
                $memberBindCard->venue_id          = $v['venue_id'];
                $memberBindCard->company_id        = $v['company_id'];
                $memberBindCard->polymorphic_id    = $v['polymorphic_id'];
                $memberBindCard->polymorphic_type  = $v['polymorphic_type'];
                $memberBindCard->number            = $v['number'];
                $memberBindCard->status            = $v['status'];
                $memberBindCard->polymorphic_ids   = $v['polymorphic_ids'];
                if(!$memberBindCard->save()){
                    return $memberBindCard->errors;
                }
            }
            return true;
        }
        return true;
    }

    /**
     * 云运动 - 导入升级后卡属性修改 - 存储会员卡时间表
     * @author huanghua<huanghua@itsports.club>
     * @create 2018/3/8
     * @param $memberCard
     * @param $cardCategory
     * @return array
     */
    public function saveCardTime($memberCard,$cardCategory)
    {
        $time = CardTime::findOne(['card_category_id' => $cardCategory['id']]);
        $cardTimes = MemberCardTime::find()->where(['member_card_id' => $memberCard['id']])->asArray()->all();
        if (!empty($cardTimes)){
            MemberCardTime::deleteAll(['member_card_id' => $memberCard['id']]);
        }
        if(!empty($time)) {
            $cardTime = new MemberCardTime();
            $cardTime->member_card_id = $memberCard->id;
            $cardTime->start = $time->start;
            $cardTime->end = $time->end;
            $cardTime->create_at = time();
            $cardTime->day = $time->day;
            $cardTime->week = $time->week;
            $cardTime->month = $time->month;
            $cardTime->quarter = $time->quarter;
            $cardTime->year = $time->year;
            if ($cardTime->save()) {
                return true;
            } else {
                return $cardTime->errors;
            }
        }else{
            return true;
        }
    }
}
