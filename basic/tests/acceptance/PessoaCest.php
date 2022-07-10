<?php
class PessoaCest 
{
    public function frontpageWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/pessoa/create');
        $I->see('Pessoa');
		$I->fillField('#pessoa-nome', 'Peterson');
		$I->fillField('#pessoa-cpf', '06608824181');
		$I->fillField('#pessoa-cep', '75131570');
		$I->click('Save');
		$I->wait(5);
		$I->anOmPage('/pessoa/view?id=2');
		$I->see('Peterson');
    }
}