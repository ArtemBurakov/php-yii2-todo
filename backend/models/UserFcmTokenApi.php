<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
use frontend\models\UserFcmToken;
use common\models\User;

/**
 * UserFcmTokenApi extend the UserFcmToken model for RESTful api.
 */
class UserFcmTokenApi extends UserFcmToken
{
    public function beforeValidate()
    {
        //always set user_id of the authorized user
        $this->user_id = Yii::$app->user->identity->id;

        return parent::beforeValidate();
    }

    public static function find()
    {
        $updated_after = Yii::$app->getRequest()->get('updated_after');

        $find = parent::find();

        if (is_numeric($updated_after)){
            $find->andWhere(['>', 'updated_at', $updated_after]);
        }

        //return only authorized user data
        $find->andWhere(['user_id' => Yii::$app->user->identity->id]);

        return $find;
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert){

        if (parent::beforeSave($insert)) {

            //delete same tokens
            UserFcmToken::deleteAll(['registration_token' => $this->registration_token]);

            return true;
        }
        else {
            return false;
        }
    }

    // filter out some fields for API, best used when you want to inherit the parent implementation
    // and blacklist some sensitive fields.
    public function fields()
    {
        $fields = parent::fields();

        // remove fields that contain sensitive information
        unset($fields['user_id']);

        return $fields;
    }
}
