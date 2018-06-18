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


use yii\db\Migration;
use diecoding\rbac\components\Option;

/**
* Class m180219_115842_user
*
* @author Die Coding <diecoding@gmail.com>
* @since 0.0.0
*/
class m180219_115842_user extends Migration
{
  /**
   * @inheritdoc
   */
  public function up()
  {
    $tableOptions = null;
    if ($this->db->driverName === 'mysql') {
      // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
      $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
    }

    $userTable = Option::instance()->userTable;
    $userDb    = Option::userDb();

    // Check if the table exists
    if ($userDb->schema->getTableSchema($userTable, true) === null) {
      $this->createTable($userTable, [
        'id'                   => $this->primaryKey(),
        'username'             => $this->string(32)->notNull()->unique(),
        'display_name'         => $this->string(32)->notNull(),
        'auth_key'             => $this->string(32)->notNull(),
        'password_hash'        => $this->string()->notNull(),
        'password_reset_token' => $this->string()->unique(),
        'email'                => $this->string()->notNull()->unique(),

        'status'               => $this->smallInteger()->notNull()->defaultValue(10),
        'created_at'           => $this->integer()->notNull(),
        'updated_at'           => $this->integer()->notNull(),
        'last_login'           => $this->text(),
        'photo'                => $this->string(),
      ], $tableOptions);
    }
  }

  /**
   * @inheritdoc
   */
  public function down()
  {
    $userTable = Option::instance()->userTable;
    $userDb    = Option::userDb();
    if ($userDb->schema->getTableSchema($userTable, true) !== null) {
      $this->dropTable($userTable);
    }
  }
}
