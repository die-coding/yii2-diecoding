<?php
# @Author: Die Coding | www.diecoding.com
# @Date:   18 October 2017
# @Email:  diecoding@gmail.com
# @Last modified by:   Die Coding | www.diecoding.com
# @Last modified time: 16-02-2018
# @License: MIT
# @Copyright: 2017



namespace diecoding\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\rbac\DbManager;
use yii\web\Session;
use yii\helpers\ArrayHelper;
use app\models\Peserta;

class Access extends Component {

  const NAME_KEY_RULE  = "RULE";
  const SESSION_ASSIGN = "__assign";
  const SESSION_GUEST  = "GUEST";

  public function isUser($item_name) {
    if (Yii::$app->user->isGuest) {
      return false;
    }

    $assign = array_keys($this->getAllAssign());

    return in_array($item_name, $assign);
  }

  public function isAssign($assign) {
    if (Yii::$app->user->isGuest) {
      return false;
    }

    $__assign  = Yii::$app->session->get(self::SESSION_ASSIGN);

    return $assign === $__assign;
  }

  public function isPeserta() {
    if (Yii::$app->user->isGuest) {
      return false;
    }

    $model = Peserta::findOne(['id_user' => Yii::$app->user->id]);

    return (bool) $model;
  }

  public function isAdmin() {

    return !empty($this->getAllAssign());
  }

  public function getAssign() {
    if (Yii::$app->user->isGuest) {
      return null;
    }

    return Yii::$app->session->get(self::SESSION_ASSIGN);
  }

  public function getAllAssign() {
    if (Yii::$app->user->isGuest) {
      return null;
    }

    $rbac   = new DbManager();
    $assign = $rbac->getAssignments(Yii::$app->user->id);
    $value  = array_keys($assign);

    return $value;
  }

  public function printAssign($id, $inline = false) {

    $rbac   = new DbManager();
    $assign = $rbac->getAssignments($id);
    $value  = array_keys($assign);

    if (!empty($value)) {
      $out = "";
      foreach ($value as $v) {
        $out .= $v;
        $out .= $inline ? "" : "\n";
      }
    } else {
      $out = null;
    }

    return $out;
  }

  public function setAssign($value = null) {
    if (Yii::$app->user->isGuest) {
      return false;
    }

    $assign = $this->getAllAssign();

    if (in_array($value, $assign) && $value) {
      $value = $value;
    } elseif (!empty($assign)) {
      $value = $assign[0];
    } else {
      $value = self::SESSION_GUEST;
    }

    $session = new Session();
    $session->open();
    $session->set(self::SESSION_ASSIGN, $value);
    $session->close();

    return true;
  }
}
