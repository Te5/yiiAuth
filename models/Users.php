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
 */
class Users extends \yii\db\ActiveRecord implements IdentityInterface {
    public $hashPassword = false;
    public $permissions;
    /**
     * {@inheritdoc}
     */
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
            [['username'], 'unique'],
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
}
