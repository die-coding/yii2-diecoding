<?php
/**
* @link http://www.diecoding.com/
* @author Die Coding (Sugeng Sulistiyawan) <diecoding@gmail.com>
* @copyright Copyright (c) 2018
*/

namespace diecoding\rbac\components;

use Yii;
use yii\caching\TagDependency;
use diecoding\rbac\models\Menu;

/**
* MenuHelper used to generate menu depend of user role.
* Usage
*
* ```
* use diecoding\rbac\components\MenuHelper;
* use yii\bootstrap\Nav;
*
* echo Nav::widget([
*    'items' => MenuHelper::getList()
* ]);
* ```
*
* To reformat returned, provide callback to method.
*
* ```
* $callback = function ($menu) {
*    $data = eval($menu['data']);
*    return [
*        'label' => $menu['name'],
*        'url' => [$menu['route']],
*        'icon' => $menu['icon'],
*        'visible' => [$menu['visible']],
*        'options' => $data,
*        'items' => $menu['children']
*        ]
*    ]
* }
*
* $items = MenuHelper::getAssignedMenu(Yii::$app->user->id, null, $callback);
* ```
*
* @author Die Coding <diecoding@gmail.com>
* @since 0.0.0
*/
class MenuHelper
{

  /**
  * Use to get assigned menu of user.
  *
  * @param integer $root
  * @param \Closure $callback use to reformat output.
  * callback should have format like
  *
  * ```
  * function ($menu) {
  *    return [
  *        'label' => $menu['name'],
  *        'url' => [$menu['route']],
  *        'options' => $data,
  *        'items' => $menu['children']
  *        ]
  *    ]
  * }
  * ```
  * @param boolean  $refresh
  * @return array
  */
  public static function getList($userId = null, $root = null, $callback = null, $refresh = true)
  {
    $config = Option::instance();
    $userId = $userId ? : (Yii::$app->user->isGuest ? $userId : Yii::$app->user->id);

    $menus   = Menu::find()->asArray()->indexBy('id')->all();
    $key     = [__METHOD__, $userId, $root];
    $cache   = $config->cache;

    if ($refresh || $callback !== null || $cache === null || (($result = $cache->get($key)) === false)) {
      $result = static::normalizeMenu($menus, $callback, $root);
      if ($cache !== null && $callback === null) {
        $cache->set($key, $result, $config->cacheDuration, new TagDependency([
          'tags' => Option::CACHE_TAG
        ]));
      }
    }

    return $result;
  }

  /**
  * Parse route
  * @param  string $route
  * @return mixed
  */
  public static function parseRoute($route)
  {

    if (!empty($route)) {

      return [$route];

      // $url = [];
      // $r = explode('&', $route);
      // $url[0] = $r[0];
      // unset($r[0]);
      // foreach ($r as $part) {
      //   $part = explode('=', $part);
      //   $url[$part[0]] = isset($part[1]) ? $part[1] : '';
      // }
      //
      // return $url;
    }

    return '#';
  }

  /**
  * Parse route
  * @param  string $visible
  * @return mixed
  */
  public static function parseVisible($visible, $assign)
  {
    if ($visible == 1) {
      if ($assign === Menu::ASSIGN_ALL) {
        return true;
      } elseif ($assign === Menu::ASSIGN_BACKEND) {
        return Yii::$app->option->isBackend;
      } elseif ($assign === Menu::ASSIGN_FRONTEND) {
        return Yii::$app->option->isFrontend;
      } elseif ($assign === Menu::ASSIGN_GUEST) {
        return Yii::$app->user->isGuest;
      } elseif ($assign === Menu::ASSIGN_LOGIN) {
        return !Yii::$app->user->isGuest;
      }

      return Assign::getAssign() === $assign;
    }

    return false;
  }

  /**
  * Normalize menu
  * @param  array $menus
  * @param  Closure $callback
  * @param  integer $parent
  * @return array
  */
  private static function normalizeMenu(&$menus, $callback, $parent = null)
  {
    $result = [];
    $order  = [];
    foreach ($menus as $id => $menu) {
      if ($menu['parent'] == $parent) {
        $menu['children'] = static::normalizeMenu($menus, $callback, $id);
        if ($callback !== null) {
          $item = call_user_func($callback, $menu);
        } else {
          $item = [
            'label'   => $menu['name'],
            'url'     => static::parseRoute($menu['route']),
            'icon'    => $menu['icon'],
            'visible' => static::parseVisible($menu['visible'], $menu['assign']),
          ];
          if ($menu['children'] != []) {
            $item['items'] = $menu['children'];
          }
        }
        $result[] = $item;
        $order[]  = $menu['order'];
      }
    }
    if ($result != []) {
      array_multisort($order, $result);
    }

    return $result;
  }
}
