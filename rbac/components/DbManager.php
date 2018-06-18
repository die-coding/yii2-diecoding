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

use yii\rbac\DbManager as Db;

/**
 * DbManager represents an authorization manager that stores authorization information in database.
 *
 * The database connection is specified by [[$db]]. The database schema could be initialized by applying migration:
 *
 * ```
 * yii migrate --migrationPath=@yii/rbac/migrations/
 * ```
 *
 * If you don't want to use migration and need SQL instead, files for all databases are in migrations directory.
 *
 * You may change the names of the three tables used to store the authorization data by setting [[\yii\rbac\DbManager::$itemTable]],
 * [[\yii\rbac\DbManager::$itemChildTable]] and [[\yii\rbac\DbManager::$assignmentTable]].
 *
 * @author Die Coding <diecoding@gmail.com>
 * @since 0.0.0
 */
class DbManager extends Db
{
  /**
   * Memory cache of assignments
   * @var array
   */
  private $_assignments = [];
  private $_childrenList;

  /**
   * @inheritdoc
   */
  public function getAssignments($userId)
  {
    if (!isset($this->_assignments[$userId])) {
      $this->_assignments[$userId] = parent::getAssignments($userId);
    }

    return $this->_assignments[$userId];
  }

  /**
   * @inheritdoc
   */
  protected function getChildrenList()
  {
    if ($this->_childrenList === null) {
      $this->_childrenList = parent::getChildrenList();
    }

    return $this->_childrenList;
  }
}
