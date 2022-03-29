<?php
namespace backend\modules\v1\controllers;

use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\AccessControl;

class NoteController extends ActiveController
{
    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['class'] = 'backend\components\IndexActionPagination';
        return $actions;
    }

    public $modelClass = 'backend\models\NoteApi';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
        ];
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];
        return $behaviors;
    }
}
