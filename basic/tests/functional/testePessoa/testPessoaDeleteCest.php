<?php
class PessoaDeleteCest
{
    // Delete
    public function pessoaDelete(FunctionalTester $I)
    {
        $I->expectTo('Verificar se a deleção de informações de uma pessoa está correta.');
        $model = $I->grabRecord('app\models\Pessoa', array('nome' => 'João Pedro'));
        $I->amOnRoute('pessoa/delete', ['id' => $model->id]);
        $I->dontSeeRecord('app\models\Pessoa', [
            'id' => $model->id,
            'nome' => 'João Pedro',
        ]);
    }
}
