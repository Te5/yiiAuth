<?php

namespace app\models;

use Yii;
use yii\base\Model;


class SendEmailForm extends Model 
{

	public $email;


	    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => Users::className(),
                'filter' => [
                    'active' => true
                ],
                'message' => 'Email doesn`t exist.'
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Email',
        ];
    }	

    public function sendEmail() 
    {
    	/* @var $user User*/
    	$user = Users::findOne(
    		[
    			'active'=> 1,
    			'email'=>$this->email,
    		]
    	);
    	if($user) 
    	{
    		$user->generateAuthKey();
    		if ($user->save())
    		{
    		        Yii::$app->mailer
    		        ->compose('resetPassword', compact('user'))
    		        ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name.'(sent automatically)'])
    		        ->setTo($this->email)
    		        ->setSubject('Password reset for '. Yii::$app->name)
    		        ->send();
    		        return true;
    		}    	
    	}
    	
    }
}