<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Imoveis';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="imovel-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Imovel', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'nome',
            [
                'attribute' => 'proprietario.'.\app\models\Pessoa::representingColumn(),
                'label' => 'Proprietário',
            ],
            'cep',
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
