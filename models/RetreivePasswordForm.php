<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\base\InvalidParamException;

class RetreivePasswordForm extends Model 
{
    public $password;
    private $_user;

    public function __construct($key, $config = []) 
    {
        if(empty($key) || !is_string($key)) 
        {
            throw new InvalidParamException('Key cannot be empty');
        }
        $this->_user = Users::findByAuthKey($key);
        if(!$this->_user) 
        {
            throw new InvalidParamException('Incorrect key');

        }
        parent::__construct($config);
    }

    public function rules() 
    {

        return [
            ['password', 'required'],
            ['password', 'match', 'pattern' => '/^(?=.*?[#$%&\'()*+,-.\/:;<=>?@[\]^_`{|}~].*?[#$%&\'()*+,-.\/:;<=>?@[\]^_`{|}~])(?=.*[0-9])[0-9a-zA-Z!@#$%0-9]{6,}$/', 'message'=> 'Password should be at least characters long and contain two special characters'],
        ];
    }

    public function attributeLabels() 
    {
        return [
            'password' => 'Password'
        ];
    }

    public function resetPassword() 
    {
        /*@var $user User*/
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removeAuthKey();
        return $user->save();
    }


}