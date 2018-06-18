<?php
/**
 * @Author            : Die Coding <www.diecoding.com>
 * @Date              : 19 February 2018
 * @Email             : diecoding@gmail.com
 * @Last modified by  : Die Coding <www.diecoding.com>
 * @Last modified time: 06 March 2018
 * @License           : MIT
 * @Copyright         : 2018
 */


namespace diecoding\rbac\components;

use Yii;
use yii\rbac\Rule;

/**
 * Route Rule for check route with extra params.
 *
 * @author Die Coding <diecoding@gmail.com>
 * @since 0.0.0
 */
class RouteRule extends Rule
{
  const RULE_NAME = 'route_rule';

  /**
   * @inheritdoc
   */
  public $name = self::RULE_NAME;

  /**
   * @inheritdoc
   */
   public function execute($user, $item, $params)
   {
     $routeParams = isset($item->data['params']) ? $item->data['params'] : [];
     foreach ($routeParams as $key => $value) {
       if (!array_key_exists($key, $params) || $params[$key] != $value) {
         return false;
       }
     }
     return true;
   }
}
