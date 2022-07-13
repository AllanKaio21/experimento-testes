<?php
$information = new \allankaio\giitester\Informations();
$newRules = $information->newRules(strtolower($className));
$dbName = $generator->getDbConnection()->driverName;
/**
 * This is the template for generating the model class of a specified table.
 */

/* @var $this yii\web\View */
/* @var $generator allankaio\giitester\crud\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $queryClassName string query class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */

echo "<?php\n";
?>

namespace <?= $generator->nsModel ?>;

use Yii;

/**
 * This is the base model class for table "<?= $generator->generateTableName($tableName) ?>".
 *
<?php foreach ($tableSchema->columns as $column): ?>
 * @property <?= "{$column->phpType} \${$column->name}\n" ?>
<?php endforeach; ?>
<?php if (!empty($relations)): ?>
 *
<?php foreach ($relations as $name => $relation): ?>
<?php if(!in_array($name, $generator->skippedRelations)): ?>
 * @property <?= '\\' . $generator->nsModel . '\\' . $relation[$generator::REL_CLASS] . ($relation[$generator::REL_IS_MULTIPLE] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>
 */
class <?= $className ?> extends <?= '\\' . ltrim($generator->baseModelClass, '\\') . "\n" ?>
{
<?php if($generator->getDbConnection()->driverName==='pgsql'): ?>
<?php if($generator->getVariablesNxN()!=null):?>

<?php foreach ($generator->getVariablesNxN() as $i):?>
    public $<?=lcfirst($generator->generateClassName($i))?> = [];
<?php endforeach;?>
<?php endif;?>

    public function behaviors(){
        return [
<?php if($generator->getVariablesNxN()!=null):?>
            [
                'class'=>\arogachev\ManyToMany\behaviors\ManyToManyBehavior::className(),
                'relations'=>[
<?php foreach ($generator->getVariablesNxN() as $i): ?>
<?php foreach ($information->getRelationsCRUD($i,$generator->tableName) as $j=>$line):?>
                    [
                        'name'=>'rel<?= $generator->generateClassName($line['table_rel']) ?>',
                        'editableAttribute'=>'<?=lcfirst($generator->generateClassName($i))?>',
                    ],
<?php endforeach; ?>
<?php endforeach; ?>
                ],
            ],
<?php endif; ?>
<?php foreach ($labels as $name => $label): ?>
<?php if(strtolower($label) === 'arquivo'):?>
            [
                'class' => '\yiidreamteam\upload\FileUploadBehavior',
                'attribute' => 'arquivo',
                'filePath' => '@webroot/uploads/<?= $className?>/[[pk]].[[extension]]',
                'fileUrl' => '/uploads/<?= $className?>/[[pk]].[[extension]]',
            ]
<?php endif;?>
<?php endforeach;?>
        ];
    }
<?php endif; ?>

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [<?= "\n            " . implode(",\n            ", $rules) . ","?>
<?php if($newRules!=NULL):?>
            <?= "\n            " . implode(",\n            ", $newRules) . ",\n"?>
<?php endif;?>
<?php if($generator->getDbConnection()->driverName==='pgsql'): ?>
<?php if(($information->getForeignKeysInfo(strtolower($className)) !=null) && $generator->getVariablesNxN()!=NULL):?>
<?php $rule = ''; ?>
<?php foreach ($generator->getVariablesNxN() as $i) { $rule =  $rule . "'".lcfirst($generator->generateClassName($i))."',"; } ?>
            [[<?= substr($rule,0,-1) ?>],\arogachev\ManyToMany\validators\ManyToManyValidator::className()]
<?php endif; ?>
<?php endif; ?>
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '<?= $generator->generateTableName($tableName) ?>';
    }

    /**
    * @inheritdoc
    */
    public static function representingColumn()
    {
<?php $out = implode($generator->tableSchema->primaryKey);?>
<?php foreach($generator->tableSchema->columns as $item):?>
<?php if($item->type==='text'):?>
<?php $out = $item->name;?>
<?php break?>
<?php endif;?>
<?php endforeach;?>
        return '<?= $out ?>';
    }
<?php if ($generator->db !== 'db'): ?>

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('<?= $generator->db ?>');
    }
<?php endif; ?>

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
<?php foreach ($labels as $name => $label): ?>
<?php if (!in_array($name, $generator->skippedColumns)): ?>
<?php if($dbName==='pgsql'):?>
<?php $out  = $information->labelsRules($tableName, $name)?>
<?php if($out!=NULL):?>
            <?="'$name' => '" . $out . "',\n" ?>
<?php else:?>
            <?= "'$name' => " . $generator->generateString($label) . ",\n" ?>
<?php endif;?>
<?php endif;?>
<?php endif; ?>
<?php endforeach; ?>
        ];
    }
<?php foreach ($relations as $name => $relation): ?>
<?php if(!in_array($name, $generator->skippedRelations)): ?>

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get<?= ucfirst($name) ?>()
    {
        <?= $relation[0] . "\n" ?>
    }
<?php endif; ?>
<?php endforeach; ?>
<?php if($generator->getDbConnection()->driverName==='pgsql'): ?>
<?php if(($information->getForeignKeysInfo(strtolower($className)) !=null) && ($generator->relNxN!=null)):?>
<?php foreach ($generator->getVariablesNxN() as $i):?>
<?php foreach ($information->getRelations($i) as $j=>$line): ?>
<?php if($line['table_rel']===lcfirst($className)):?>
    /**
    *  @return \yii\db\ActiveQuery
    */
    public function getRel<?=$generator->generateClassName($i)?>()
    {
        return $this->hasMany(<?=$generator->generateClassName($i)?>::className(),
        ['<?=$line['column_relation']?>' => '<?=$line['column_related']?>']);
    }

<?php endif;?>
<?php if($line['table_rel']!=lcfirst($className)):?>
    /**
    * @return \yii\db\ActiveQuery
    */
    public function getRel<?=$generator->generateClassName($line['table_rel'])?>()
    {
        return $this->hasMany(<?=$generator->generateClassName($line['table_rel'])?>::className(), ['<?=$line['column_related']?>' => '<?=$line['column_relation']?>'])
        ->via('rel<?=$generator->generateClassName($i)?>');
    }
<?php endif;?>
<?php endforeach;?>
<?php endforeach;?>
<?php endif; ?>
<?php endif; ?>
}
