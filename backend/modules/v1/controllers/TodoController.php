<?php
namespace backend\modules\v1\controllers;

use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\AccessControl;

class TodoController extends ActiveController
{
    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['class'] = 'backend\components\IndexActionPagination';
        return $actions;
    }

    public $modelClass = 'backend\models\TodoApi';

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
