<?php

namespace app\controllers;

use app\models\Cidade;
use app\models\Pessoa;
use yii\helpers\Json;

class CidadeController extends \yii\web\Controller
{
    public function actionProprietarios()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != NULL) {
                $cidade_id = $parents[0];
                $out = Pessoa::find()->where(['cidade_id' => $cidade_id])->select(['id', "nome as name"])->asArray()->all();

                return Json::encode(['output' => $out, 'selected' => '']);
            }
        }
        $out = Pessoa::find()->where(['cidade_id' => 1])->select(['id', 'nome as name'])->asArray()->all();
        return Json::encode(['output' => $out, 'selected' => '']);
    }

}
