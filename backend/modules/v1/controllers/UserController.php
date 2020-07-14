<?php
namespace backend\modules\v1\controllers;

use yii\rest\ActiveController;
use yii\filters\AccessControl;
use Yii;
use common\models\User;

class UserController extends ActiveController
{
    public $modelClass = 'common\models\User';

    public function actionAuthorize()
    {
        $params = Yii::$app->getRequest()->getBodyParams();
        $response = Yii::$app->getResponse();

        //validate data
        $model = \yii\base\DynamicModel::validateData(['username', 'password'], [
            [['username', 'password'], 'required'],
            [['username', 'password'], 'string'],
        ]);
        $model->load($params, '');

        if (!$model->validate()) {
            $response->setStatusCode(401, 'Unauthorized');
            $result = [
                'name' => 'Unauthorized',
                'message' => 'You are requesting with an invalid credential.',
            ];
            return $result;
        }

        //get user by username
        $user = User::findByUsername($model->username);
        if (!$user || !$user->validatePassword($model->password)) {
            $response->setStatusCode(401, 'Unauthorized');
            $result = [
                'name' => 'Unauthorized',
                'message' => 'You are requesting with an invalid credential.',
            ];
            return $result;
        }

        $result = [
            'id' => $user->id,
            'access_token' => $user->access_token
        ];

        return $result;
    }
}
