<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%task_user}}`.
 */
class m190131_054729_create_task_user_table extends Migration
{
  /**
   * {@inheritdoc}
   */
  public function safeUp()
  {
    $this->createTable('{{%task_user}}', [
      'id' => $this->primaryKey(),
      'task_id' => $this->integer()->notNull(),
      'user_id' => $this->integer()->notNull(),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function safeDown()
  {
    $this->dropTable('{{%task_user}}');
  }
}
