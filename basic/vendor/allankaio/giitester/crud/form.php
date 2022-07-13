<?php
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator yii\gii\generators\crud\Generator */
?>
<?php
    echo $form->field($generator, 'tableName');
    echo $form->field($generator,'relNxN');
    echo $form->field($generator, 'generateSearchModel')->checkbox();
    echo $form->field($generator, 'indexWidgetType')->dropDownList([
        'grid' => 'GridView',
        'list' => 'ListView',
    ]);