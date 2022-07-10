<?php

namespace app\models;

use Yii;

/**
 * This is the base model class for table "estado".
 *
 * @property integer $id
 * @property string $nome
 * @property integer $uf
 *
 * @property \app\models\Cidade[] $cidades
 * @property \app\models\Imovel[] $imovels
 * @property \app\models\Pessoa[] $pessoas
 */
class Estado extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nome'], 'required'],
            [['nome'], 'string'],
            [['uf'], 'integer'],        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'estado';
    }

    /**
    * @inheritdoc
    */
    public static function representingColumn()
    {
        return 'nome';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCidades()
    {
        return $this->hasMany(\app\models\Cidade::className(), ['estado_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImovels()
    {
        return $this->hasMany(\app\models\Imovel::className(), ['estado_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPessoas()
    {
        return $this->hasMany(\app\models\Pessoa::className(), ['estado_id' => 'id']);
    }
}
