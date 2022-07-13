<?php

use yii\helpers\StringHelper;

$dbName = $generator->getDbConnection()->driverName;
$myModel = new allankaio\giitester\crud\Generator;
$information = new \allankaio\giitester\Informations();
$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $searchModelAlias = $searchModelClass . 'Search';
}
$pks = $generator->tableSchema->primaryKey;
$fk = $generator->tableSchema->foreignKeys;
$urlParams = $generator->generateUrlParams();
$actionParams = $generator->generateActionParams();
$actionParamComments = $generator->generateActionParamComments();
$skippedRelations = array_map(function($value){
    return "'$value'";
},$generator->skippedRelations);
echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use Yii;
use <?= ltrim($generator->modelClass, '\\') ?>;
<?php if (!empty($generator->searchModelClass)): ?>
use <?= ltrim($generator->searchModelClass, '\\') . (isset($searchModelAlias) ? " as $searchModelAlias" : "") ?>;
<?php else : ?>
use yii\data\ActiveDataProvider;
<?php endif; ?>
use <?= ltrim($generator->baseControllerClass, '\\') ?>;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
* <?= $controllerClass ?> implements the CRUD actions for <?= $modelClass ?> model.
*/
class <?= $controllerClass ?> extends <?= StringHelper::basename($generator->baseControllerClass) . "\n" ?>
{
    /**
    * @return array
    */
    public function behaviors(){
        return [
            'ghost-access'=> [
                'class' => 'webvimark\modules\UserManagement\components\GhostAccessControl',
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
    * Lists all <?= $modelClass ?> models.
    * @return mixed
    */
    public function actionIndex(){
<?php if (!empty($generator->searchModelClass)): ?>
        $searchModel = new <?= isset($searchModelAlias) ? $searchModelAlias : $searchModelClass ?>();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
<?php else : ?>
        $dataProvider = new ActiveDataProvider([
            'query' => <?= $modelClass ?>::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
<?php endif; ?>
    }

    /**
    * Displays a single <?= $modelClass ?> model.
    * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
    * @return mixed
    */
    public function actionView(<?= $actionParams ?>){
        return $this->render('view', [
            'model' => $this->findModel(<?= $actionParams ?>),
        ]);
    }

    /**
    * Creates a new <?= $modelClass ?> model.
    * If creation is successful, the browser will be redirected to the 'view' page.
    * @return mixed
    */
    public function actionCreate(){
        $model = new <?= $modelClass ?>();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Item Incluído com sucesso.");
            return $this->redirect(['view', <?= $urlParams ?>]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
    * Updates an existing <?= $modelClass ?> model.
    * If update is successful, the browser will be redirected to the 'view' page.
    * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
    * @return mixed
    */
    public function actionUpdate(<?= $actionParams ?>){
        $model = $this->findModel(<?= $actionParams ?>);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Item atualizado com sucesso.");
            return $this->redirect(['view', <?= $urlParams ?>]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
    * Deletes an existing <?= $modelClass ?> model.
    * If deletion is successful, the browser will be redirected to the 'index' page.
    * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
    * @return mixed
    */
    public function actionDelete(<?= $actionParams ?>){
<?php if($dbName === 'pgsql'):?>
<?php $out= $information->getForeignKeysInfo(strtolower($modelClass));?>
<?php if($out != null): ?>
<?php foreach ($out as $line): ?>
<?php if($line['actiondelete']==='RESTRICT' || $line['actiondelete']==='NO ACTION'): ?>
        if((\app\models\<?= $myModel->generateClassName($line['tableref']) ?>::find()->where(['<?= $line['columnref'] ?>'=><?= $actionParams ?>])->all())){
            Yii::$app->session->setFlash('danger', "<?= $modelClass?> sendo usado em algum(a) <?= ucfirst($line['tableref']) ?>.");
            return $this->redirect(['index']);
        }
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>
<?php endif;  ?>
        $model=$this->findModel(<?= $actionParams ?>);
        $model->delete();
        Yii::$app->session->setFlash('success', "Item removido com sucesso.");
        return $this->redirect(['index']);
    }

    /**
    * Finds the <?= $modelClass ?> model based on its primary key value.
    * If the model is not found, a 404 HTTP exception will be thrown.
    * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
    * @return <?=                   $modelClass ?> the loaded model
    * @throws NotFoundHttpException if the model cannot be found
    */
    protected function findModel(<?= $actionParams ?>){
<?php
if (count($pks) === 1) {
    $condition = '$id';
} else {
    $condition = [];
    foreach ($pks as $pk) {
        $condition[] = "'$pk' => \$$pk";
    }
    $condition = '[' . implode(', ', $condition) . ']';
}
?>
        if (($model = <?= $modelClass ?>::findOne(<?= $condition ?>)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(<?= $generator->generateString('The requested page does not exist.')?>);
        }
    }
}