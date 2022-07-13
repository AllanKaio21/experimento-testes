<?php

use yii\helpers\StringHelper;
use \yii\validators;

$dbName = $generator->getDbConnection()->driverName;
$helper = new \allankaio\giitester\helpers();
$modelClass = $generator->modelClass;
$modelClassName = StringHelper::basename($generator->modelClass);
$pks = $generator->tableSchema->primaryKey;
$arrayfk = $generator->tableSchema->foreignKeys;
$tableSchema = $generator->tableSchema;
$model = new $modelClass;
$modelRules = $model->rules();
$labels = $model->attributeLabels();
$require = new validators\RequiredValidator();
$integer = new validators\NumberValidator();
$exist = new validators\ExistValidator();
$unique = new validators\UniqueValidator();
$minOrMax = new validators\StringValidator();
$url = new validators\UrlValidator();
$dateValid = new validators\DateValidator();
$boolValid = new validators\BooleanValidator();
$emailValid = new validators\EmailValidator();
$aux = $helper->isThisRule('nome',$modelRules,'required');
//Parametros
$string = 'Form Tester 001';
$string2 = 'Form Tester 002';
$email = 'formtester@gmail.com';
$date = date('d/m/Y').'11';
$int = 100;
$time = '01:00:00';
$bool = true;
echo "<?php\n";
?>
class Test<?= $modelClassName ?>DateTypesCest
{
    public function _before(FunctionalTester $I){
        $I->login('superadmin','superadmin');
    }

    //Test Not Null Fields
    public function <?= $modelClassName . 'NotNullFieds' ?>(FunctionalTester $I)
    {
        $I->wantTo('Verify exception for not null fields');
        $I->amOnPage('<?= $tableSchema->fullName ?>/create');
        $I->submitForm('form',[]);
<?php foreach ($tableSchema->columns as $column): ?>
<?php if (($column->allowNull==false && $column->name!=$pks[0]) || $helper->isThisRule($column->name, $modelRules, 'required')): ?>
<?php $options = $require->getClientOptions($model, $column->name)?>
<?php $isCusMens = $helper->isCustomMessage($modelRules, 'required', $column->name)?>
<?php if ($isCusMens[0]):?>
        $I->see('<?= $isCusMens[1]?>');
<?php else:?>
        $I->see('<?= $options['message']?>');
<?php endif;?>
<?php endif;?>
<?php endforeach;?>
    }

    //test Integer Fields
    public function <?= $modelClassName . 'IntegerFields' ?>(FunctionalTester $I)
    {
        $I->wantTo('Verify exception for integer fields');
        $I->amOnPage('<?= $tableSchema->fullName ?>/create');
        $I->submitForm('form',[
<?php foreach ($tableSchema->columns as $column): ?>
<?php if (!$column->isPrimaryKey && $column->phpType=='integer'):?>
            <?= "'{$modelClassName}[{$column->name}]' => '$string'" ?>,
<?php endif;?>
<?php endforeach;?>
        ]);
<?php foreach ($tableSchema->columns as $column): ?>
<?php if ($column->phpType=='integer' &&!$column->isPrimaryKey):?>
<?php $integer->integerOnly = true?>
<?php $integer->message = null?>
<?php $integer->init()?>
<?php $integer->validateAttribute($model, $column->name)?>
<?php $options = $integer->getClientOptions($model, $column->name)?>
<?php $isCusMens = $helper->isCustomMessage($modelRules, 'integer', $column->name)?>
<?php if ($isCusMens[0]):?>
        $I->see('<?= $isCusMens[1]?>');
<?php else:?>
        $I->see('<?= $options['message']?>');
<?php endif;?>
<?php endif;?>
<?php endforeach;?>
    }

    //test Boolean Fields
    public function <?= $modelClassName . 'BooleanFields' ?>(FunctionalTester $I)
    {
        $I->wantTo('Verify exception for Boolean fields');
        $I->amOnPage('<?= $tableSchema->fullName ?>/create');
        $I->submitForm('form',[
<?php foreach ($tableSchema->columns as $column): ?>
<?php if (!$column->isPrimaryKey && $column->phpType=='boolean'):?>
            <?= "'{$modelClassName}[{$column->name}]' => '$string'" ?>,
<?php endif;?>
<?php endforeach;?>
        ]);
<?php foreach ($tableSchema->columns as $column): ?>
<?php if ($column->phpType=='boolean' &&!$column->isPrimaryKey):?>
<?php $options = $boolValid->getClientOptions($model, $column->name)?>
<?php $isCusMens = $helper->isCustomMessage($modelRules, 'boolean', $column->name)?>
<?php if ($isCusMens[0]):?>
        $I->see('<?= $isCusMens[1]?>');
<?php else:?>
        $I->see('<?= $options['message']?>');
<?php endif;?>
<?php endif;?>
<?php endforeach;?>
    }

    //test Date Fields
    public function <?= $modelClassName . 'DateFields' ?>(FunctionalTester $I)
    {
        $I->wantTo('Verify exception for Date fields');
        $I->amOnPage('<?= $tableSchema->fullName ?>/create');
        $I->submitForm('form',[
<?php $vf = false;?>
<?php foreach ($tableSchema->columns as $column): ?>
<?php if($helper->isFormatDate($column->name, $modelRules)[0]):?>
<?php $vf = true?>
<?php break;?>
<?php endif;?>
<?php endforeach;?>
<?php if($vf):?>
<?php foreach ($tableSchema->columns as $column): ?>
<?php if ($column->type!='date' && $column->allowNull==false && !$column->isPrimaryKey):?>
            <?= "'{$modelClassName}[{$column->name}]' => '1'" ?>,
<?php endif;?>
<?php if (!$column->isPrimaryKey && $column->type=='date' ):?>
            <?= "'{$modelClassName}[{$column->name}]' => '$date'" ?>,
<?php endif;?>
<?php endforeach;?>
<?php endif;?>
        ]);
<?php foreach ($tableSchema->columns as $column): ?>
<?php if($helper->isFormatDate($column->name, $modelRules)[0]):?>
<?php if ($column->type=='date' && !$column->isPrimaryKey && isset($labels[$column->name])):?>
<?php $isCusMens = $helper->isCustomMessage($modelRules, 'date', $column->name)?>
<?php if ($isCusMens[0]):?>
        $I->see('<?= $isCusMens[1]?>');
<?php else:?>
        $I->see('<?= str_replace('{attribute}',$labels[$column->name],$dateValid->message)?>');
<?php endif;?>
<?php endif;?>
<?php endif;?>
<?php endforeach;?>
    }

    //test Max or Min Fields
    public function <?= $modelClassName . 'MaxOrMinFields' ?>(FunctionalTester $I)
    {
        $I->wantTo('Verify exception for Min or Max fields');
        $I->amOnPage('<?= $tableSchema->fullName ?>/create');
        $I->submitForm('form',[
<?php foreach ($tableSchema->columns as $column): ?>
<?php $vf = $helper->isMinOrMax($column->name,$modelRules)?>
<?php if ($vf[0]):?>
            <?= "'{$modelClassName}[{$column->name}]' =>"?> '<?=$helper->genMinOrMaxFail($vf[1],$vf[2],$column->phpType)?>',
<?php endif;?>
<?php endforeach;?>
        ]);
<?php foreach ($tableSchema->columns as $column): ?>
<?php $vf = $helper->isMinOrMax($column->name,$modelRules)?>
<?php if ($vf[0]):?>
<?php $minOrMax->min = $vf[1]?>
<?php $minOrMax->max = $vf[2]?>
<?php $minOrMax->init()?>
<?php $minOrMax->validateAttribute($model, $column->name)?>
<?php $options = $minOrMax->getClientOptions($model, $column->name)?>
<?php if ($vf[2]!=null):?>
        $I->see('<?= $options['tooLong']?>');
<?php else:?>
        $I->see('<?= $options['tooShort']?>');
<?php endif;?>
<?php endif;?>
<?php endforeach;?>
    }

    //test String Fields
    public function <?= $modelClassName . 'UrlFields' ?>(FunctionalTester $I)
    {
        $I->wantTo('Verify exception for Url fields');
        $I->amOnPage('<?= $tableSchema->fullName ?>/create');
        $I->submitForm('form',[
<?php foreach ($tableSchema->columns as $column): ?>
<?php if (!$column->isPrimaryKey && $column->type=='url' ):?>
            <?= "'{$modelClassName}[{$column->name}]' => '$string'" ?>,
<?php endif;?>
<?php endforeach;?>
        ]);
<?php foreach ($tableSchema->columns as $column): ?>
<?php if ($column->type=='url' && !$column->isPrimaryKey):?>
<?php $url->validateAttribute($model, $column->name)?>
<?php $options = $url->getClientOptions($model, $column->name)?>
<?php $isCusMens = $helper->isCustomMessage($modelRules, 'url', $column->name)?>
<?php if ($isCusMens[0]):?>
        $I->see('<?= $isCusMens[1]?>');
<?php else:?>
        $I->see('<?= $options['message']?>');
<?php endif;?>
<?php endif;?>
<?php endforeach;?>
    }

    //Test FK Fields
    public function <?= $modelClassName . 'FkFields' ?>(FunctionalTester $I)
    {
        $I->wantTo('Verify exception for fk fields');
        $I->amOnPage('<?= $tableSchema->fullName ?>/create');
        $I->submitForm('form',[
<?php $i = 0?>
<?php  foreach ($arrayfk as $fk): ?>
<?php  foreach ($fk as $id): ?>
<?php $key[$i] = $id?>
<?php endforeach;?>
<?php $i++?>
<?php endforeach;?>
<?php $i = 0?>
<?php foreach ($arrayfk as $fk): ?>
<?php $col[$i] = array_search($key[$i], $fk)?>
            <?= "'{$modelClassName}[{$col[$i]}]' => '99999999'" ?>,
<?php $i++?>
<?php endforeach;?>
        ]);
<?php $i = 0?>
<?php foreach ($arrayfk as $fk): ?>
<?php if (isset($labels[$col[$i]])):?>
<?php $isCusMens = $helper->isCustomMessage($modelRules, 'exist', $column->name)?>
<?php if ($isCusMens[0]):?>
        $I->see('<?= $isCusMens[1]?>');
<?php else:?>
        $I->see('<?=str_replace('{attribute}', $labels[$col[$i]], $exist->message)?>');
<?php endif;?>

<?php $i++?>
<?php endif;?>
<?php endforeach;?>
    }

    //Test Unique Fields
    public function <?= $modelClassName . 'UniqueFields' ?>(FunctionalTester $I)
    {
        $I->wantTo('Verify exception for Unique Fields');
        $I->amOnPage('<?= $tableSchema->fullName ?>/create');
<?php foreach ($tableSchema->columns as $column): ?>
<?php $uni = $helper->uniqueField($column->name, $modelRules)?>
<?php if($uni):?>
        $category = $I->grabRecord('app\models\<?=$modelClassName?>', array());
<?php break;?>
<?php endif;?>
<?php endforeach;?>
<?php foreach ($tableSchema->columns as $column): ?>
<?php $uni = $helper->uniqueField($column->name, $modelRules)?>
<?php $isCusMens = $helper->isCustomMessage($modelRules, 'unique', $column->name)?>
<?php if ($column->phpType=='integer' && $uni):?>
        $I->submitForm('form',[
            <?="'{$modelClassName}[{$column->name}]' => "?>$category-><?=$column->name?>]);
<?php if ($isCusMens[0]):?>
        $I->see('<?= $isCusMens[1]?>');
<?php elseif(isset($labels[$column->name])):?>
        $I->see(str_replace('{value}',$category-><?=$column->name?>,'<?= (str_replace('{attribute}', $labels[$column->name], $unique->message))?>'));
<?php endif;?>
<?php elseif ($column->type=='date' && $uni):?>
        $I->submitForm('form',[
            <?="'{$modelClassName}[{$column->name}]' => "?>$category-><?=$column->name?>]);
<?php if ($isCusMens[0]):?>
        $I->see('<?= $isCusMens[1]?>');
<?php elseif(isset($labels[$column->name])):?>
        $I->see(str_replace('{value}',$category-><?=$column->name?>,'<?= (str_replace('{attribute}', $labels[$column->name], $unique->message))?>'));
<?php endif;?>
<?php elseif ($column->type=='text' && $uni):?>
        $I->submitForm('form',[
            <?="'{$modelClassName}[{$column->name}]' => "?>$category-><?=$column->name?>]);
<?php if ($isCusMens[0]):?>
        $I->see('<?= $isCusMens[1]?>');
<?php elseif(isset($labels[$column->name])):?>
        $I->see(str_replace('{value}',$category-><?=$column->name?>,'<?= (str_replace('{attribute}', $labels[$column->name], $unique->message))?>'));
<?php endif;?>

<?php endif;?>
<?php endforeach;?>
    }

    //test Email Fields
    public function <?= $modelClassName . 'EmailFields' ?>(FunctionalTester $I)
    {
        $I->wantTo('Verify exception for Email fields');
        $I->amOnPage('<?= $tableSchema->fullName ?>/create');
        $I->submitForm('form',[
<?php foreach ($tableSchema->columns as $column): ?>
<?php $vfemail = $helper->emailField($column->name,$modelRules)?>
<?php if ($vfemail):?>
            <?= "'{$modelClassName}[{$column->name}]' => '$string'" ?>,
<?php endif;?>
<?php endforeach;?>
        ]);
<?php foreach ($tableSchema->columns as $column): ?>
<?php $vfemail = $helper->emailField($column->name,$modelRules)?>
<?php if ($vfemail):?>
<?php $options = $emailValid->getClientOptions($model, $column->name)?>
<?php $isCusMens = $helper->isCustomMessage($modelRules, 'email', $column->name)?>
<?php if ($isCusMens[0]):?>
        $I->see('<?= $isCusMens[1]?>');
<?php else:?>
        $I->see('<?= $options['message']?>');
<?php endif;?>
<?php endif;?>
<?php endforeach;?>
    }
}
<?php $helper->testerExecOrder()?>