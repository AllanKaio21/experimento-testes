<?php

namespace app\models;

use Yii;

/**
 * This is the base model class for table "pessoa".
 *
 * @property integer $id
 * @property string $nome
 * @property string $cpf
 * @property string $cep
 * @property string $rua
 * @property integer $cidade_id
 * @property integer $estado_id
 * @property string $profissao
 * @property string $complemento
 *
 * @property \app\models\Cidade $cidade
 * @property \app\models\Estado $estado
 */
class Pessoa extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nome', 'cpf'], 'required', 'message' => 'Campo não pode ficar em branco!'],
            [['cpf', 'cidade_id', 'estado_id'], 'integer'],
            [['cep', 'rua', 'profissao'], 'string', 'max' => 255],
            [['nome', 'complemento'], 'string'],
            [['cidade_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cidade::className(), 'targetAttribute' => ['cidade_id' => 'id']],
            [['estado_id'], 'exist', 'skipOnError' => true, 'targetClass' => Estado::className(), 'targetAttribute' => ['estado_id' => 'id']],            
            [['cpf'], \yiibr\brvalidator\CpfValidator::className()],
            [['cpf'], 'validateFieldUnique'],
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
        return 'pessoa';
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
            'cpf'=>'CPF',
            'nome'=>'Nome',
            'cep'=>'CEP',
            'rua'=>'Rua/Av',
            'cidade_id'=>'Cidade',
            'estado_id'=>'Estado',
            'profissao'=>'Profissão',
            'complemento'=>'Complemento',
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
    public function getImoveis()
    {
        return $this->hasMany(\app\models\Imovel::className(), ['pessoa_id' => 'id']);
    }

    public function validateFieldUnique($attribute, $params, $validator)
    {
        if ($this->cpf != null && $this->cpf != "") {
            $data = Pessoa::find()->where(['cpf' => $this->cpf])->all();
            if ($data) {
                $this->addError('cpf', 'CPF já cadastrado no sistema, por você ou por outro aluno.');
            }
        }
    }
}
