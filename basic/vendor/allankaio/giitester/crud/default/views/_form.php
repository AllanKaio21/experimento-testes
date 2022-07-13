<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

$information = new \allankaio\giitester\Informations();

/* @var $this yii\web\View */
/* @var $generator \allankaio\giitester\crud\Generator */
/* @var $relations array */
$tableSchema = $generator->getTableSchema();
$fk = $generator->generateFK($tableSchema);
echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form">

    <?= "<?php " ?>$form = ActiveForm::begin(); ?>

    <?= "<?= " ?>$form->errorSummary($model); ?>

<?php
foreach ($tableSchema->getColumnNames() as $attribute) {
    if (!in_array($attribute, $generator->skippedColumns)) {
        echo "    <?= " . $generator->generateActiveField($attribute, $fk) . " ?>\n\n";
    }
}

$forms = "";
foreach ($relations as $name => $rel) {
    $relID = Inflector::camel2id($rel[$generator::FK_FIELD_NAME]);
    if ($rel[$generator::REL_IS_MULTIPLE] && isset($rel[$generator::REL_TABLE]) && !in_array($name, $generator->skippedRelations)) {
        $forms .= "        [\n".
            "            'label' => '<i class=\"glyphicon glyphicon-book\"></i> ' . Html::encode(".$generator->generateString($rel[$generator::REL_CLASS])."),\n".
            "            'content' => \$this->render('_form".$rel[$generator::FK_FIELD_NAME]."', [\n".
            "                'row' => \\yii\\helpers\\ArrayHelper::toArray(\$model->$name),\n".
            "            ]),\n".
            "        ],\n";
    }else if(isset($rel[$generator::REL_IS_MASTER]) && !$rel[$generator::REL_IS_MASTER]){
        $forms .= "        [\n".
            "            'label' => '<i class=\"glyphicon glyphicon-book\"></i> ' . Html::encode(".$generator->generateString($rel[$generator::REL_CLASS])."),\n".
            "            'content' => \$this->render('_form".$rel[$generator::FK_FIELD_NAME]."', [\n" .
            "                'form' => \$form,\n".
            "                '".$rel[$generator::REL_CLASS]."' => is_null(\$model->$name) ? new ".$generator->nsModel."\\".$rel[$generator::REL_CLASS]."() : \$model->$name,\n".
            "            ]),\n".
            "        ],\n";
    }
}
?>
<?php if($generator->getDbConnection()->driverName==='pgsql'): ?>
<?php if($generator->getVariablesNxN()!=null):?>
<?php foreach ($generator->getVariablesNxN() as $i):?>
<?php foreach ($information->getRelationsCRUD($i,$generator->tableName) as $j=>$line):?>
    <?= "<?php \n" ?>
        $data=\yii\helpers\ArrayHelper::map(\app\models\<?= $generator->generateClassName($line['table_rel']) ?>::find()->orderBy(\app\models\<?= $generator->generateClassName($line['table_rel']) ?>::representingColumn())->all(),'<?=$line['column_related']?>',\app\models\<?= $generator->generateClassName($line['table_rel']) ?>::representingColumn());
        echo $form->field($model, '<?=lcfirst($generator->generateClassName($i))?>')->widget(\kartik\widgets\Select2::classname(), [
            'name' => '<?=lcfirst($generator->generateClassName($i))?>',
            'data' => $data,
            'options' => ['placeholder' => 'Select...',
                'multiple'=> true,
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
    <?= "?>" ?>
<?php endforeach;?>
<?php endforeach;?>
<?php endif; ?>
<?php endif; ?>


    <div class="form-group">
<?php if($generator->saveAsNew): ?>
<?= "    <?php if(Yii::\$app->controller->action->id != 'save-as-new'): ?>\n" ?>
<?= "        <?= " ?>Html::submitButton($model->isNewRecord ? <?= $generator->generateString('Create') ?> : <?= $generator->generateString('Update') ?>, ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
<?= "    <?php endif; ?>\n" ?>
<?= "    <?php if(Yii::\$app->controller->action->id != 'create'): ?>\n" ?>
<?= "        <?= " ?>Html::submitButton(<?=$generator->generateString('Save As New')?>, ['class' => 'btn btn-info', 'value' => '1', 'name' => '_asnew']) ?>
<?= "    <?php endif; ?>\n" ?>
<?php else: ?>
<?= "        <?= " ?>Html::submitButton($model->isNewRecord ? <?= $generator->generateString('Create') ?> : <?= $generator->generateString('Update') ?>, ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
<?php endif; ?>
<?php if ($generator->cancelable): ?>
        <?= "<?= " ?>Html::a(Yii::t('app', 'Cancel'), Yii::$app->request->referrer , ['class'=> 'btn btn-danger']) ?>
<?php endif; ?>
    </div>

    <?= "<?php " ?>ActiveForm::end(); ?>

</div>
