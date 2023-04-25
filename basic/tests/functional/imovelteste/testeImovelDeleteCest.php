<?php
class testeImovelDeleteCest{
    public function imovelDelete(FunctionalTester $I){
        $I->expectTo("Verifica se os dados do Imovel foi deletado");
        $imovel = $I->grabRecord("app\models\Imovel", array("nome"=>"Casa Grande"));
        $I->amOnRoute("imovel/delete", ["id"=> $imovel->id]);
        $I->dontSeeRecord("app\models\Imovel",[
            "id" => $imovel->id,
            "nome" => $imovel->nome
        ]);
        
    }
}