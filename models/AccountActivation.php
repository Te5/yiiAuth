<?php
namespace app\models;


use yii\base\InvalidParamException;
use yii\base\Model;

/*@property string $username*/


class AccountActivation extends Model 
{
	/*@var $user \app\models\Users*/
	private $_user;

	public function __construct($key, $config =[]) 
	{
		if(empty($key)||!is_string($key)) 
		{
			throw new InvalidParamException('Key cannot be empty!');
		}

		$this->_user = Users::findByAuthKey($key);
		if(!$this->_user) 
		{
			throw new InvalidParamException('Incorrect key!');
		}
		parent::__construct($config);
	}	

	public function activateAccount() 
	{
		$user = $this->_user;
		$user->active = 1;
		$user->removeAuthKey();
		$user->addPerm();
		return $user->save();
	}
	public function getUsername() 
	{
		$user = $this->_user;
		return $user->username;
	}
}