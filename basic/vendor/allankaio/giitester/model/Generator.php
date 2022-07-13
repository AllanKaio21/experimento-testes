<?php

namespace allankaio\giitester\model;

use Yii;
use yii\db\ActiveQuery;
use yii\gii\CodeFile;
use yii\helpers\Json;
use yii\helpers\Inflector;
use allankaio\giitester\Informations;

class Generator extends \allankaio\giitester\BaseGenerator {

    public $nsModel = 'app\models';
    public $nameAttribute = 'name, title, username';
    public $hiddenColumns = 'id, lock';
    public $skippedColumns = 'created_at, updated_at, created_by, updated_by, deleted_at, deleted_by, created, modified, deleted';
    public $generateQuery = true;
    public $queryNs = 'app\models';
    public $queryClass;
    public $queryBaseClass = 'yii\db\ActiveQuery';
    public $generateLabelsFromComments = false;
    public $useTablePrefix = false;
    public $generateRelations = self::RELATIONS_ALL;
    public $generateAttributeHints = false;
    public $generateMigrations = false;
    public $optimisticLock = 'lock';
    public $createdAt = 'created_at';
    public $updatedAt = 'updated_at';
    public $timestampValue = "new \\yii\\db\\Expression('NOW()')";
    public $createdBy = 'created_by';
    public $updatedBy = 'updated_by';
    public $blameableValue = '\Yii::$app->user->id';
    public $UUIDColumn = 'id';
    public $deletedBy = 'deleted_by';
    public $deletedAt = 'deleted_at';
    public $generateBaseOnly = false;
    public $relNxN;

    public function getName() {
        return 'EasYii Gii (Model)';
    }

    public function getDescription() {
        return 'This generator generates model operations for the database.';
    }

    public function rules() {
        return array_merge(parent::rules(), [
            [['db', 'nsModel', 'tableName', 'modelClass', 'queryNs','relNxN'], 'filter', 'filter' => 'trim'],
            [['tableName', 'db'], 'required'],
            [['tableName'], 'match', 'pattern' => '/^(\w+\.)?([\w\*]+)$/', 'message' => 'Only word characters, and optionally an asterisk and/or a dot are allowed.'],
            [['tableName'], 'validateTableName'],
            [['nsModel', 'baseModelClass', 'queryNs', 'queryBaseClass'], 'match', 'pattern' => '/^[\w\\\\]+$/', 'message' => 'Only word characters and backslashes are allowed.'],
            [['modelClass', 'baseModelClass', 'db'], 'match', 'pattern' => '/^[\w\\\\]*$/', 'message' => 'Only word characters and backslashes are allowed.'],
            [['queryBaseClass', 'queryClass'], 'validateClass', 'params' => ['extends' => ActiveQuery::className()]],
            [['db'], 'validateDb'],
            [['enableI18N', 'generateQuery', 'generateLabelsFromComments',
                'useTablePrefix', 'generateMigrations', 'generateAttributeHints', 'generateBaseOnly'], 'boolean'],
            [['generateRelations'], 'in', 'range' => [self::RELATIONS_NONE, self::RELATIONS_ALL, self::RELATIONS_ALL_INVERSE]],
            [['messageCategory'], 'validateMessageCategory', 'skipOnEmpty' => false],

            [['skippedColumns', 'skippedRelations',
                'blameableValue', 'nameAttribute', 'hiddenColumns', 'timestampValue',
                'optimisticLock', 'createdAt', 'updatedAt', 'createdBy', 'updatedBy',
                'blameableValue', 'UUIDColumn', 'deletedBy', 'deletedAt'], 'safe'],
        ]);
    }

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'db' => 'Database Connection ID',
            'modelClass' => 'Model Class',
            'timestampValue' => 'Value',
            'blameableValue' => 'Value',
            'generateQuery' => 'Generate ActiveQuery',
            'queryNs' => 'ActiveQuery Namespace',
            'queryClass' => 'ActiveQuery Class',
            'nsModel' => 'Namespace',
            'baseModelClass' => 'Base Class',
            'nsSearchModel' => 'Search Model Namespace',
            'UUIDColumn' => 'UUID Column',
            'viewPath' => 'View Path',
            'relNxN'=>'NxM Relations',
            'generateBaseOnly' => 'Generate Base Model Only',
        ]);
    }

    public function hints() {
        return array_merge(parent::hints(), [
            'db' => 'This is the ID of the DB application component.',
            'tableName' => 'This is the name of the DB table that the new ActiveRecord class is associated with, e.g. <code>post</code>.
                The table name may consist of the DB schema part if needed, e.g. <code>public.post</code>.
                The table name may end with asterisk to match multiple table names, e.g. <code>tbl_*</code>
                will match tables who name starts with <code>tbl_</code>. In this case, multiple ActiveRecord classes
                will be generated, one for each matching table name; and the class names will be generated from
                the matching characters. For example, table <code>tbl_post</code> will generate <code>Post</code>
                class.',
            'relNxN'=>'If you want to generate NxM relationships with Select2 and TabularForm, enter the name of the tables by separating them with a comma.',
            'nameAttribute' => 'This is the (set) of name column that you use to show as label, '
            . 'separated by comma (,) for multiple table(asterisk on Table Name).',
            'skippedColumns' => 'Fill this field with the column name that you dont want to generate form & labels for the table. 
                You can fill multiple columns, separated by comma (,). You may specify the column name
                although "Table Name" ends with asterisk, in which case all columns will not be generated at all models & CRUD.',
            'skippedRelations' => 'Fill this field with the relation name that you dont want to generate CRUD for the table.
                You can fill multiple relations, separated by comma (,). You do not need to specify the class name
                if "Table Name" ends with asterisk, in which case all relations will be generated.',
            'hiddenColumns' => 'Fill this field with the column name that you want to generate form with the hidden field of the table. 
                You can fill multiple columns, separated by comma (,). You may specify the column name
                although "Table Name" ends with asterisk, in which case all columns will be generated with hidden field at the forms',
            'nsModel' => 'This is the namespace of the ActiveRecord class to be generated, e.g., <code>app\models</code>',
            'modelClass' => 'This is the name of the Model class to be generated. The class name should not contain
                the namespace part as it is specified in "Model Namespace". You do not need to specify the class name
                if "Table Name" ends with asterisk, in which case multiple ActiveRecord classes will be generated.',
            'baseModelClass' => 'This is the base class of the new ActiveRecord class. It should be a fully qualified namespaced class name.',
            'nsSearchModel' => 'This is the namespace of the search model class to be generated, e.g., <code>app\models</code>',
            'generateQuery' => 'This indicates whether to generate ActiveQuery for the ActiveRecord class.',
            'generateLabelsFromComments' => 'This indicates whether the generator should generate attribute labels
                by using the comments of the corresponding DB columns.',
            'useTablePrefix' => 'This indicates whether the table name returned by the generated ActiveRecord class
                should consider the <code>tablePrefix</code> setting of the DB connection. For example, if the
                table name is <code>tbl_post</code> and <code>tablePrefix=tbl_</code>, the ActiveRecord class
                will return the table name as <code>{{%post}}</code>.',
            'generateRelations' => 'This indicates whether the generator should generate relations based on
                foreign key constraints it detects in the database. Note that if your database contains too many tables,
                you may want to uncheck this option to accelerate the code generation process.',
            'generateAttributeHints' => 'This indicates whether the generator generate attribute hints on the extended models',
            'generateMigrations' => 'This indicates whether the generator should generate migrations based on
                table structure.',
            'optimisticLock' => 'This indicates whether the generator should generate optimistic lock feature for Model. '
            . 'Enter this field with optimistic lock column name. '
            . 'Empty this field if you want to disable this feature.',
            'createdAt' => 'This indicates whether the generator should generate Timestamp Behaviors feature for Model. '
            . 'Enter this field with Created At column name. '
            . 'Empty "Created At" & "Updated At" field if you want to disable this feature.',
            'updatedAt' => 'This indicates whether the generator should generate Timestamp Behaviors feature for Model. '
            . 'Enter this field with Updated At column name. '
            . 'Empty "Created At" & "Updated At" field if you want to disable this feature.',
            'timestampValue' => 'This will generate the </code>value</code> configuration entry for Timestamp Behaviors.  e.g., <code>new Expression(\'NOW()\')</code>',
            'createdBy' => 'This indicates whether the generator should generate Blameable Behaviors feature for Model. '
            . 'Enter this field with Created By column name. '
            . 'Empty "Created By" & "Updated By" field if you want to disable this feature.',
            'updatedBy' => 'This indicates whether the generator should generate Blameable Behaviors feature for Model. '
            . 'Enter this field with Updated By column name. '
            . 'Empty "Created By" & "Updated By" field if you want to disable this feature.',
            'blameableValue' => 'This will generate the </code>value</code> configuration entry for Blameable Behaviors.  e.g., <code>new Expression(\'NOW()\')</code>',
            'UUIDColumn' => 'This indicates whether the generator should generate UUID Behaviors feature for Model. '
            . 'Enter this field with UUID column name. '
            . 'Empty "UUID Column" field if you want to disable this feature.',
            'deletedBy' => 'This indicates whether the generator should generate Soft Delete feature for Model. '
            . 'Enter this field with Deleted By column name. '
            . 'Empty "Deleted By" & "Deleted At" field if you want to disable this feature.',
            'deletedAt' => 'This indicates whether the generator should generate Soft Delete feature for Model. '
            . 'Enter this field with Updated By column name. '
            . 'Empty "Deleted By" & "Deleted At" field if you want to disable this feature.',
            'nsModel' => 'This is the namespace of the ActiveRecord class to be generated, e.g., <code>app\models</code>',
            'viewPath' => 'Specify the directory for storing the view scripts for the controller. You may use path alias here, e.g.,
                <code>/var/www/basic/controllers/views/post</code>, <code>@app/views/post</code>. If not set, it will default
                to <code>@app/views/ControllerID</code>',
            'modelClass' => 'This is the name of the Model class to be generated. The class name should not contain
                the namespace part as it is specified in "Model Namespace". You do not need to specify the class name
                if "Table Name" ends with asterisk, in which case multiple ActiveRecord classes will be generated.',
            'queryNs' => 'This is the namespace of the ActiveQuery class to be generated, e.g., <code>app\models</code>',
            'queryClass' => 'This is the name of the ActiveQuery class to be generated. The class name should not contain
                the namespace part as it is specified in "ActiveQuery Namespace". You do not need to specify the class name
                if "Table Name" ends with asterisk, in which case multiple ActiveQuery classes will be generated.',
            'queryBaseClass' => 'This is the base class of the new ActiveQuery class. It should be a fully qualified namespaced class name.',
            'generateBaseOnly' => 'This indicates whether the generator should generate extended model(where you write your code) or not. '
            . 'You usually re-generate models when you make changes on your database.'
        ]);
    }

    public function stickyAttributes() {
        return array_merge(parent::stickyAttributes(), [
            'db',
            'nameAttribute',
            'nsModel',
            'nsSearchModel',
            'baseModelClass',
            'queryNs',
            'queryBaseClass',
            'optimisticLock',
            'createdBy',
            'updatedBy',
            'deletedBy',
            'createdAt',
            'timestampValue',
            'updatedAt',
            'deletedAt',
            'blameableValue',
            'UUIDColumn',
        ]);
    }

    public function getVariablesNxN()
    {
        $inf = new Informations();
        $variables = [];
        $var = explode(';', $this->relNxN);
        if ($var != null) {
            foreach ($var as $i) {
                if($inf->getVariables($i, $this->tableName)) {
                    $variables [] = $i;
                }
            }
        }
        return $variables;
    }

    /**
     * @inheritdoc
     */
    public function requiredTemplates() {
        return ['model.php'];
    }

    public $isTree;

    /**
     * @inheritdoc
     */
    public function generate() {
        $files = [];
        $relations = $this->generateRelations();
        $db = $this->getDbConnection();
        $this->nameAttribute = ($this->nameAttribute) ? explode(',', str_replace(' ', '', $this->nameAttribute)) : [];
        $this->skippedColumns = ($this->skippedColumns) ? explode(',', str_replace(' ', '', $this->skippedColumns)) : [];
        $this->skippedRelations = ($this->skippedRelations) ? explode(',', str_replace(' ', '', $this->skippedRelations)) : [$this->skippedRelations];
        $this->skippedColumns = array_filter($this->skippedColumns);
        $this->skippedRelations = array_filter($this->skippedRelations);
        $gii = new \yii\gii\generators\model\Generator();
//        $this->skippedRelations = ($this->skippedRelations) ? explode(',', str_replace(' ', '', $this->skippedRelations)) : [];
        foreach ($this->getTableNames() as $tableName) {
            // preparation :
            if (strpos($this->tableName, '*') !== false) {
                $modelClassName = $this->generateClassName($tableName);
            } else {
                $modelClassName = (!empty($this->modelClass)) ? $this->modelClass : Inflector::id2camel($tableName, '_');
            }
            //$queryClassName = ($this->generateQuery) ? $this->generateQueryClassName($modelClassName) : false;
            $tableSchema = $db->getTableSchema($tableName);
            $this->modelClass = "{$this->nsModel}\\{$modelClassName}";
            $this->tableSchema = $tableSchema;
            $this->isTree = !array_diff(self::getTreeColumns(), $tableSchema->columnNames);
//            $this->controllerClass = $this->nsController . '\\' . $modelClassName . 'Controller';
            $params = [
                'tableName' => $tableName,
                'className' => $modelClassName,
                //'queryClassName' => $queryClassName,
                'tableSchema' => $tableSchema,
                'labels' => $gii->generateLabels($tableSchema),
                'rules' => $gii->generateRules($tableSchema),
                'relations' => isset($relations[$tableName]) ? $relations[$tableName] : [],
                'isTree' => $this->isTree
            ];
            // model :
            $files[] = new CodeFile(
                    Yii::getAlias('@' . str_replace('\\', '/', $this->nsModel)) . '/' . $modelClassName . '.php', $this->render('model.php', $params)
            );

            if (strpos($this->tableName, '*') !== false) {
                $this->modelClass = '';
//                $this->controllerClass = '';
            } else {
                $this->modelClass = $modelClassName;
//                $this->controllerClass = $modelClassName . 'Controller';
            }
        }
        $this->nameAttribute = (is_array($this->nameAttribute)) ? implode(', ', $this->nameAttribute) : '';
        $this->skippedColumns = (is_array($this->skippedColumns)) ? implode(', ', $this->skippedColumns) : '';
        $this->skippedRelations = (is_array($this->skippedRelations)) ? implode(', ', $this->skippedRelations) : '';

        return $files;
    }

    /**
     * Checks if any of the specified columns is auto incremental.
     * @param \yii\db\TableSchema $table the table schema
     * @param array $columns columns to check for autoIncrement property
     * @return boolean whether any of the specified columns is auto incremental.
     */
    protected function isColumnAutoIncremental($table, $columns) {
        foreach ($columns as $column) {
            if (isset($table->columns[$column]) && $table->columns[$column]->autoIncrement) {
                return true;
            }
        }

        return false;
    }

    public function relationships(){

    $out = [];
    if (isset($_POST['depdrop_parents'])) {
        $id = end($_POST['depdrop_parents']);
        $list = $this->tableSchema->foreignKeys;
        //$list = Catequisando::find()->where(['id_comunidade'=>$id,'status'=>1])->select(['id','nome'])->asArray()->all();
        $selected  = null;
        if ($id != null && count($list) > 0) {
            $selected = '';
            foreach ($list as $i => $account) {
                $out[] = ['id' => $account['id'], 'name' => $account['nome']];
            }
            // Shows how you can preselect a value
            echo Json::encode(['output' => $out, 'selected'=>$selected]);
            return;
        }
    }
    echo Json::encode(['output' => '', 'selected'=>'']);

    }

}
