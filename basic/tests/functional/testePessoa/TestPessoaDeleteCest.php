<?php
class TestPessoaDeleteCest {
    // Delete
    public function PessoaDelete(FunctionalTester $I)
    {
        $I->expectTo('Verificar se a deleção de informações de uma pessoa está correta.');
        $model = $I->grabRecord('app\models\Pessoa', array('nome' => 'Joao Pedro'));
        $I->amOnRoute('pessoa/delete', ['id' => $model->id]);
        $I->dontSeeRecord('app\models\Pessoa', [
            'id' => $model->id,
            'nome' => $model->nome,
        ]);
    }
}
