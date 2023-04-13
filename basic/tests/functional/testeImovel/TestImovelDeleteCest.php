<?php
class TestImovelDeleteCest {
    // Delete
    public function ImovelDelete(FunctionalTester $I)
    {
        $I->expectTo('Verificar se a deleção de informações de um imóvel está correta.');
        $model = $I->grabRecord('app\models\Imovel', array('nome' => 'Casa Grande'));
        $I->amOnRoute('imovel/delete', ['id' => $model->id]);
        $I->dontSeeRecord('app\models\Imovel', [
            'id' => $model->id,
            'nome' => $model->nome,
        ]);
    }
}
