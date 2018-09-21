<?php


/*@var $model app\models\Users
*/
use yii\helpers\Html;
?>


<h2>Thank you for signing up for an account!</h2>
<p>Welcome to the website, <?= $model->username ?>! In order to activate your account, please, proceed into the following link:</p>
<p><?=Html::a('In order to activate your account, please, proceed into the following link: '. Yii::$app->urlManager->createAbsoluteUrl([

	'/user/activate-account',
	'key'=> $model->authKey
	])) ?></p>

<p>Don`t forget, your username is <b><?= $model->login?></b></p>


