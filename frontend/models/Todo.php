<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use frontend\components\Helper;
use common\models\User;

/**
 * This is the model class for table "todo".
 *
 * @property int $id
 * @property int $user_id
 * @property int $board_id
 * @property string $name
 * @property string $text
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 */
class Todo extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const STATUS_DONE = 20;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'todo';
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
            [['name', 'text'], 'required'],
            [['user_id', 'board_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 55],
            [['text'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('frontend', 'ID'),
            'user_id' => Yii::t('frontend', 'User ID'),
            'board_id' => Yii::t('frontend', 'Board ID'),
            'name' => Yii::t('frontend', 'Name'),
            'text' => Yii::t('frontend', 'Text'),
            'status' => Yii::t('frontend', 'Status'),
            'created_at' => Yii::t('frontend', 'Created At'),
            'updated_at' => Yii::t('frontend', 'Updated At'),
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
            "model" => "todo"
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
