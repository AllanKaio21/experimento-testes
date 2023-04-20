<?php
class ImovelTesteCest
{
    // Create
    public function ImovelCreate(FunctionalTester $I)
    {
        $pessoa = $I->grabRecord('app\models\Pessoa', array('nome' => 'Joao Pedro'));
        $I->expectTo('Verifica se o cadastro das informações de um imovel está correto.');
        $I->amOnRoute('imovel/create');
        $I->submitForm('form',[
            'Imovel[nome]' => 'Casa',
            'Imovel[pessoa_id]' => $pessoa->id,
            'Imovel[cep]' => '69945000',
            'Imovel[rua]' => 'Rua 01',
            'Imovel[estado_id]' => 1,
            'Imovel[cidade_id]' => 1,
            'Imovel[valor]' => '500000',
            'Imovel[complemento]' => 'Perto do mercado', 
        ]);

        $I->seeRecord('app\models\Imovel', [
            'nome' => 'Casa',
        ]);
        $I->seeRecord('app\models\Imovel', [
            'pessoa_id' => $pessoa->id,
        ]);
        $I->seeRecord('app\models\Imovel', [
            'cep' => '69945000',
        ]);
        $I->seeRecord('app\models\Imovel',[
            'rua' => 'Rua 01',
        ]);
        $I->seeRecord('app\models\Imovel',[
            'estado_id' => 1,
        ]);
        $I->seeRecord('app\models\Imovel',[
            'cidade_id' => 1,
        ]);
        $I->seeRecord('app\models\Imovel',[
            'valor' => '500000',
        ]);
        $I->seeRecord('app\models\Imovel',[
            'complemento' => 'Perto do mercado',
        ]);
    }

    // Update
    public function ImovelUpdate(FunctionalTester $I)
    {
        $I->expectTo("Verificar se atualização das informações está correto.");
        $model = $I->grabRecord('app\models\Imovel', array('nome' => 'Casa'));
        $pessoa = $I->grabRecord('app\models\Pessoa', array('nome' => 'Joao Pedro'));
        $I->amOnRoute('imovel/update',['id' => $model->id]);
        $I->submitForm('form',[
            'Imovel[nome]' => 'Casa Grande',
            'Imovel[pessoa_id]' => $pessoa->id,
            'Imovel[cep]' => '69945000',
            'Imovel[rua]' => 'Rua 02',
            'Imovel[estado_id]' => 1,
            'Imovel[cidade_id]' => 1,
            'Imovel[valor]' => '140000',
            'Imovel[complemento]' => 'Perto da faculdade',
        ]);
        $I->seeRecord('app\models\Imovel', [
            'nome' => 'Casa Grande',
        ]);
        $I->seeRecord('app\models\Imovel', [
            'pessoa_id' => $pessoa->id,
        ]);
        $I->seeRecord('app\models\Imovel', [
            'cep' => '69945000',
        ]);
        $I->seeRecord('app\models\Imovel',[
            'rua' => 'Rua 02',
        ]);
        $I->seeRecord('app\models\Imovel',[
            'estado_id' => 1,
        ]);
        $I->seeRecord('app\models\Imovel',[
            'cidade_id' => 1,
        ]);
        $I->seeRecord('app\models\Imovel',[
            'valor' => '140000',
        ]);
        $I->seeRecord('app\models\Imovel',[
            'complemento' => 'Perto da faculdade',
        ]);
    }
}
