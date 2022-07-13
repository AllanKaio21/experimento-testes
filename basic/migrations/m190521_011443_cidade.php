<?php

use yii\db\Migration;

/**
 * Class m190521_011443_cidade
 */
class m190521_011443_cidade extends Migration
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
        echo "Creating table Cidade. \n";

        $this->createTable('cidade', [
            'id' => $this->primaryKey(),
            'nome' => $this->text()->notNull(),
            'uf' => $this->string(2),
            'estado_id' => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey('cidade_estado', 'cidade', 'estado_id', 'estado', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "Deleting table Cidade. \n";
        $this->dropTable('cidade');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190521_011443_cidade cannot be reverted.\n";

        return false;
    }
    */
}
