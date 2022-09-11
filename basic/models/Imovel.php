<?php

namespace app\models;

use Yii;

/**
 * This is the base model class for table "imovel".
 *
 * @property integer $id
 * @property string $nome
 * @property integer $pessoa_id
 * @property string $cep
 * @property string $rua
 * @property integer $cidade_id
 * @property integer $estado_id
 * @property string $complemento
 * @property float $valor
 *
 * @property \app\models\Cidade $cidade
 * @property \app\models\Estado $estado
 * @property \app\models\Pessoa $proprietario
 */
class Imovel extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nome', 'pessoa_id'], 'required'],
            [['pessoa_id', 'cidade_id', 'estado_id'], 'integer'],
            [['cep', 'rua'], 'string', 'max' => 255],
            [['nome', 'complemento'], 'string'],
            [['valor'], 'number', 'min' => 0, 'max' => 10],
            [['cidade_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cidade::className(), 'targetAttribute' => ['cidade_id' => 'id']],
            [['estado_id'], 'exist', 'skipOnError' => true, 'targetClass' => Estado::className(), 'targetAttribute' => ['estado_id' => 'id']],
            [['pessoa_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pessoa::className(), 'targetAttribute' => ['pessoa_id' => 'id']],
            [['cep'], 'ceptester'],
        ];
    }

    public function ceptester($attribute, $params, $validator){
        if (strlen($this->cep) != 8){
            $this->addError($attribute,'Cep deve conter oito caracters.');
        }
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'imovel';
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
            'pessoa_id' => 'Proprietário',
            'nome' => 'Nome',
            'cep' => 'CEP',
            'rua' => 'Rua/Av',
            'cidade_id' => 'Cidade',
            'estado_id' => 'Estado',
            'complemento' => 'Complemento',
            'valor' => 'Valor do Imóvel',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCidade()
    {
        return $this->hasOne(\app\models\Cidade::className(), ['id' => 'cidade_id']);
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
    public function getProprietario()
    {
        return $this->hasOne(\app\models\Pessoa::className(), ['id' => 'pessoa_id']);
    }
}
