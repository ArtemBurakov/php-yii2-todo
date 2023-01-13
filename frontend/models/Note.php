<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use frontend\components\Helper;
use common\models\User;

/**
 * This is the model class for table "note".
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $board_id
 * @property string $name
 * @property string $text
 * @property int $type
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 */
class Note extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const STATUS_DONE = 20;

    const TYPE_TASK = 1;
    const TYPE_NOTE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'note';
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
            [['user_id', 'type', 'board_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'text'], 'string'],
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
            'board_id' => 'Board ID',
            'name' => 'Name',
            'text' => 'Text',
            'type' => 'Type',
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
            "model" => "note"
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
