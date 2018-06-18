<?php
/**
 * @Author: Die Coding <www.diecoding.com>
 * @Date:   19 February 2018
 * @Email:  diecoding@gmail.com
 * @Last modified by:   Die Coding <www.diecoding.com>
 * @Last modified time: 14 March 2018
 * @License: MIT
 * @Copyright: 2018
 */


use yii\db\Migration;
use diecoding\rbac\components\Option;

/**
* Class m180219_115842_menu
*
* @author Die Coding <diecoding@gmail.com>
* @since 0.0.0
*/
class m180219_115842_menu extends Migration
{

  /**
   * @inheritdoc
   */
  public function up()
  {
    $menuTable    = Option::instance()->menuTable;
    $tableOptions = null;
    if ($this->db->driverName === 'mysql') {
      $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
    }

    $this->createTable($menuTable, [
      'id'       => $this->primaryKey(),
      'name'     => $this->string(128)->notNull(),
      'parent'   => $this->integer(),
      'route'    => $this->string(),
      'icon'     => $this->string(32),
      'assign'   => $this->string(64)->notNull()->defaultValue("*"),
      'visible'  => $this->smallInteger()->notNull()->defaultValue(1),
      'order'    => $this->integer(),
      'data'     => $this->binary(),
      "FOREIGN KEY ([[parent]]) REFERENCES {$menuTable}([[id]]) ON DELETE SET NULL ON UPDATE CASCADE",
    ], $tableOptions);
  }

  /**
   * @inheritdoc
   */
  public function down()
  {
    $this->dropTable(Option::instance()->menuTable);
  }
}
