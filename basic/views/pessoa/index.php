<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pessoas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pessoa-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Pessoa', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
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

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
