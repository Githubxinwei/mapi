<?php
namespace backend\models;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use Yii;
use common\models\relations\AdminRelations;

class Admin extends \common\models\base\Admin implements IdentityInterface
{
    use AdminRelations;
    const STATUS_ACTIVE    = 20;
    const DAYTIME = 3600;//一天的时间

    /**
     * 后台 - 登录 - 数据验证判断
     * @auther 侯凯新
     * create 2017-3-30
     */
    public static function tableName()
    {
        return '{{%admin}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password_hash'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['password_reset_token'], 'unique'],
            [['email'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID自增',
            'username' => '用户名',
            'auth_key' => 'Auth Key',
            'password_hash' => '密码Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => '邮箱',
            'status' => '状态10审核20通过',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
//        $cache = Yii::$app->cache;
//        $user = $cache->get('cache_find_identity');
//        if ($user === false) {
//            $user =  static::find()->where(['id' => $id, 'status' => self::STATUS_ACTIVE])->asArray()->one();
//            $cache->set('cache_find_identity', $user, self::DAYTIME);
//        }
//
//        $result =  $cache->get('cache_find_result');
//        if ($result === false) {
//            $result = new static($user);
//            $cache->set('cache_find_result', $result, self::DAYTIME);
//        }

        $user =  static::find()->where(['id' => $id, 'status' => self::STATUS_ACTIVE])->asArray()->one();
        return new static($user);
    }
    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
    /**
     * Validates password
     * 云运动 - 管理员 - 获取管理员详细信息
     * @return bool if password provided is valid for current user
     */
    public static function getAdminAndEmployeeOne()
    {
//        $cache = Yii::$app->cache;
//        $result = $cache->get('cache_get_admin_and_employee_one');
//        if ($result === false) {
//            $adminId  = Yii::$app->user->identity->id; //获取用户id
//            $result = Employee::find()->where(['admin_user_id'=>$adminId])->asArray()->one(); //查询员工详细信息
//            $cache->set('cache_get_admin_and_employee_one', $result,self::DAYTIME);
//        }

        $adminId  = Yii::$app->user->identity->id; //获取用户id
        $result = Employee::find()->where(['admin_user_id'=>$adminId])->asArray()->one(); //查询员工详细信息
        return $result;
    }
    /**
     * Validates password
     * 云运动 - 管理员 - 显示名字
     * @return bool if password provided is valid for current user
     */
    public static function setAdminByEmployee()
    {
        $employee = self::getAdminAndEmployeeOne();
        if(!empty($employee)){
            return $employee['name'];
        }
        return Yii::$app->user->identity->username;
    }
    /**
     * Validates password
     * 云运动 - 管理员 - 获取管理员信息
     * @return bool if password provided is valid for current user
     */
    public static function getEmployeeAdmin($id)
    {
        if(empty($id)){
            return false;
        }
        $admin =self::find()->where(['id'=>$id])->asArray()->one();
        return $admin;
    }
}
