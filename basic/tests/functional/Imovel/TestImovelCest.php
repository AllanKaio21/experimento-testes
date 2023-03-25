<?php
class TestImovelCest
{
    public function _before(FunctionalTester $I){
        // TODO: Enter a login method if needed!
    }

    //Test Template Form
    public function ImovelCreate(FunctionalTester $I)
    {
        $I->wantTo('Verify exception for create');
        $I->amOnRoute('imovel/create');
        $model[0] = $I->grabRecord('app\models\pessoa', array());
        $model[1] = $I->grabRecord('app\models\cidade', array());
        $model[2] = $I->grabRecord('app\models\estado', array());
        $I->submitForm('form',[
            'Imovel[nome]' => 'Form Tester 001',
            'Imovel[pessoa_id]' => $model[0]->id,
            // TODO: This attribute "cep" contains a custom rule "ceptester", enter it manually.
            'Imovel[cep]' => '38400718',
            'Imovel[rua]' => 'Form Tester 001',
            'Imovel[cidade_id]' => $model[1]->id,
            'Imovel[estado_id]' => $model[2]->id,
            'Imovel[complemento]' => 'Form Tester 001',
            // TODO: o "valor" field not recognized, enter manually.
            'Imovel[valor]' => '400000',
        ]);
        $I->seeRecord('app\models\Imovel', [
            'nome' => 'Form Tester 001',
        ]);
        // TODO: This attribute "cep" contains a custom rule "ceptester", enter it manually.
        $I->seeRecord('app\models\Imovel', [
            'cep' => '38400718',
        ]);
        $I->seeRecord('app\models\Imovel', [
            'rua' => 'Form Tester 001',
        ]);
        $I->seeRecord('app\models\Imovel', [
            'complemento' => 'Form Tester 001',
        ]);
        // TODO: o "valor" field not recognized, enter manually.
        $I->seeRecord('app\models\Imovel', [
            'valor' => '400000',
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
            // TODO: This attribute "cep" contains a custom rule "ceptester", enter it manually.
            'Imovel[cep]' => '38400718',
            'Imovel[rua]' => 'Form Tester 001',
            'Imovel[cidade_id]' => $model2[1]->id,
            'Imovel[estado_id]' => $model2[2]->id,
            'Imovel[complemento]' => 'Form Tester 002',
            // TODO: o "valor" field not recognized, enter manually.
            'Imovel[valor]' => '400000',
        ]);
        $I->seeRecord('app\models\imovel', [
            'nome' => 'Form Tester 002',
        ]);
        // TODO: This attribute "cep" contains a custom rule "ceptester", enter it manually.
        $I->seeRecord('app\models\imovel', [
            'cep' => '38400718',
        ]);
        $I->seeRecord('app\models\imovel', [
            'rua' => 'Form Tester 001',
        ]);
        $I->seeRecord('app\models\imovel', [
            'complemento' => 'Form Tester 002',
        ]);
        // TODO: o "valor" field not recognized, enter manually.
        $I->seeRecord('app\models\Imovel', [
            'valor' => '400000',
        ]);
    }
}
