<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\extensions\Mask;

/* @var $this yii\web\View */
/* @var $model app\models\Imovel */

$this->title = $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Imoveis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="imovel-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nome',
            [
                'attribute' => 'proprietario.'.\app\models\Pessoa::representingColumn(),
                'label' => 'Proprietário',
            ],
            [
                'attribute' => 'cep',
                'value' => function($model){
                    if ($model->cep){
                        return Mask::cpf($model->cep, "#####-###");
                    }
                    return null;
                },
            ],
            'rua',
            [
                'attribute' => 'cidade.'.\app\models\Cidade::representingColumn(),
                'label' => 'Cidade',
            ],
            [
                'attribute' => 'estado.'.\app\models\Estado::representingColumn(),
                'label' => 'Estado',
            ],
        ],
    ]) ?>

</div>
