<?php
class TestImovelDeleteCest
{
    public function _before(FunctionalTester $I){
        // TODO: Enter a login method if needed!
    }

    //Test Delete
    public function ImovelDelete(FunctionalTester $I)
    {
        $I->wantTo('Verify exception for Delete');
        $model = $I->grabRecord('app\models\Imovel', array(
            'nome' => 'Form Tester 002',
            'cep' => '75465111',
            'rua' => 'Form Tester 001',
            'complemento' => 'Form Tester 002',
            'valor' => '150000'
            // TODO: Fill the string with the last value of the data in the update
        ));
        $id = $model->id;
        $I->amOnRoute('/imovel/delete', ['id' => $id]);
        $I->dontSeeRecord('app\models\Imovel', [
            'id'=> $id,
        ]);
    }
}
