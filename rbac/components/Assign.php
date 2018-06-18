<?php
/**
 * @link http://www.diecoding.com/
 * @author Die Coding (Sugeng Sulistiyawan) <diecoding@gmail.com>
 * @copyright Copyright (c) 2018
 */

namespace diecoding\rbac\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\web\Session;

use common\models\Peserta;

/**
 * @author Die Coding <diecoding@gmail.com>
 * @since 0.0.0
 */
class Assign extends Component
{
    public static $sessionGuestValue = "GUEST";
    public static $sessionAssignName = "__assign";


    public function isGuest()
    {
      if (Yii::$app->user->isGuest) {
          return false;
      }

      $__assign  = Yii::$app->session->get(self::$sessionAssignName);

      return self::$sessionGuestValue === $__assign;
    }

    public function isPeserta()
    {
      if (Yii::$app->user->isGuest) {
          return false;
      }

      $model  = Peserta::findOne(['id_user' => Yii::$app->user->id]);

      if ($model) {
        return $model->status === $model::DITERIMA;
      }

      return false;
    }

    public function isAssign($assign)
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }

        $__assign  = Yii::$app->session->get(self::$sessionAssignName);

        return $assign === $__assign;
    }

    public static function getAssign()
    {
        return Yii::$app->user->isGuest ? null : Yii::$app->session->get(self::$sessionAssignName);
    }

    public function isUser($item_name)
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }

        $assign = array_keys($this->getAllAssign());

        return in_array($item_name, $assign);
    }

    public static function isAdmin()
    {
        return !empty($this->getAllAssign());
    }

    public function getAllAssign()
    {
        if (Yii::$app->user->isGuest) {
            return null;
        }

        $rbac   = new DbManager();
        $assign = $rbac->getAssignments(Yii::$app->user->id);
        $value  = array_keys($assign);

        return $value;
    }

    public function getListAssign()
    {
      $authManager  = Option::authManager();
      return ArrayHelper::map($authManager->getRoles(), "name", "name");
    }

    public function print($user_id, $inline = false)
    {
        $rbac    = new DbManager();
        $assign  = $rbac->getAssignments($user_id);

        if (empty($assign)) {
            return null;
        }

        $value   = array_keys($assign);
        $implode = $inline ? ", " : "\n";

        return implode($implode, $value);
    }

    public function setAssign($value = null)
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }

        $assign = $this->getAllAssign();

        if (in_array($value, $assign) && $value) {
            $value = $value;
        } elseif (!empty($assign)) {
            $value = $assign[0];
        } else {
            $value = self::$sessionGuestValue;
        }

        $session = new Session();
        $session->open();
        $session->set(self::$sessionAssignName, $value);
        $session->close();

        return true;
    }
}
