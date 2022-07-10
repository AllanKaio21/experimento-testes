<?php

use yii\db\Migration;

/**
 * Class m190521_011445_pessoa
 */
class m190521_011446_imovel extends Migration
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
        echo "Creating table Imóvel. \n";

        $this->createTable('imovel', [
            'id' => $this->primaryKey(),
            'nome' => $this->string()->notNull(),
            'pessoa_id' => $this->integer()->notNull(),
            'cep'=>$this->string(),
            'rua'=>$this->string(),
            'cidade_id'=>$this->integer(),
            'estado_id'=>$this->integer(),
            'complemento'=>$this->string(),
            'valor'=>$this->decimal(),
        ], $tableOptions);

        $this->addForeignKey('imovel_cidade', 'imovel', 'cidade_id', 'cidade', 'id');
        $this->addForeignKey('imovel_estado', 'imovel', 'estado_id', 'estado', 'id');
        $this->addForeignKey('imovel_pessoa', 'imovel', 'pessoa_id', 'pessoa', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "Deleting table Imovel. \n";
        $this->dropTable('imovel');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190521_011445_pessoa cannot be reverted.\n";

        return false;
    }
    */
}
