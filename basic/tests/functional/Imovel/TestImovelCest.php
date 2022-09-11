<?php
class TestImovelCest
{
    public function _before(FunctionalTester $I){
        // TODO: Enter a login method if needed!
    }

    //Test Template Form
    public function ImovelForm(FunctionalTester $I)
    {
        $I->wantTo('Verify exception for form');
        $I->amOnRoute('imovel/create');
        $model[0] = $I->grabRecord('app\models\pessoa', array());
        $model[1] = $I->grabRecord('app\models\cidade', array());
        $model[2] = $I->grabRecord('app\models\estado', array());
        $I->submitForm('form',[
            'Imovel[nome]' => 'Form Tester 001',
            'Imovel[pessoa_id]' => $model[0]->id,
            'Imovel[cep]' => '76360008',
            //TODO: This attribute "cep" contains a custom rule "ceptester", enter it manually.
            'Imovel[rua]' => 'Form Tester 001',
            'Imovel[cidade_id]' => $model[1]->id,
            'Imovel[estado_id]' => $model[2]->id,
            'Imovel[complemento]' => 'Form Tester 001',
            'Imovel[valor]' => '12',
            // TODO: o "valor" field not recognized, enter manually.
        ]);
        $I->seeRecord('app\models\Imovel', [
            'nome' => 'Form Tester 001',
        ]);
        $I->seeRecord('app\models\Imovel', [
            'rua' => 'Form Tester 001',
        ]);
        $I->seeRecord('app\models\Imovel', [
            'complemento' => 'Form Tester 001',
        ]);
    }

    //Test Update
    public function ImovelUpdate(FunctionalTester $I)
    {
        $I->wantTo("Verify exception for Update");
        $model = $I->grabRecord('app\models\Imovel', array('nome' => 'Form Tester 001'));
        $model2[0] = $I->grabRecord('app\models\pessoa', array());
        $model2[1] = $I->grabRecord('app\models\cidade', array());
        $model2[2] = $I->grabRecord('app\models\estado', array());
        $id = $model->id;
        $I->amOnRoute('imovel/update',['id' => $id]);
        $I->submitForm('form',[
            'Imovel[nome]' => 'Form Tester 002',
            'Imovel[pessoa_id]' => $model2[0]->id,
            'Imovel[cep]' => '76360009',
            //TODO: This attribute "cep" contains a custom rule "ceptester", enter it manually.
            'Imovel[rua]' => 'Form Tester 001',
            'Imovel[cidade_id]' => $model2[1]->id,
            'Imovel[estado_id]' => $model2[2]->id,
            'Imovel[complemento]' => 'Form Tester 002',
            'Imovel[valor]' => '12',
            // TODO: o "valor" field not recognized, enter manually.
        ]);
        $I->seeRecord('app\models\imovel', [
            'nome' => 'Form Tester 002',
        ]);
        $I->seeRecord('app\models\imovel', [
            'cep' => '76360009',
        ]);
        $I->seeRecord('app\models\imovel', [
            'rua' => 'Form Tester 001',
        ]);
        $I->seeRecord('app\models\imovel', [
            'complemento' => 'Form Tester 002',
        ]);
    }
}
