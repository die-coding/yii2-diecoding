<?php
/**
 * @Author            : Die Coding <www.diecoding.com>
 * @Date              : 19 February 2018
 * @Email             : diecoding@gmail.com
 * @Last modified by  : Die Coding <www.diecoding.com>
 * @Last modified time: 21 February 2018
 * @License           : MIT
 * @Copyright         : 2018
 */


namespace diecoding\rbac\models\form;

use Yii;
use yii\base\Model;
use diecoding\rbac\components\Access;
use diecoding\rbac\models\User;

/**
* Login
*/
class Login extends Model
{
  const LOGIN_EXPIRE = 3600*24*1;

  public $username;
  public $password;
  public $captcha    = YII_ENV_DEV ? 'dev' : null;
  public $rememberMe = false;

  private $_user;


  /**
  * @inheritdoc
  */
  public function rules()
  {
    return [
      // username and password are both required
      [['username', 'password'], 'required'],
      // rememberMe must be a boolean value
      ['rememberMe', 'boolean'],
      // username is exist by existUser()
      ['username', 'existUser'],
      // password is validated by validatePassword()
      ['password', 'validatePassword'],
      // captcha
      ['captcha', 'captcha'],
    ];
  }

  /**
  * Check exist username.
  *
  * @param string $attribute the attribute currently being validated
  * @param array $params the additional name-value pairs given in the rule
  */
  public function existUser($attribute, $params)
  {
    if (!$this->hasErrors()) {
      $user = $this->getUser();
      if (!$user) {
        $this->addError($attribute, Yii::t('diecoding', 'Incorrect username or email.'));
      }
    }
  }

  /**
  * Validates the password.
  * This method serves as the inline validation for password.
  *
  * @param string $attribute the attribute currently being validated
  * @param array $params the additional name-value pairs given in the rule
  */
  public function validatePassword($attribute, $params)
  {
    if (!$this->hasErrors()) {
      $user = $this->getUser();
      if (!$user->validatePassword($this->password)) {
        $this->addError($attribute, Yii::t('diecoding', 'Incorrect password.'));
      }
    }
  }

  /**
  * Logs in a user using the provided username and password.
  *
  * @return bool whether the user is logged in successfully
  */
  public function login()
  {
    if ($this->validate()) {
      $user = $this->getUser();
      $user->setLastLogin();
      $user->save();

      $expire = isset(Yii::$app->params['user.loginExpire']) ? Yii::$app->params['user.loginExpire'] : self::LOGIN_EXPIRE;
      $_login = Yii::$app->user->login($user, $this->rememberMe ? $expire);

      if ($_login) {
        Access::$assign;
      }

      return $_login;
    }

    return false;
  }

  /**
  * Finds user by [[username]]
  * Finds user by [[email]]
  *
  * @return User|null
  */
  protected function getUser()
  {
    if ($this->_user === null) {
      $this->_user = User::findByUsername($this->username);
    }

    if ($this->_user === null) {
      $this->_user = User::findByEmail($this->username);
    }

    return $this->_user;
  }
}
