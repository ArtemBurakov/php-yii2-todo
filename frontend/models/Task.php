<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use frontend\components\Helper;
use common\models\User;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property int $user_id
 * @property int $note_id
 * @property string $name
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 */
class Task extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const STATUS_DONE = 20;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'note_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'note_id' => 'User ID',
            'name' => 'Name',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function beforeValidate()
    {
        //set user id for insert
        if (!$this->id){
            $this->user_id = \Yii::$app->user->identity->id;
        }

        return parent::beforeValidate();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        // send FCM push notification
        $data = array(
            "model" => "task"
        );

        $tokenModels = $this->user->userFcmTokens;

        if ($tokenModels && is_array($tokenModels)) {
            foreach($tokenModels as $tokenModel){
                $tokens[] = $tokenModel->registration_token;
            }

            Helper::pushNotification($tokens, $data);
        }
    }
}
