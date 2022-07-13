<?php

use yii\helpers\StringHelper;
use \yii\validators;

$dbName = $generator->getDbConnection()->driverName;
$helper = new \allankaio\giitester\Helpers();
$modelClass = $generator->modelClass;
$modelClassName = StringHelper::basename($generator->modelClass);
$pks = $generator->tableSchema->primaryKey;
$arrayfk = $generator->tableSchema->foreignKeys;
$tableSchema = $generator->tableSchema;
$model = new $modelClass;
$labels = $model->attributeLabels();
//Parametros
$string = 'Form Tester 002';
$email = 'formtester@gmail.com';
$date = date('d/m/Y');
$int = 100;
$modelRules = $model->rules();
echo "<?php\n";
?>
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
        $I->login('superadmin','superadmin');
    }

    //Test Delete
    public function <?= $modelClassName . 'Delete' ?>(FunctionalTester $I)
    {
        $I->wantTo('Verify exception for Delete');
<?php foreach ($tableSchema->columns as $column): ?>
<?php if ($column->allowNull==false && !$column->isPrimaryKey && $column->type=='text'):?>
        $category = $I->grabRecord('app\models\<?=$modelClassName?>', array('<?=$column->name?>' => '<?=$string?>'));
<?php break;?>
<?php elseif ($column->allowNull==false && !$column->isPrimaryKey && $column->phpType=='integer'):?>
        $category = $I->grabRecord('app\models\<?=$modelClassName?>', array('<?=$column->name?>' => '<?=$int?>'));
<?php break;?>
<?php endif;?>
<?php endforeach;?>
<?php foreach ($tableSchema->columns as $column): ?>
<?php if ($column->isPrimaryKey):?>
        $id = $category-><?=$column->name?>;
        $I->sendAjaxPostRequest(['/<?=$tableSchema->fullName?>/delete', 'id' => $id]);
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