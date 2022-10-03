<?php
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator yii\gii\generators\tests\Generator */
$helper = new \allankaio\giitester\helpers();

?>
<?php
    echo $form->field($generator, 'tableName');
    $helper->testerExecOrder();