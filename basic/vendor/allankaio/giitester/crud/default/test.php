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
$labels = $model->attributeLabels();
$require = new validators\RequiredValidator();
$integer = new validators\NumberValidator();
$integer->integerOnly = true;
$integer->message = null;
$integer->init();
$msg = $integer->message;
$exist = new validators\ExistValidator();
$unique = new validators\UniqueValidator();
$dateValid = new validators\DateValidator();
//Parametros
$string = 'Form Tester 001';
$string2 = 'Form Tester 002';
$email = 'formtester@gmail.com';
$date = date('d/m/Y');
$int = 100;
$time = '01:00:00';
$bool = true;
$modelRules = $model->rules();
echo "<?php\n";
?>
<?php $i=0?>
<?php $keys = []?>
<?php $arrayfk2 = []?>
<?php foreach ($arrayfk as $fk):?>
<?php $j=0?>
<?php foreach ($tableSchema->columns as $column):?>
<?php $aux[$j] = [$fk[0], array_keys($fk)[1]];?>
<?php if($aux[$j][1] == $column->name && $column->allowNull==false):?>
<?php $value = [$fk[0], array_keys($fk)[1]]?>
<?php array_push($keys, $value);?>
<?php array_push($arrayfk2,  $fk)?>
<?php endif;?>
<?php endforeach;?>
<?php $j++?>
<?php $i++?>
<?php endforeach;?>
<?php $key2 = []?>
<?php foreach ($keys as $aux):?>
<?php array_push($key2, $aux[1])?>
<?php endforeach;?>
class Test<?= $modelClassName ?>Cest
{
    public function _before(FunctionalTester $I){
        $I->login('superadmin','superadmin');
    }

    //Test Template Form
    public function <?= $modelClassName . 'Form' ?>(FunctionalTester $I)
    {
        $I->wantTo('Verify exception for form');
        $I->amOnPage('<?= $tableSchema->fullName ?>/create');
<?php //Get dependencies?>
<?php $i=0?>
<?php foreach ($tableSchema->columns as $column): ?>
<?php $key = $helper->isKey($keys, $column->name)?>
<?php if($key[0] && $column->allowNull==false):?>
        $category[<?=$i?>] = $I->grabRecord('app\models\<?=$key[1]?>', array());
<?php $i++?>
<?php endif;?>
<?php endforeach;?>
        $I->submitForm('form',[
<?php $i=0;?>
<?php $j=0;?>
<?php $k=0;?>
<?php //Create Values for form?>
<?php foreach ($tableSchema->columns as $column): ?>
<?php $vf = $helper->isMinOrMax($column->name,$modelRules)?>
<?php $inRange = $helper->isInRange($column->name, $modelRules)?>
<?php $vfcpf = $helper->cpfField($column->name,$modelRules)?>
<?php $vfemail = $helper->emailField($column->name,$modelRules)?>
<?php $key = $helper->isKey($keys, $column->name)?>
<?php $uni = $helper->uniqueField($column->name, $modelRules)?>
<?php $vfdate = $helper->isFormatDate($column->name, $modelRules)?>
<?php $isDefault = $helper->isDefaultValidator($column->name, $modelRules)?>
<?php $vfrequired = $helper->isThisRule($column->name, $modelRules, 'required')?>
<?php if (($column->allowNull==false || $vfrequired) && $column->phpType=='integer'):?>
<?php if($vfcpf):?>
<?php $inte[$i] = $helper->genCpfValid()?>
<?php elseif($vf[0]):?>
<?php $inte[$i] = $helper->genMinOrMax($vf[1],$vf[2],$column->phpType)?>
<?php elseif($inRange[0]):?>
    <?php $inte[$i] = $inRange[1]?>
<?php elseif($key[0]):?>
            <?= "'{$modelClassName}[{$column->name}]' =>" ?> $category[<?=$j?>]-><?=$arrayfk2[array_search($column->name,$key2)][$column->name]?>,
<?php if(!$isDefault[0]):?>
            //TODO: This attribute "<?=$column->name?>" contains a custom rule "<?=$isDefault[1]?>", enter it manually.
<?php endif;?>
<?php $j++?>
<?php else:?>
<?php $inte[$i] = $int?>
<?php endif;?>
<?php if(!$key[0]):?>
            <?= "'{$modelClassName}[{$column->name}]' => '$inte[$i]'" ?>,
<?php if(!$isDefault[0]):?>
            //TODO: This attribute "<?=$column->name?>" contains a custom rule "<?=$isDefault[1]?>", enter it manually.
<?php endif;?>
<?php endif;?>
<?php elseif ($column->allowNull==false && $column->type=='date' ):?>
<?php if($vfdate[0]):?>
<?php $format = substr($vfdate[1], 4)?>
<?php $datee[$i] = date($format);?>
            <?= "'{$modelClassName}[{$column->name}]' => '$datee[$i]'" ?>,
<?php if(!$isDefault[0]):?>
            //TODO: This attribute "<?=$column->name?>" contains a custom rule "<?=$isDefault[1]?>", enter it manually.
<?php endif;?>
<?php else:?>
<?php $datee[$i] = $date?>
            <?= "'{$modelClassName}[{$column->name}]' => '$datee[$i]'" ?>,
<?php if(!$isDefault[0]):?>
            //TODO: This attribute "<?=$column->name?>" contains a custom rule "<?=$isDefault[1]?>", enter it manually.
<?php endif;?>
<?php endif;?>
<?php elseif ($column->allowNull==false &&$column->type=='boolean' ):?>
            <?= "'{$modelClassName}[{$column->name}]' => $bool" ?>,
<?php if(!$isDefault[0]):?>
            //TODO: This attribute "<?=$column->name?>" contains a custom rule "<?=$isDefault[1]?>", enter it manually.
<?php endif;?>
<?php elseif($column->allowNull==false && $column->type == 'time'):?>
            <?= "'{$modelClassName}[{$column->name}]' => '$time'" ?>,
<?php if(!$isDefault[0]):?>
            //TODO: This attribute "<?=$column->name?>" contains a custom rule "<?=$isDefault[1]?>", enter it manually.
<?php endif;?>
<?php elseif ($column->allowNull==false && $column->type=='text'):?>
<?php if($vfcpf):?>
<?php $stringe[$i] = $helper->genCpfValid()?>
<?php elseif($vf[0]):?>
<?php $stringe[$i] = $helper->genMinOrMax($vf[1],$vf[2],$column->phpType)?>
<?php elseif($inRange[0]):?>
<?php $stringe[$i] = $inRange[1]?>
<?php elseif($vfemail):?>
<?php $stringe[$i] = $email?>
<?php elseif($key[0]):?>
            <?= "'{$modelClassName}[{$column->name}]' =>" ?> $category[<?=$j?>]-><?=$arrayfk2[array_search($column->name, $key2)][$column->name]?>,
<?php if(!$isDefault[0]):?>
            //TODO: This attribute "<?=$column->name?>" contains a custom rule "<?=$isDefault[1]?>", enter it manually.
<?php endif;?>
<?php $j++?>
<?php else:?>
<?php $stringe[$i] = $string?>
<?php endif;?>
<?php if(!$key[0]):?>
            <?= "'{$modelClassName}[{$column->name}]' => '$stringe[$i]'" ?>,
<?php if(!$isDefault[0]):?>
            //TODO: This attribute "<?=$column->name?>" contains a custom rule "<?=$isDefault[1]?>", enter it manually.
<?php endif;?>
<?php endif;?>
<?php elseif ($column->allowNull==false):?>
            <?= "'{$modelClassName}[{$column->name}]' => ''" ?>,
<?php if(!$isDefault[0]):?>
            //TODO: This attribute "<?=$column->name?>" contains a custom rule "<?=$isDefault[1]?>", enter it manually.
<?php endif;?>
<?php endif;?>
<?php $i++;?>
<?php endforeach;?>
        ]);
<?php $i=0;?>
<?php $j=0;?>
<?php //Creates verification of the data registered in the form?>
<?php foreach ($tableSchema->columns as $column): ?>
<?php $key = $helper->isKey($keys, $column->name)?>
<?php $isDefault = $helper->isDefaultValidator($column->name, $modelRules)?>
<?php if ($column->allowNull==false && $column->phpType=='integer' && !$column->isPrimaryKey && $isDefault[0]):?>
        $I->seeRecord('app\models\<?= $modelClassName ?>', [
            <?= "'$column->name' => "?><?= $key[0] ? '$category[' . $j . ']->' . $arrayfk2[array_search($column->name, $key2)][$column->name] : "'$inte[$i]'" ?>,
        ]);
<?php elseif ($column->allowNull==false && $column->type=='date' && !$column->isPrimaryKey && $isDefault[0]):?>
        $I->seeRecord('app\models\<?= $modelClassName ?>', [
            <?= "'$column->name' => "?><?= $key[0] ? '$category[' . $j . ']->' . $arrayfk2[array_search($column->name, $key2)][$column->name] : "'$datee[$i]'" ?>,
        ]);
<?php elseif ($column->allowNull==false && $column->type=='boolean' && !$column->isPrimaryKey):?>
        $I->seeRecord('app\models\<?= $modelClassName ?>', [
            <?= "'$column->name' => "?><?= $key[0] ? '$category[' . $j . ']->' . $arrayfk2[array_search($column->name, $key2)][$column->name] : "'$bool[$i]'" ?>,
        ]);
<?php elseif ($column->allowNull==false && $column->type == 'time'):?>
        $I->seeRecord('app\models\<?= $modelClassName ?>', [
            <?= "'$column->name' => "?><?= $key[0] ? '$category[' . $j . ']->' . $arrayfk2[array_search($column->name, $key2)][$column->name] : "'$time[$i]'" ?>,
        ]);
<?php elseif ($column->allowNull==false && $column->type=='text' && !$column->isPrimaryKey && $isDefault[0]):?>
        $I->seeRecord('app\models\<?= $modelClassName ?>', [
            <?= "'$column->name' => "?><?= $key[0] ? '$category[' . $j . ']->' . $arrayfk2[array_search($column->name, $key2)][$column->name] : "'$stringe[$i]'" ?>,
        ]);
<?php endif;?>
<?php if($key[0] && $column->allowNull==false):?>
<?php $j++;?>
<?php endif;?>
<?php $i++;?>
<?php endforeach;?>
    }

    //Test Update
    public function <?= $modelClassName . 'Update' ?>(FunctionalTester $I)
    {
        $I->wantTo("Verify exception for Update");
<?php foreach ($tableSchema->columns as $column): ?>
<?php $key = $helper->isKey($keys, $column->name)?>
<?php if ($column->allowNull==false && !$column->isPrimaryKey && $column->type=='text'):?>
        $category = $I->grabRecord('app\models\<?=$modelClassName?>', array('<?=$column->name?>' => '<?=$string?>'));
<?php break;?>
<?php elseif ($column->allowNull==false && !$column->isPrimaryKey && $column->phpType=='integer'):?>
        $category = $I->grabRecord('app\models\<?=$modelClassName?>', array('<?=$column->name?>' => '<?=$int?>'));
<?php break;?>
<?php endif;?>
<?php endforeach;?>
<?php $i=0?>
<?php foreach ($tableSchema->columns as $column): ?>
<?php $key = $helper->isKey($keys, $column->name)?>
<?php if($key[0] && $column->allowNull==false):?>
        $category2[<?=$i?>] = $I->grabRecord('app\models\<?=$key[1]?>', array());
<?php $i++?>
<?php endif;?>
<?php endforeach;?>
<?php foreach ($tableSchema->columns as $column): ?>
<?php if ($column->isPrimaryKey):?>
        $id = $category-><?=$column->name?>;
        $I->sendAjaxPostRequest(['/<?=$tableSchema->fullName?>/update', 'id' => $id],[
<?php break;?>
<?php endif;?>
<?php endforeach;?>
<?php $i=0;?>
<?php $j=0;?>
<?php foreach ($tableSchema->columns as $column): ?>
<?php $vf = $helper->isMinOrMax($column->name,$modelRules)?>
<?php $inRange = $helper->isInRange($column->name, $modelRules)?>
<?php $vfcpf = $helper->cpfField($column->name,$modelRules)?>
<?php $vfemail = $helper->emailField($column->name,$modelRules)?>
<?php $key = $helper->isKey($keys, $column->name)?>
<?php if ($column->allowNull==false && $column->phpType=='integer' && !$column->isPrimaryKey):?>
<?php if($vfcpf):?>
<?php $inte[$i] = $helper->genCpfValid()?>
<?php elseif($inRange[0]):?>
<?php $inte[$i] = $inRange[1]?>
<?php elseif($vf[0]):?>
<?php $inte[$i] = $helper->genMinOrMax($vf[1],$vf[2],$column->phpType)?>
<?php elseif($key[0]):?>
            <?= "'{$modelClassName}[{$column->name}]' =>" ?> $category2[<?=$j?>]-><?=$arrayfk2[$j][$keys[$j][1]]?>,
<?php $j++?>
<?php else:?>
<?php $inte[$i] = $int?>
<?php endif;?>
<?php if(!$key[0]):?>
            <?= "'{$modelClassName}[{$column->name}]' => '$inte[$i]'" ?>,
<?php endif;?>
<?php elseif ($column->allowNull==false && $column->type=='date' && !$column->isPrimaryKey):?>
            <?= "'{$modelClassName}[{$column->name}]' => '$date'" ?>,
<?php elseif ($column->allowNull==false && $column->type=='text' && !$column->isPrimaryKey):?>
<?php if($vfcpf):?>
<?php $stringe[$i] = $helper->genCpfValid()?>
<?php elseif($vf[0]):?>
<?php $stringe[$i] = $helper->genMinOrMax($vf[1],$vf[2],$column->phpType)?>
<?php elseif($inRange[0]):?>
<?php $stringe[$i] = $inRange[1]?>
<?php elseif($vfemail):?>
<?php $stringe[$i] = $email?>
<?php elseif($key[0]):?>
            <?= "'{$modelClassName}[{$column->name}]' =>" ?> $category2[<?=$j?>]->id,
<?php $j++?>
<?php else:?>
<?php $stringe[$i] = $string2?>
<?php endif;?>
<?php if(!$key[0]):?>
            <?= "'{$modelClassName}[{$column->name}]' => '$stringe[$i]'" ?>,
<?php endif;?>
<?php elseif ($column->allowNull==false && !$column->isPrimaryKey):?>
            <?= "'{$modelClassName}[{$column->name}]' => ''" ?>,
<?php endif;?>
<?php $i++;?>
<?php endforeach;?>
        ]);
<?php $i=0;?>
<?php foreach ($tableSchema->columns as $column): ?>
<?php $vf = $helper->isMinOrMax($column->name,$modelRules)?>
<?php $vfcpf = $helper->cpfField($column->name,$modelRules)?>
<?php $key = $helper->isKey($keys, $column->name)?>
<?php if ($column->allowNull==false && $column->phpType=='integer' && !$column->isPrimaryKey && !$key[0]):?>
        $I->seeRecord('app\models\<?= $tableSchema->fullName ?>', [
            <?= "'$column->name' => '$inte[$i]'" ?>,
        ]);
<?php elseif ($column->allowNull==false && $column->type=='date' && !$column->isPrimaryKey && !$key[0]):?>
        $I->seeRecord('app\models\<?= $tableSchema->fullName ?>', [
            <?= "'$column->name' => '$date'" ?>,
        ]);
<?php elseif ($column->allowNull==false && $column->type=='text' && !$column->isPrimaryKey && !$key[0]):?>
        $I->seeRecord('app\models\<?= $tableSchema->fullName ?>', [
            <?= "'$column->name' => '$stringe[$i]'" ?>,
        ]);
<?php endif;?>
<?php $i++;?>
<?php endforeach;?>
    }

}
<?php $helper->testerExecOrder()?>