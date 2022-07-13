<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\depdrop\DepDrop;
use kartik\money\MaskMoney;

/* @var $this yii\web\View */
/* @var $model app\models\Imovel */
/* @var $form yii\widgets\ActiveForm */
/* @var $estados \app\models\Estado[] */

//$estados = \yii\helpers\ArrayHelper::map($estados, 'id', 'name');
$estados = [
    ['id' => '123', 'name' => 'aaa', 'class' => 'x'],
    ['id' => '124', 'name' => 'bbb', 'class' => 'x'],
    ['id' => '345', 'name' => 'ccc', 'class' => 'y'],
];
?>

<div class="imovel-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cep')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'estado_id')->dropDownList($estados); ?>

    <?= $form->field($model, 'cidade_id')->widget(DepDrop::classname(), [
        'type' => DepDrop::TYPE_SELECT2,
        'pluginOptions' => [
            'depends' => ['imovel-estado_id'],
            'placeholder' => 'Select a cidade...',
            'url' => Url::to(['estado/cidades'])
        ]
    ]);
    ?>

    <?= $form->field($model, 'pessoa_id')->widget(DepDrop::classname(), [
        'type' => DepDrop::TYPE_SELECT2,
        'pluginOptions' => [
            'depends' => ['imovel-cidade_id'],
            'placeholder' => 'Select a cidade...',
            'url' => Url::to(['cidade/proprietarios'])
        ]
    ]);
    ?>

    <?= $form->field($model, 'rua')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
