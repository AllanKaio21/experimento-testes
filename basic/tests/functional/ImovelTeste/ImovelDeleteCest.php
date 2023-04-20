<?php
class ImovelDeleteCest
{
    // Delete
    public function ImovelDelete(FunctionalTester $I)
    {
        $I->wantTo(' Verificar se a deleção de informações do imovel está correta.');
        $model = $I->grabRecord('app\models\Imovel', array('nome' => 'Casa Grande'));
        $id = $model->id;
        $I->amOnRoute('/imovel/delete', ['id' => $id]);
        $I->dontSeeRecord('app\models\Imovel', [
            'id' => $id,
            'nome' => $model->nome,
        ]);
    }
}
