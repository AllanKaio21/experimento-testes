<?php
class TesteCest
{
    // Create
    public function TesteCreate(FunctionalTester $I)
    {
        $I->expectTo('Verifica se o cadastro das informações de uma pessoa está correto.');
        $I->amOnRoute('teste/create');
        $I->submitForm('form',[
            'Teste[nome]' => 'João',
            'Teste[cpf]' => '86523435008',
            'Teste[idade]' => '21',
        ]);
        $I->seeRecord('app\models\Teste', [
            'nome' => 'João',
        ]);
        $I->seeRecord('app\models\Teste', [
            'cpf' => '86523435008',
        ]);
        $I->seeRecord('app\models\Teste', [
            'idade' => '21',
        ]);
    }

    // Update
    public function testeUpdate(FunctionalTester $I)
    {
        $I->expectTo("Verificar se atualização das informações está correto.");
        $model = $I->grabRecord('app\models\Teste', array('nome' => 'João'));
        $I->amOnRoute('teste/update',['id' => $model->id]);
        $I->submitForm('form',[
            'Teste[nome]' => 'João Pedro',
            'Teste[cpf]' => $model->cpf,
            'Teste[idade]' => '22',
        ]);
        $I->seeRecord('app\models\teste', [
            'nome' => 'João Pedro',
        ]);
        $I->seeRecord('app\models\teste', [
            'cpf' => $model->cpf,
        ]);
        $I->seeRecord('app\models\teste', [
            'idade' => '22',
        ]);
    }
}
