<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

$urlParams = $generator->generateUrlParams();
$information = new \allankaio\giitester\Informations();
$tableSchema = $generator->getTableSchema();
$pk = empty($tableSchema->primaryKey) ? $tableSchema->getColumnNames()[0] : $tableSchema->primaryKey[0];
$fk = $generator->generateFK($tableSchema);
echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use yii\helpers\Url;

$this->title = $model->{$model::representingColumn()};
$this->params['breadcrumbs'][] = ['label' => <?= ($generator->pluralize) ? $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) : $generator->generateString(Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-view">

    <h1><?= "<?= " ?>Html::encode($this->title) ?></h1>

    <p>
        <?= "<?= " ?>Html::a(<?= $generator->generateString('Update') ?>, ['update', <?= $urlParams ?>], ['class' => 'btn btn-primary']) ?>
        <?= "<?php".PHP_EOL ?>
            if (webvimark\modules\UserManagement\models\User::hasPermission("deletar")) {
        ?>
        <?= "<?= " ?>Html::a(<?= $generator->generateString('Delete') ?>, ['delete', <?= $urlParams ?>], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => <?= $generator->generateString('Are you sure you want to delete this item?') ?>,
                'method' => 'post',
            ],
        ]) ?>
        <?= "<?php"?>
            }
        ?>
    </p>


    <div class="row">
<?= "<?php \n" ?>
    $gridColumn = [
<?php 
if ($tableSchema === false) {
    foreach ($generator->getColumnNames() as $name) {
        if (++$count < 6) {
            echo "            '" . $name . "',\n";
        } else {
            echo "            // '" . $name . "',\n";
        }
    }
} else{
    foreach($tableSchema->getColumnNames() as $attribute){
        if(!in_array($attribute, $generator->skippedColumns)) {
            echo "        ".$generator->generateDetailViewField($attribute,$fk, $tableSchema);

        }
    }
}?>
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]); 
?>

<?= '<?php'?>
<?php $relations = explode(";",$generator->relNxN)?>
<?php foreach ($information->getForeignKeysInfo($generator->tableName) as $item): ?>
    $query = app\models\<?= $generator->generateClassName($item['tableref'])?>::find()->where(['<?=$item['columnref']?>'=> $model-><?=$item['columnfk']?>])->orderBy('id')->indexBy('id');
    $dataProvider = new yii\data\ActiveDataProvider([
        'query' => $query,
    ]);

    $form = kartik\widgets\ActiveForm::begin(['action'=>Url::to(['<?= Inflector::camel2id(StringHelper::basename($item['tableref'])) ?>/batchupdate','id'=>$model-><?= $pk ?>,'return'=>'<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>'])]);

    echo kartik\builder\TabularForm::widget([
        'dataProvider'=>$dataProvider,
        'form'=>$form,
        'checkboxColumn'=>false,
        'actionColumn'=>[
            'template' => '{view} {update} {delete}',
            'urlCreator' => function($action, $model, $key, $index) {
            return Url::to(['/'.$model::tablename().'/'.$action,'id'=>$key]);
           },
        ],
        'attributes'=>[
<?php foreach ($information->getColumns($item['tableref']) as $column=>$name): ?>
<?php if($information->labelsRules($item['tableref'],$name['column_name'])==NULL):?>
<?php $label = $name['column_name'] ?>
<?php else:?>
<?php $label = $information->labelsRules($item['tableref'],$name['column_name']) ?>
<?php endif;?>
<?php if(in_array($item['tableref'],$relations)):?>
<?php if($name['tipo']=='text'):?>
        '<?=$name['column_name']?>'=>[
        'type'=>kartik\builder\TabularForm::INPUT_TEXT,
        'columnOptions'=>['hAlign'=>GridView::ALIGN_RIGHT, 'width'=>'90px']
        ],
<?php elseif ($name['tipo']=='date'):?>
        '<?=$name['column_name']?>'=>[
            'type' => kartik\builder\TabularForm::INPUT_WIDGET,
            'widgetClass'=>\kartik\widgets\DatePicker::classname(),
            'options'=>[
                'pluginOptions'=>[
                    'format'=>'dd/mm/yyyy',
                    'todayHighlight'=>true,
                    'autoclose'=>true
                ]
            ],
            'columnOptions'=>['width'=>'200px']
        ],
<?php else: ?>
        '<?=$name['column_name']?>'=>[
        'type'=>kartik\builder\TabularForm::INPUT_STATIC,
        'columnOptions'=>['hAlign'=>GridView::ALIGN_RIGHT, 'width'=>'90px']
        ],
<?php endif;?>
<?php else:?>
        '<?=$name['column_name']?>'=>[
        'type'=>kartik\builder\TabularForm::INPUT_STATIC,
        'columnOptions'=>['hAlign'=>GridView::ALIGN_RIGHT, 'width'=>'90px']
        ],
<?php endif;?>
<?php endforeach;?>
        ],
        'gridSettings'=>[
            'floatHeader'=>true,
            'panel'=>[
                'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i><?= $generator->generateClassName($item['tableref']) ?></h3>',
                'type' => GridView::TYPE_PRIMARY,
<?php if(in_array($item['tableref'],$relations)):?>
                'after'=>
                    Html::a('<i class="glyphicon glyphicon-plus"></i> Add', Url::to(['<?= Inflector::camel2id(StringHelper::basename($item['tableref'])) ?>/create','id<?= $generator->tableName?>'=>$model-><?= $pk?>]), ['class'=>'btn btn-success']) . ' ' .
    Html::submitButton('<i class="glyphicon glyphicon-floppy-disk"></i> Save', ['class'=>'btn btn-primary'])
<?php endif;?>
            ]
        ]
    ]);

    kartik\widgets\ActiveForm::end();

<?php endforeach;?>
<?= '?>' ?>

    </div>
</div>
