<?php
/**
 * @Author            : Die Coding <www.diecoding.com>
 * @Date              : 19 February 2018
 * @Email             : diecoding@gmail.com
 * @Last modified by  : Die Coding <www.diecoding.com>
 * @Last modified time: 11 March 2018
 * @License           : MIT
 * @Copyright         : 2018
 */


namespace diecoding\rbac\controllers;

use Yii;
use diecoding\rbac\components\ItemController;
use yii\rbac\Item;

/**
 * RoleController implements the CRUD actions for AuthItem model.
 *
 * @author Die Coding <diecoding@gmail.com>
 * @since 0.0.0
 */
class RoleController extends ItemController
{
  /**
   * @inheritdoc
   */
  public function labels()
  {
    return[
      'Item'  => Yii::t('diecoding', 'Role'),
      'Items' => Yii::t('diecoding', 'Roles'),
    ];
  }

  /**
   * @inheritdoc
   */
  public function getType()
  {
    return Item::TYPE_ROLE;
  }
}
