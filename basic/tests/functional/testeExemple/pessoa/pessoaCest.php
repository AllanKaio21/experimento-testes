<?php
class pessoaCest
{
    // Create
    public function pessoaCreate(FunctionalTester $I)
    {
        $I->expectTo('Verifica se o cadastro das informações de uma pessoa está correto.');
        $I->amOnRoute('pessoa/create');
        $I->submitForm('form',[
            'Pessoa[nome]' => 'Joao',
            'Pessoa[cpf]' => '86523435008',
            'Pessoa[cep]' => '69945000',
            'Pessoa[rua]' => 'Rua 01',
            'Pessoa[estado_id]' => 1,
            'Pessoa[cidade_id]' => 1,
            'Pessoa[profissao]' => 'Estudante',
            'Pessoa[complemento]' => 'Perto do mercado'


        ]);
        $I->seeRecord('app\models\Pessoa', [
            'nome' => 'Joao',
        ]);
        $I->seeRecord('app\models\Pessoa', [
            'cpf' => '86523435008',
        ]);
        $I->seeRecord('app\models\Pessoa', [
            'cep' => '69945000',
        ]);
        
        $I->seeRecord('app\models\Pessoa', [
            'rua' => 'Rua 01',
        ]);

        $I->seeRecord('app\models\Pessoa', [
            'estado_id' => 1,
        ]);

        $I->seeRecord('app\models\Pessoa', [
            'cidade_id' => 1,
        ]);

        $I->seeRecord('app\models\Pessoa', [
            'profissao' => 'Estudante',
        ]);

        $I->seeRecord('app\models\Pessoa', [
            'complemento' => 'Perto do mercado',
        ]);


    }

    // Update
    public function PessoaUpdate(FunctionalTester $I)
    {
        $I->expectTo("Verificar se atualização das informações de uma pessoa está correta.");
        $model = $I->grabRecord('app\models\Pessoa', array('nome' => 'Joao'));
        $I->amOnRoute('pessoa/update',['id' => $model->id]);
        $I->submitForm('form',[
            'Pessoa[nome]' => 'Joao Pedro',
            'Pessoa[cpf]' => '86523435008',
            'Pessoa[cep]' => '69945000',
            'Pessoa[rua]' => 'Rua 02',
            'Pessoa[estado_id]' => 1,
            'Pessoa[cidade_id]' => 1,
            'Pessoa[profissao]' => 'Programador',
            'Pessoa[complemento]' => 'Perto da faculdade'
        ]);
        $I->seeRecord('app\models\Pessoa', [
            'nome' => 'Joao Pedro',
            ]);

        $I->seeRecord('app\models\Pessoa', [
            'cpf' => '86523435008',
        ]);

        $I->seeRecord('app\models\Pessoa', [
            'cep' => '69945000',
        ]);
        
        $I->seeRecord('app\models\Pessoa', [
            'rua' => 'Rua 02',
        ]);

        $I->seeRecord('app\models\Pessoa', [
            'estado_id' => 1,
        ]);

        $I->seeRecord('app\models\Pessoa', [
            'cidade_id' => 1,
        ]);

        $I->seeRecord('app\models\Pessoa', [
            'profissao' => 'Programador',
        ]);

        $I->seeRecord('app\models\Pessoa', [
            'complemento' => 'Perto da faculdade',
        ]);
       
    }
}
