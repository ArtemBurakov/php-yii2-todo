<?php
namespace backend\components;

use Yii;
use yii\data\ActiveDataProvider;
use yii\rest\IndexAction;

class IndexActionPagination extends IndexAction
{
    /**
     * Prepares the data provider that should return the requested collection of the models.
     * @return ActiveDataProvider
     */
    protected function prepareDataProvider()
    {
        if ($this->prepareDataProvider !== null) {
            return call_user_func($this->prepareDataProvider, $this);
        }

        /* @var $modelClass \yii\db\BaseActiveRecord */
        $modelClass = $this->modelClass;

        return new ActiveDataProvider([
            'query' => $modelClass::find(),
            'pagination' => [
                'defaultPageSize' => 10000,
                'pageSizeLimit' => [1, 10000],
            ],
        ]);
    }
}
