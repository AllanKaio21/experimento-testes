<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Imovel */

$this->title = 'Update Imovel: ' . $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Imoveis', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nome, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="imovel-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
