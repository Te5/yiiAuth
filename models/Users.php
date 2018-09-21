<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;
use app\models\AuthAssignment;
/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $login
 * @property string $email
 * @property string $username
 * @property string $password
 * @property string $authKey
 * @property binary $active 
 */
class Users extends \yii\db\ActiveRecord implements IdentityInterface {
    public $hashPassword = false;
    public $permissions;
    /**
     * {@inheritdoc}
     */
    const STATUS_DELETED = 0;
    const STATUS_NOT_ACTIVE = 1;
    const STATUS_ACTIVE = 10;
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['login', 'email', 'username', 'password',], 'required'],
            [['login', 'email', 'username', 'password', 'authKey'], 'string', 'max' => 64],
            [['username', 'authKey'], 'unique'],
            ['active', 'default', 'value'=> 0, 'on'=> 'emailActivation']
            //добавить regexp

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Login',
            'email' => 'Email',
            'username' => 'Username',
            'password' => 'Password',
            'authKey' => 'Auth Key',
        ];
    }

        public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException("findIdentityByAccessToken is not implemented");
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }


    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }       


    public function validatePassword($password)
    {

        return Yii::$app->security->validatePassword($password, $this->password);

    }               

    public static function findByLogin($login) 
    {
        return self::findOne(['login'=>$login]);
    }    

    public function beforeSave($insert) 
    {
        if(parent::beforeSave($insert)) {
            
            if($this->hashPassword) 
            {
                $this->password = Yii::$app->security->generatePasswordHash($this->password, 10);

            }

//          Добавляем пользователю выбранную роль при update
            if (isset($_POST['Users']['permissions'])) 
            {
            $permList = $_POST['Users']['permissions'];
            $currentRole = AuthAssignment::findOne(['user_id'=> "{$this->id}"]);

            isset($currentRole)? $currentRole->delete(): null;
            $newPerm = new AuthAssignment;
            $newPerm->user_id = $this->id;
            $newPerm->item_name = $_POST['Users']['permissions'];
            $newPerm->save();
            }

            return true; 
        } else {
            return false;
        }
    }

    public function addPerm() 
    {

                $userPerm = new AuthAssignment;
                $userPerm->user_id = $this->id;
                $userPerm->item_name = 'user';
                $userPerm->save();   
    }

    public static function isAuthKeyExpire($key) 
    {
        if(empty($key)) 
        {
            return false;
        }
        $expire = Yii::$app->params['authKeyExpire'];
        $parts = explode('_', $key);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    public static function findByAuthKey($key) 
    {
        if(!static::isAuthKeyExpire($key)) 
        {
            return null;
        }
        return static::findOne(['authKey'=>$key]);
    }

    public function generateAuthKey() 
    {
        $this->authKey = Yii::$app->security->generateRandomString().'_'.time();
    }

    public function removeAuthKey() 
    {
        $this->authKey = null;
    }

    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password, 10);
    }    

    public function sendActivationEmail($model) 
    {
        return Yii::$app->mailer
                    ->compose('activationEmail', compact('model'))
                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name.'(sent automatically)'])
                    ->setTo($this->email)
                    ->setSubject('Account activation for '. Yii::$app->name)
                    ->send();
                    return true;
    }
}
