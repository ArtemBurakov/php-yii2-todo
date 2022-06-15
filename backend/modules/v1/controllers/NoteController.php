<?php
namespace backend\modules\v1\controllers;

use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\AccessControl;
use yii\filters\Cors;

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
        $behaviors['corsFilter'] = [
            'class' => Cors::className()
        ];
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
               'except' => ['options'],
        ];
        $behaviors['access'] = [
               'class' => AccessControl::className(),
               'rules' => [
                   [
                       'allow' => true,
                       'roles' => ['@'],
                   ],
                   [
                       'actions' => ['options'],
                       'allow' => true,
                       'roles' => ['?'],
                   ]
               ],
           ];
        return $behaviors;
    }
}
