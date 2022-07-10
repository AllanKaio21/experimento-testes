<?php

use yii\db\Migration;

/**
 * Class m190521_011435_estado
 */
class m190521_011435_estado extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        echo "Creating table Estado. \n";

        $this->createTable('estado', [
            'id' => $this->primaryKey(),
            'nome' => $this->text()->notNull(),
            'uf' => $this->integer(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "Deleting table Estado. \n";
        $this->dropTable('estado');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190521_011435_estado cannot be reverted.\n";

        return false;
    }
    */
}
