<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auth".
 *
 * @property int $id
 * @property int $user_id
 * @property string $source
 * @property string $source_id
 */
class Auth extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'source', 'source_id'], 'required'],
            [['user_id'], 'integer'],
            [['source', 'source_id'], 'string', 'max' => 255],
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
            'source' => 'Source',
            'source_id' => 'Source ID',
        ];
    }

    public static function findIdentity($user_id)
    {
        return self::findOne(['user_id'=>$user_id]);
    }    
}
