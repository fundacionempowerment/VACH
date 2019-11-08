<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\db\Query;
use yii\web\IdentityInterface;

/**
 * Class User
 * @package app\models
 * @property integer id
 * @property string username
 * @property string email
 * @property string phone
 * @property string name
 * @property string surname
 * @property integer status
 * @property string auth_key
 * @property string password_hash
 * @property string password_reset_token
 * @property integer created_at
 * @property integer updated_at
 * @property boolean is_administrator
 * @property string notes
 */
class User extends ActiveRecord implements IdentityInterface
{
    const PASSWORD = 'password';

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    public $password;
    public $password_confirm;
    public $resetPassword;

    public function init() {
        parent::init();
        $this->is_administrator = false;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'surname', 'email', 'username'], 'required'],
            [['password', 'password_confirm'], 'required', 'on' => self::PASSWORD],
            [['name', 'surname', 'email', 'phone', 'username', 'password', 'password_confirm', 'is_administrator', 'resetPassword', 'notes'], 'safe'],
            [['notes'], 'string', 'max' => 1000],
            [['name', 'surname', 'email', 'phone', 'notes'], 'filter', 'filter' => 'trim'],
            ['username', 'unique'],
            ['email', 'email'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            ['password_confirm', 'compare', 'compareAttribute' => 'password'],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByName($name)
    {
        return self::find()
            ->where(['like', 'name', $name])
            ->orWhere(['like', 'surname', $name])
            ->orWhere(['like', 'username', $name])
            ->andWhere(['status' => self::STATUS_ACTIVE]);
    }

    public function attributeLabels()
    {
        return [
            'username' => Yii::t('user', 'Username'),
            'password' => Yii::t('user', 'Password'),
            'password_confirm' => Yii::t('user', 'Password confirmation'),
            'is_administrator' => Yii::t('user', 'Is administrator'),
            'name' => Yii::t('app', 'Name'),
            'surname' => Yii::t('user', 'Surname'),
            'email' => Yii::t('app', 'Email'),
            'fullname' => Yii::t('app', 'Name'),
            'phone' => Yii::t('app', 'Phone'),
            'resetPassword' => Yii::t('user', 'Send reset password email'),
            'notes' => Yii::t('app', 'Notes'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
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
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public function getFullname()
    {
        return $this->name . ' ' . $this->surname;
    }

    public function getUserFullname()
    {
        return $this->name . ' ' . $this->surname . ' (' . $this->username . ')';
    }

    public function beforeSave($insert)
    {
        if (!$this->authKey) {
            $this->generateAuthKey();
        }
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->afterFind();
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int)end($parts);
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
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
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

    public static function getList()
    {
        return \yii\helpers\ArrayHelper::map(static::find()->orderBy('name,surname')->all(), 'id', 'fullname');
    }

    public static function getUserList()
    {
        return \yii\helpers\ArrayHelper::map(static::find()->orderBy('name,surname')->all(), 'id', 'userfullname');
    }

    public static function getAdminEmails()
    {
        return \yii\helpers\ArrayHelper::map(static::find()->where(['is_administrator' => true])->all(), 'id', 'email');
    }

    public function getDeletable()
    {
        if (Company::find()->where(['coach_id' => $this->id])->exists()) {
            return false;
        }
        if (Person::find()->where(['coach_id' => $this->id])->exists()) {
            return false;
        }
        if (Team::find()->where(['coach_id' => $this->id])->exists()) {
            return false;
        }
        if (TeamCoach::find()->where(['coach_id' => $this->id])->exists()) {
            return false;
        }
        if (Log::find()->where(['coach_id' => $this->id])->exists()) {
            return false;
        }

        return true;
    }

    public function getStock($product_id = null) {

        if (!$product_id) {
            $product_id = Product::find()->all()[0]->id;
        }

        $query = new Query();

        $balance = $query->select(new Expression('count(id) as balance'))
            ->from('stock')
            ->where([
                'coach_id' => $this->id,
                'product_id' => $product_id,
                'status' => Stock::STATUS_VALID,
            ])
            ->one();

        if ($balance && $balance['balance']) {
            return $balance['balance'];
        }
        return 0;
    }

}
