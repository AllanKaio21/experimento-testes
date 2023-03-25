<?php

use kartik\widgets\DepDrop;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Pessoa */
/* @var $form yii\widgets\ActiveForm */
/* @var $estados \app\models\Estado[] */

$estados = \yii\helpers\ArrayHelper::map($estados, 'id', 'name');

?>

<div class="pessoa-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cpf')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'estado_id')->dropDownList($estados, ['selected'=>1]); ?>

    <?= $form->field($model, 'cidade_id')->widget(DepDrop::classname(), [
        'pluginOptions' => [
            'depends' => ['pessoa-estado_id'],
            'placeholder' => 'Select a cidade...',
            'url' => Url::to(['estado/cidades'])
        ]
    ]);
    ?>

    <?= $form->field($model, 'cep')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
