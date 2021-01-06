<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\models\User;
use frontend\components\Helper;

/**
 * This is the model class for table "board".
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property int $created_at
 * @property int $updated_at
 */
class Board extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'board';
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
            [['name'], 'required'],
            [['user_id', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
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
            'name' => Yii::t('frontend', 'Name'),
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
            "model" => "board"
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
