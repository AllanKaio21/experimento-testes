<?php

use yii\db\Migration;

/**
 * Class m190521_011445_pessoa
 */
class m190521_011445_pessoa extends Migration
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
        echo "Creating table Pessoa. \n";

        $this->createTable('pessoa', [
            'id' => $this->primaryKey(),
            'nome' => $this->string()->notNull(),
            'cpf' => $this->bigInteger()->notNull(),
            'cep'=>$this->string(),
            'rua'=>$this->string(),
            'cidade_id'=>$this->integer(),
            'estado_id'=>$this->integer(),
            'profissao'=>$this->string(),
            'complemento'=>$this->text(),
        ], $tableOptions);

        $this->addForeignKey('pessoa_cidade', 'pessoa', 'cidade_id', 'cidade', 'id');
        $this->addForeignKey('pessoa_estado', 'pessoa', 'estado_id', 'estado', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "Deleting table Pessoa. \n";
        $this->dropTable('pessoa');
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
