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


namespace diecoding\rbac\components;

use yii\rbac\Rule;

/**
 * Description of GuestRule
 *
 * @author Die Coding <diecoding@gmail.com>
 * @since 0.0.0
 */
class GuestRule extends Rule
{
  /**
   * @inheritdoc
   */
  public $name = 'GuestRule';

  /**
   * @inheritdoc
   */
  public function execute($user, $item, $params)
  {
    return $user->getIsGuest();
  }
}
