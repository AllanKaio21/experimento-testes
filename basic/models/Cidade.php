<?php

namespace app\models;

use Yii;

/**
 * This is the base model class for table "cidade".
 *
 * @property integer $id
 * @property string $nome
 * @property string $uf
 * @property integer $estado_id
 *
 * @property \app\models\Estado $estado
 * @property \app\models\Imovel[] $imovels
 * @property \app\models\Pessoa[] $pessoas
 */
class Cidade extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nome'], 'required'],
            [['nome'], 'string'],
            [['estado_id'], 'integer'],
            [['uf'], 'string', 'max' => 2],
            [['estado_id'], 'exist', 'skipOnError' => true, 'targetClass' => Estado::className(), 'targetAttribute' => ['estado_id' => 'id']],        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cidade';
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
    public function getEstado()
    {
        return $this->hasOne(\app\models\Estado::className(), ['id' => 'estado_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImovels()
    {
        return $this->hasMany(\app\models\Imovel::className(), ['cidade_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPessoas()
    {
        return $this->hasMany(\app\models\Pessoa::className(), ['cidade_id' => 'id']);
    }
}
