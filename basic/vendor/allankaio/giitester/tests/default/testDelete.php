<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use \yii\validators;

$dbName = $generator->getDbConnection()->driverName;
$helper = new \allankaio\giitester\Helpers();
$modelClass = $generator->modelClass;
$modelClassName = StringHelper::basename($generator->modelClass);
$pks = $generator->tableSchema->primaryKey;
$arrayfk = $generator->tableSchema->foreignKeys;
$tableSchema = $generator->tableSchema;
$modelvf = "/".$modelClass.".php";
$bar = str_replace(" ", "", "\ ");
$modelvf = str_replace($bar, "/", $modelvf);
if(file_exists($modelvf)) {
    $model = new $modelClass;
    $modelRules = $model->rules();
    $labels = $model->attributeLabels();
}
//Parametros
$string = 'Form Tester 002';
$email = 'formtester@gmail.com';
$date = date('d/m/Y');
$int = 100;
$route = Inflector::camel2id($modelClassName);
echo "<?php\n";
?>
<?php if(file_exists($modelvf)):?>
class Test<?= $modelClassName ?>DeleteCest
{
<?php $atribute[0] = $modelClassName?>
<?php $i = 1?>
<?php foreach ($arrayfk as $fk): ?>
<?php $atribute[$i] = $atribute[0].'=>'.ucfirst($fk[0])."\r\n"?>
<?php $i++?>
<?php endforeach;?>
<?php if(count($arrayfk)<1):?>
<?php $atribute[1] = $atribute[0].'=>null'."\r\n"?>
<?php $helper->testerSave($atribute)?>
<?php else:?>
<?php $helper->testerSave($atribute)?>
<?php endif;?>
    public function _before(FunctionalTester $I){
        // TODO: Enter a login method if needed!
    }

    //Test Delete
    public function <?= $modelClassName . 'Delete' ?>(FunctionalTester $I)
    {
        $I->wantTo('Verify exception for Delete');
<?php foreach ($tableSchema->columns as $column): ?>
<?php if (!$column->isPrimaryKey && ($column->type=='text' || $column->type=='string')):?>
        $model = $I->grabRecord('app\models\<?=$modelClassName?>', array(
            '<?=$column->name?>' => '<?=$string?>'
            // TODO: Fill the string with the last value of the data in the update
        ));
<?php break;?>
<?php elseif (!$column->isPrimaryKey && $column->phpType=='integer'):?>
        $model = $I->grabRecord('app\models\<?=$modelClassName?>', array(
            '<?=$column->name?>' => '<?=$int?>'
            // TODO: Fill the string with the last value of the data in the update
        ));
<?php break;?>
<?php endif;?>
<?php endforeach;?>
<?php foreach ($tableSchema->columns as $column): ?>
<?php if ($column->isPrimaryKey):?>
        $id = $model-><?=$column->name?>;
        $I->amOnRoute('/<?= $route ?>/delete', ['id' => $id]);
<?php break;?>
<?php endif;?>
<?php endforeach;?>
<?php foreach ($tableSchema->columns as $column): ?>
<?php if ($column->isPrimaryKey):?>
        $I->dontSeeRecord('app\models\<?=$modelClassName?>', [
            <?="'{$column->name}'=> "?>$id,
        ]);
<?php break;?>
<?php endif;?>
<?php endforeach;?>
    }
}
<?php $helper->testerExecOrder()?>
<?php else:?>
    //TODO: Template "<?=$modelvf?>" not found, you must create the template for this table before creating the autotest.
<?php endif;?>
