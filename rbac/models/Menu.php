<?php
/**
* @link http://www.diecoding.com/
* @author Die Coding (Sugeng Sulistiyawan) <diecoding@gmail.com>
* @copyright Copyright (c) 2018
*/

namespace diecoding\rbac\models;

use Yii;
use diecoding\rbac\components\Option;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
* This is the model class for table "menu".
*
* @property integer $id Menu id(autoincrement)
* @property string $name Menu name
* @property integer $parent Menu parent
* @property string $route Route for this menu
* @property string $icon
* @property string $assign
* @property integer $visible
* @property integer $order Menu order
* @property string $data Extra information for this menu
*
* @property Menu $menuParent Menu parent
* @property Menu[] $menus Menu children
*
* @author Die Coding <diecoding@gmail.com>
* @since 0.0.0
*/
class Menu extends \yii\db\ActiveRecord
{

  const ASSIGN_ALL      = '*';
  const ASSIGN_BACKEND  = 'b';
  const ASSIGN_FRONTEND = 'f';
  const ASSIGN_GUEST    = '?';
  const ASSIGN_LOGIN    = '@';
  
  public $parent_name;

  private static $_routes;

  /**
  * @inheritdoc
  */
  public static function tableName()
  {
    return Option::instance()->menuTable;
  }

  /**
  * @inheritdoc
  */
  public static function getDb()
  {
    if (Option::instance()->db !== null) {
      return Option::instance()->db;
    } else {
      return parent::getDb();
    }
  }

  /**
  * @inheritdoc
  */
  public function rules()
  {
    return [
      [['name'], 'required'],
      [['parent_name'], 'in',
      'range' => static::find()->select(['name'])->column(),
      'message' => 'Menu "{value}" not found.'],
      [['parent', 'route', 'data', 'visible', 'order', 'icon', 'assign'], 'default'],
      [['parent'], 'filterParent', 'when' => function() {
        return !$this->isNewRecord;
      }],
      [['visible', 'order'], 'integer'],
      [['route'], 'in',
      'range' => static::getSavedRoutes(),
      'message' => Yii::t('diecoding', 'Route "{value}" not found.')]
    ];
  }

  /**
  * Use to loop detected.
  */
  public function filterParent()
  {
    $parent = $this->parent;
    $db = static::getDb();
    $query = (new Query)->select(['parent'])
    ->from(static::tableName())
    ->where('[[id]]=:id');
    while ($parent) {
      if ($this->id == $parent) {
        $this->addError('parent_name', Yii::t('diecoding', 'Loop detected.'));
        return;
      }
      $parent = $query->params([':id' => $parent])->scalar($db);
    }
  }

  /**
  * @inheritdoc
  */
  public function attributeLabels()
  {
    return [
      'id'          => Yii::t('diecoding', 'ID'),
      'name'        => Yii::t('diecoding', 'Name'),
      'parent'      => Yii::t('diecoding', 'Parent'),
      'parent_name' => Yii::t('diecoding', 'Parent Name'),
      'route'       => Yii::t('diecoding', 'Route'),
      'icon'        => Yii::t('diecoding', 'Icon'),
      'assign'      => Yii::t('diecoding', 'Assign'),
      'visible'     => Yii::t('diecoding', 'Visible'),
      'order'       => Yii::t('diecoding', 'Order'),
      'data'        => Yii::t('diecoding', 'Data'),
    ];
  }

  /**
  * Get list assign
  * @return []
  */
  public static function getListAssign($arrayKey = null)
  {
    $authManager  = Option::authManager();

    $defaultRoles = [
      self::ASSIGN_ALL      => Yii::t('diecoding', 'All User'),
      self::ASSIGN_BACKEND  => Yii::t('diecoding', 'App Backend'),
      self::ASSIGN_FRONTEND => Yii::t('diecoding', 'App Frontend'),
      self::ASSIGN_GUEST    => Yii::t('diecoding', 'User Not Login'),
      self::ASSIGN_LOGIN    => Yii::t('diecoding', 'User Login'),
    ];

    $allRoles = ArrayHelper::merge($defaultRoles, ArrayHelper::map($authManager->getRoles(), "name", "name"));

    if ($arrayKey) {
      if (array_key_exists($arrayKey, $allRoles)) {
        return $allRoles[$arrayKey];
      }
      return Yii::t('diecoding', 'Role not found');
    }

    return $allRoles;
  }

  /**
  * Get menu parent
  * @return \yii\db\ActiveQuery
  */
  public function getMenuParent()
  {
    return $this->hasOne(Menu::className(), ['id' => 'parent']);
  }

  /**
  * Get menu children
  * @return \yii\db\ActiveQuery
  */
  public function getMenus()
  {
    return $this->hasMany(Menu::className(), ['parent' => 'id']);
  }

  /**
  * Get saved routes.
  * @return array
  */
  public static function getSavedRoutes()
  {
    if (self::$_routes === null) {
      self::$_routes = [];
      foreach (Option::authManager()->getPermissions() as $name => $value) {
        if ($name[0] === '/' && substr($name, -1) != '*') {
          self::$_routes[] = $name;
        }
      }
    }
    return self::$_routes;
  }

  public static function getMenuSource()
  {
    $tableName = static::tableName();
    return (new \yii\db\Query())
    ->select(['m.id', 'm.name', 'm.route', 'parent_name' => 'p.name'])
    ->from(['m' => $tableName])
    ->leftJoin(['p' => $tableName], '[[m.parent]]=[[p.id]]')
    ->all(static::getDb());
  }
}
