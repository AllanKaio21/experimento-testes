<?php
class TesteDelete
{
    // Delete
    public function testeDelete(FunctionalTester $I)
    {
        $I->expectTo('Verify exception for Delete');
        $model = $I->grabRecord('app\models\Teste', array('nome' => 'Jorge'));
        $I->sendAjaxPostRequest(['/Teste/delete', 'id' => $model->id]);
        $I->dontSeeRecord('app\models\Teste', [
            'id' => $id,
            'nome' => $model->nome,
        ]);
    }
}
