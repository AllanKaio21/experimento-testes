<?php
class TestPessoaCest
{
    public function _before(FunctionalTester $I){
        // TODO: Enter a login method if needed!
    }

    //Test Template Form
    public function PessoaCreate(FunctionalTester $I)
    {
        $I->wantTo('Verify exception for create');
        $I->amOnRoute('pessoa/create');
        $model[0] = $I->grabRecord('app\models\cidade', array());
        $model[1] = $I->grabRecord('app\models\estado', array());
        $I->submitForm('form',[
            'Pessoa[nome]' => 'Form Tester 001',
            // TODO: This attribute "cpf" contains a custom rule "validateFieldUnique", enter it manually.
            'Pessoa[cpf]' => '07908787126',
            // TODO: This attribute "cep" contains a custom rule "ceptester", enter it manually.
            'Pessoa[cep]' => '75063386',
            'Pessoa[rua]' => 'Form Tester 001',
            'Pessoa[cidade_id]' => $model[0]->id,
            'Pessoa[estado_id]' => $model[1]->id,
            'Pessoa[profissao]' => 'Form Tester 001',
            'Pessoa[complemento]' => 'Form Tester 001',
        ]);
        $I->seeRecord('app\models\Pessoa', [
            'nome' => 'Form Tester 001',
        ]);
        // TODO: This attribute "cpf" contains a custom rule "validateFieldUnique", enter it manually.
        $I->seeRecord('app\models\Pessoa', [
            'cpf' => '07908787126',
        ]);
        // TODO: This attribute "cep" contains a custom rule "ceptester", enter it manually.
        $I->seeRecord('app\models\Pessoa', [
            'cep' => '75063386',
        ]);
        $I->seeRecord('app\models\Pessoa', [
            'rua' => 'Form Tester 001',
        ]);
        $I->seeRecord('app\models\Pessoa', [
            'profissao' => 'Form Tester 001',
        ]);
        $I->seeRecord('app\models\Pessoa', [
            'complemento' => 'Form Tester 001',
        ]);
    }

    //Test Update
    public function PessoaUpdate(FunctionalTester $I)
    {
        $I->wantTo("Verify exception for Update");
        $model = $I->grabRecord('app\models\Pessoa', array('nome' => 'Form Tester 001'));
        $model2[0] = $I->grabRecord('app\models\cidade', array());
        $model2[1] = $I->grabRecord('app\models\estado', array());
        $id = $model->id;
        $I->amOnRoute('pessoa/update',['id' => $id]);
        $I->submitForm('form',[
            'Pessoa[nome]' => 'Form Tester 002',
            // TODO: This attribute "cpf" contains a custom rule "validateFieldUnique", enter it manually.
            'Pessoa[cpf]' => '07908787126',
            // TODO: This attribute "cep" contains a custom rule "ceptester", enter it manually.
            'Pessoa[cep]' => '75063386',
            'Pessoa[rua]' => 'Form Tester 001',
            'Pessoa[cidade_id]' => $model2[0]->id,
            'Pessoa[estado_id]' => $model2[1]->id,
            'Pessoa[profissao]' => 'Form Tester 001',
            'Pessoa[complemento]' => 'Form Tester 002',
        ]);
        $I->seeRecord('app\models\pessoa', [
            'nome' => 'Form Tester 002',
        ]);
        // TODO: This attribute "cpf" contains a custom rule "validateFieldUnique", enter it manually.
        $I->seeRecord('app\models\pessoa', [
            'cpf' => '07908787126',
        ]);
        // TODO: This attribute "cep" contains a custom rule "ceptester", enter it manually.
        $I->seeRecord('app\models\pessoa', [
            'cep' => '75063386',
        ]);
        $I->seeRecord('app\models\pessoa', [
            'rua' => 'Form Tester 001',
        ]);
        $I->seeRecord('app\models\pessoa', [
            'profissao' => 'Form Tester 001',
        ]);
        $I->seeRecord('app\models\pessoa', [
            'complemento' => 'Form Tester 002',
        ]);
    }
}
