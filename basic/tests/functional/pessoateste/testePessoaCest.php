<?php

class testePessoaCest{

    public function pessoaCreate(FunctionalTester $I){
        $I->expectTo('Verificar se pessoa foi criada');
        $I->amOnRoute("pessoa/create");
        $I->submitForm("form",[ 
            "Pessoa[nome]" =>"Joao",
            "Pessoa[cpf]"=> "86523435008",
            "Pessoa[cep]"=> "69945000",
            "Pessoa[rua]" => "Rua 01",
            "Pessoa[estado_id]" => 1,
            "Pessoa[cidade_id]" => 1,
            "Pessoa[profissao]" => "Estudante",
            "Pessoa[complemento]" => "Perto do mercado",

        ]);

        $I->seeRecord("app\models\Pessoa", [
            'nome' => "Joao",
        ]);

        $I->seeRecord("app\models\Pessoa", [
            'cpf' => "86523435008",

        ]);

        $I->seeRecord("app\models\Pessoa", [
           "cep" => "69945000", 
        ]);

        $I->seeRecord("app\models\Pessoa", [
            "rua" => "Rua 01",    
        ]);

        $I->seeRecord("app\models\Pessoa", [
            "estado_id" => 1    
        ]);

        $I->seeRecord("app\models\Pessoa",[
            "cidade_id" => 1
        ]);

        $I->seeRecord("app\models\Pessoa", [
            "profissao" => "Estudante"
        ]);

        $I->seeRecord("app\models\Pessoa", [
           "complemento" => "Perto do mercado" 
        ]);
    



    }

    public function pessoaUpdate(FunctionalTester $I){
        $I->expectTo("Verificar se os da dos de pessoa foi atualizado");
        $pessoa = $I->grabRecord("app\models\Pessoa", array("nome"=>"Joao"));
        $I->amOnRoute("pessoa/update", ["id" => $pessoa->id]);
        $I->submitForm("form", [
            "Pessoa[nome]" => "Joao Pedro",
            "Pessoa[cpf]" => $pessoa->cpf,
            "Pessoa[cep]" => "69945000",
            "Pessoa[rua]" => "Rua 02",
            "Pessoa[estado_id]" => 1,
            "Pessoa[cidade_id]" => 1,
            "Pessoa[profissao]" => "Programador",
            "Pessoa[complemento]" => "Perto da Faculdade",
        ]);

        $I->seeRecord("app\models\Pessoa",[
            "nome" => "Joao Pedro"
        ]);
        $I->seeRecord("app\models\Pessoa",[
            "cpf" => $pessoa->cpf
        ]);
        $I->seeRecord("app\models\Pessoa",[
            "cep" => "69945000"
        ]);
        $I->seeRecord("app\models\Pessoa", [
            "estado_id" => 1
        ]);
        $I->seeRecord("app\models\Pessoa", [
            "cidade_id" => 1
        ]);
        $I->seeRecord("app\models\Pessoa",[
            "profissao" => "Programador"
        ]);
        $I->seeRecord("app\models\Pessoa",[
            "complemento" => "Perto da Faculdade"
        ]);

    }
}