<?php
class testePessoaDeleteCest{
    public function pessoaDelete(FunctionalTester $I){
        $I->expectTo("Verificar se os dados da Pessoa foi deletado do banco!");
        $pessoa = $I->grabRecord("app\models\Pessoa", array("nome"=>"Joao Pedro"));
        $I->amOnRoute("pessoa/delete", ["id"=>$pessoa->id]);
        $I->dontSeeRecord("app\models\Pessoa",[
            "id" => $pessoa->id,
            "nome" => $pessoa->nome
        ]);

    }
}