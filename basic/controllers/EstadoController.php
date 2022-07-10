<?php

namespace app\controllers;

use app\models\Cidade;
use yii\helpers\Json;

class EstadoController extends \yii\web\Controller
{
    public function actionCidades()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != NULL) {
                $estado_id = $parents[0];
                $out = Cidade::find()->where(['estado_id' => $estado_id])->select(['id', "nome as name"])->asArray()->all();

                return Json::encode(['output' => $out, 'selected' => '']);
            }
        }
        $out = Cidade::find()->where(['estado_id' => 1])->select(['id', 'nome as name'])->asArray()->all();
        return Json::encode(['output' => $out, 'selected' => '']);
    }

}
