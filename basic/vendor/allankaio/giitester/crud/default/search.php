<?php

use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator \allankaio\giitester\model\Generator */

$modelClass = StringHelper::basename($generator->modelClass);

if ($modelClass === $generator->searchModelClass) {
    $modelAlias = $modelClass . 'Model';
}
$rules = $generator->generateSearchRules();
$labels = $generator->generateSearchLabels();
$searchAttributes = $generator->getSearchAttributes();
$searchConditions = $generator->generateSearchConditions();

echo "<?php\n";
?>

namespace <?= $generator->nsSearchModel ?>;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use <?= ltrim($generator->nsModel . '\\' . $modelClass, '\\') . (isset($modelAlias) ? " as $modelAlias" : "") ?>;

/**
 * <?= $generator->searchModelClass ?> represents the model behind the search form about `<?= $generator->modelClass ?>`.
 */
 class <?= StringHelper::basename($generator->searchModelClass) ?> extends <?= isset($modelAlias) ? $modelAlias : $modelClass ?>

{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            <?= implode(",\n            ", $rules) ?>,
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = <?= isset($modelAlias) ? $modelAlias : $modelClass ?>::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes'=>[
<?php foreach ($generator->tableSchema->foreignKeys as $foreignKey=>$key):?>
<?php if (key($key)==0):?>
<?php $attr = $key['0'];?>
<?php next($key);?>
<?php $nome = key($key);?>
<?php endif;?>
                '<?=$nome?>'=>[
                    'asc'=>['<?= $attr?>'=>SORT_ASC],
                    'desc'=>['<?= $attr?>'=>SORT_DESC],
                ],
<?php endforeach;?>
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        <?= implode("\n        ", $searchConditions) ?>

        return $dataProvider;
    }
}
