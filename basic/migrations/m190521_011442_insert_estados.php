<?php

use yii\db\Migration;

/**
 * Class m190521_011442_insert_estados
 */
class m190521_011442_insert_estados extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert('estado', ['id', 'nome', 'uf'], [
            [1, 'AC', 'Acre'],
            [2, 'AL', 'Alagoas'],
            [3, 'AM', 'Amazonas'],
            [4, 'AP', 'Amapá'],
            [5, 'BA', 'Bahia'],
            [6, 'CE', 'Ceará'],
            [7, 'DF', 'Distrito Federal'],
            [8, 'ES', 'Espírito Santo'],
            [9, 'GO', 'Goiás'],
            [10, 'MA', 'Maranhão'],
            [11, 'MG', 'Minas Gerais'],
            [12, 'MS', 'Mato Grosso do Sul'],
            [13, 'MT', 'Mato Grosso'],
            [14, 'PA', 'Pará'],
            [15, 'PB', 'Paraíba'],
            [16, 'PE', 'Pernambuco'],
            [17, 'PI', 'Piauí'],
            [18, 'PR', 'Paraná'],
            [19, 'RJ', 'Rio de Janeiro'],
            [20, 'RN', 'Rio Grande do Norte'],
            [21, 'RO', 'Rondônia'],
            [22, 'RR', 'Roraima'],
            [23, 'RS', 'Rio Grande do Sul'],
            [24, 'SC', 'Santa Catarina'],
            [25, 'SE', 'Sergipe'],
            [26, 'SP', 'São Paulo'],
            [27, 'TO', 'Tocantins'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190521_011442_insert_estados cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190521_011442_insert_estados cannot be reverted.\n";

        return false;
    }
    */
}
