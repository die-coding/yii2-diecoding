<?php
/**
 * @Author: Die Coding <www.diecoding.com>
 * @Date:   19 February 2018
 * @Email:  diecoding@gmail.com
 * @Last modified by:   Die Coding <www.diecoding.com>
 * @Last modified time: 19 February 2018
 * @License: MIT
 * @Copyright: 2018
 */


namespace diecoding\rbac\models;

use Yii;
use yii\base\Object;
use diecoding\rbac\components\Option;
use diecoding\rbac\components\Helper;

/**
 * Description of Assignment
 *
 * @author Die Coding <diecoding@gmail.com>
 * @since 0.0.0
 */
class Assignment extends Object
{
  /**
   * @var integer User id
   */
  public $id;

  /**
   * @var \yii\web\IdentityInterface User
   */
  public $user;

  /**
   * @inheritdoc
   */
  public function __construct($id, $user = null, $config = [])
  {
    $this->id   = $id;
    $this->user = $user;
    parent::__construct($config);
  }

  /**
   * Grands a roles from a user.
   * @param array $items
   * @return integer number of successful grand
   */
  public function assign($items)
  {
    $authManager = Option::authManager();
    $success     = 0;
    foreach ($items as $name) {
      try {
        $item = $authManager->getRole($name);
        $item = $item ? : $authManager->getPermission($name);
        $authManager->assign($item, $this->id);
        $success++;
      } catch (\Exception $e) {
        Yii::error($e->getMessage(), __METHOD__);
      }
    }
    Helper::invalidate();

    return $success;
  }

  /**
   * Revokes a roles from a user.
   * @param array $items
   * @return integer number of successful revoke
   */
  public function revoke($items)
  {
    $authManager = Option::authManager();
    $success = 0;
    foreach ($items as $name) {
      try {
        $item = $authManager->getRole($name);
        $item = $item ?: $authManager->getPermission($name);
        $authManager->revoke($item, $this->id);
        $success++;
      } catch (\Exception $e) {
        Yii::error($e->getMessage(), __METHOD__);
      }
    }
    Helper::invalidate();

    return $success;
  }

  /**
   * Get all available and assigned roles/permission
   * @return array
   */
  public function getItems()
  {
    $authManager = Option::authManager();
    $available   = [];
    foreach (array_keys($authManager->getRoles()) as $name) {
      $available[$name] = 'role';
    }

    foreach (array_keys($authManager->getPermissions()) as $name) {
      if ($name[0] !== '/') {
        $available[$name] = 'permission';
      }
    }

    $assigned = [];
    foreach ($authManager->getAssignments($this->id) as $item) {
      $assigned[$item->roleName] = $available[$item->roleName];
      unset($available[$item->roleName]);
    }

    return [
      'available' => $available,
      'assigned'  => $assigned,
    ];
  }

  /**
   * @inheritdoc
   */
  public function __get($name)
  {
    if ($this->user) {
      return $this->user->$name;
    }
  }
}
