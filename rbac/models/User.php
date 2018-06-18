<?php
/**
 * @Author            : Die Coding <www.diecoding.com>
 * @Date              : 19 February 2018
 * @Email             : diecoding@gmail.com
 * @Last modified by  : Die Coding <www.diecoding.com>
 * @Last modified time: 20 February 2018
 * @License           : MIT
 * @Copyright         : 2018
 */


namespace diecoding\rbac\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Json;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use diecoding\rbac\components\Option;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $last_login
 * @property string $display_name
 * @property string $photo
 * @property string $password write-only password
 *
 * @property UserProfile $profile
 */
class User extends ActiveRecord implements IdentityInterface
{
  const STATUS_INACTIVE = 0;
  const STATUS_ACTIVE   = 10;
  const TOKEN_EXPIRE    = 3600*24*1;

  /**
   * @inheritdoc
   */
  public static function tableName()
  {
    return Option::instance()->userTable;
  }

  /**
   * @inheritdoc
   */
  public static function getDb()
  {
    return Option::userDb();
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
      ['status', 'default', 'value' => self::STATUS_ACTIVE],
      ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
    ];
  }

  /**
   * @inheritdoc
   */
  public function attributeLabels()
  {
    return [
      'id'                   => Yii::t('diecoding', 'ID'),
      'username'             => Yii::t('diecoding', 'Username'),
      'auth_key'             => Yii::t('diecoding', 'Auth Key'),
      'password_hash'        => Yii::t('diecoding', 'Password Hash'),
      'password_reset_token' => Yii::t('diecoding', 'Password Reset Token'),
      'email'                => Yii::t('diecoding', 'Email'),
      'status'               => Yii::t('diecoding', 'Status'),
      'created_at'           => Yii::t('diecoding', 'Created At'),
      'updated_at'           => Yii::t('diecoding', 'Updated At'),
      'last_login'           => Yii::t('diecoding', 'Last Login'),
      'display_name'         => Yii::t('diecoding', 'Display Name'),
      'photo'                => Yii::t('diecoding', 'Photo'),
    ];
  }

  /**
   * @inheritdoc
   */
  public static function findIdentity($id)
  {
    return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
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
  * Finds user by email
  *
  * @param string $email
  * @return static|null
  */
  public static function findByEmail($email)
  {
    return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
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
      'status'               => self::STATUS_ACTIVE,
    ]);
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

    $timestamp = (int) substr($token, strrpos($token, '_') + 1);
    $expire    = isset(Yii::$app->params['user.passwordResetTokenExpire']) ? Yii::$app->params['user.passwordResetTokenExpire'] : self::TOKEN_EXPIRE;
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

  /**
  * Generate Last Login
  */
  public function setLastLogin()
  {
    $request = Yii::$app->request;

    $write = [
      'user-agent'  => $request->userAgent,
      'ip'          => $request->userIp,
      'url'         => $request->url,
      'referrer'    => $request->referrer,
      'date'        => date('r'),
    ];

    $this->last_login = Json::encode($write)."\n";
  }

  /**
  * Generate Decode Last Login
  */
  public function decodeLastLogin()
  {
    $array  = Json::decode($this->last_login);

    $decode = "USER AGENT: {$array['user-agent']}
      IP: {$array['ip']}
      URL: {$array['url']}
      REFERRER: {$array['referrer']}
      DATE: {$array['date']}
    ";

    return $decode;
  }
}
