<?php

namespace backend\models;

use Yii;
use frontend\models\Board;

class BoardApi extends Board
{
    public static function find()
    {
        $query = parent::find();
        $query->where(['user_id' => \Yii::$app->user->identity->id]);

        $updated_after = Yii::$app->getRequest()->get('updated_after');
        if (is_numeric($updated_after)){
            $query->andFilterWhere(['>', 'updated_at', $updated_after]);
        }

        return $query;
    }
}
