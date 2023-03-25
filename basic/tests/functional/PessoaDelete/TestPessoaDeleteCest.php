<?php
class TestPessoaDeleteCest
{
    public function _before(FunctionalTester $I){
        // TODO: Enter a login method if needed!
    }

    //Test Delete
    public function PessoaDelete(FunctionalTester $I)
    {
        $I->wantTo('Verify exception for Delete');
        $model = $I->grabRecord('app\models\Pessoa', array(
            'nome' => 'Form Tester 002',
            'cpf' => '35963958288',
            'cep' => '76142342',
            'rua' => 'Form Tester 001',
            'profissao' => 'Form Tester 001',
            'complemento' => 'Form Tester 002',

            // TODO: Fill the string with the last value of the data in the update
        ));
        $id = $model->id;
        $I->amOnRoute('/pessoa/delete', ['id' => $id]);
        $I->dontSeeRecord('app\models\Pessoa', [
            'id'=> $id,
        ]);
    }
}
