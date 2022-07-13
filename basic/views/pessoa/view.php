<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Pessoa */

$this->title = $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Pessoas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pessoa-view">

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
                'attribute' => 'cpf',
                'value' => function($model){
                    if ($model->cpf){
                        return \app\extensions\Mask::cpf(str_pad($model->cpf, 11, 0, STR_PAD_LEFT), "###.###.###-##");
                    }
                    return null;
                },
            ],
            [
                'attribute' => 'cep',
                'value' => function($model){
                    if ($model->cep){
                        return \app\extensions\Mask::cpf($model->cep, "#####-###");
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

    <hr>
    <h2>Imóveis</h2>

    <?= GridView::widget([
        'dataProvider' => $imoveis,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'nome',
            [
                'attribute' => 'cep',
                'value' => function($model){
                    if ($model->cep){
                        return \app\extensions\Mask::cpf($model->cep, "#####-###");
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
    ]); ?>
</div>
