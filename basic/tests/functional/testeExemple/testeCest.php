<?php
class TesteCest
{
    // Form
    public function TesteForm(FunctionalTester $I)
    {
        $I->expectTo('Verify exception for form');
        $I->amOnRoute('teste/create');
        $I->submitForm('form',[
            'Teste[nome]' => 'João',
            'Teste[cpf]' => '00011122290',
            'Teste[idade]' => '21',
        ]);
        $I->seeRecord('app\models\Teste', [
            'nome' => 'João',
        ]);
        $I->seeRecord('app\models\Teste', [
            'cpf' => '00011122290',
        ]);
        $I->seeRecord('app\models\Teste', [
            'idade' => '21',
        ]);
    }

    // Update
    public function testeUpdate(FunctionalTester $I)
    {
        $I->expectTo("Verify exception for Update");
        $model = $I->grabRecord('app\models\Teste', array('nome' => 'João'));
        $I->amOnRoute('teste/update',['id' => $model->id]);
        $I->submitForm('form',[
            'Teste[nome]' => 'Jorge',
            'Teste[cpf]' => $model->cpf,
            'Teste[idade]' => $model->idade,
        ]);
        $I->seeRecord('app\models\teste', [
            'id' => $model->id,
            'nome' => 'Jorge',
        ]);
    }
}
