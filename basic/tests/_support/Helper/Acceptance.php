<?php
namespace Helper;

use Codeception\Event\PrintResultEvent;
use Codeception\Event\StepEvent;
use Codeception\Event\TestEvent;
use Codeception\Events;
use Codeception\Lib\ModuleContainer;
use Codeception\Lib\ModuleContainerTest;
use Codeception\Test\Cest;
use Codeception\Test\Descriptor;
use Codeception\Util\Debug;
use Codeception\TestInterface;


class Acceptance extends \Codeception\Module
{
    public function fillOutSelect2OptionField(\AcceptanceTester $I, $selector, $value)
    {
        $selector = substr($selector, 1);
        $element = "//*[@id='select2-{$selector}-container']/span";
        $I->waitForElementVisible($element);
        $I->click($element);
        $searchField = '.select2-search__field';
        $I->waitForElementVisible($searchField);
        $I->fillField($searchField, $value);
        $I->pressKey($searchField, \WebDriverKeys::ENTER);
    }

    public function _afterStep(\Codeception\Step $step, TestInterface $test)
    {
//        $tr = new TranslateClient('en', 'pt');

        $pos = strpos($step->getLine(), "Acceptance");
        if ($pos === false) {
            $line = explode('/', $step->getLine());
            $dir = $line[count($line) - 2];
            $line = $line[count($line) - 1];
            $line = substr($line, 0, strpos($line, 'Cest'));
        } else {
            $st = $step->getMetaStep();
            $reflector = new \ReflectionClass('Codeception\Step\Meta');
            $metaStep = $reflector->getProperty('file');
            $metaStep->setAccessible(true);
            $class = $metaStep->getValue($st);

            $line = explode('/', $class);
            $dir = $line[count($line) - 2];
            $line = $line[count($line) - 1];
            $line = substr($line, 0, strpos($line, 'Cest'));
        }

        $path = codecept_absolute_path('tests/documentacao/') . "Use-Case/" . $dir . "/";
        $arquivo = $path . $line . "-" . $test->getMetadata()->getName() . ".html";

        if (!is_dir($path))
            mkdir($path, 0777, true);
        $fp = fopen($arquivo, "a+");

        $texto = $step->getHtml();

        $texto = str_replace("I ", "<br> I ", $texto);

//        $texto = $tr->translate($texto);

        fwrite($fp, $texto);
        fclose($fp);
    }

}
