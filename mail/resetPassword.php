<?php


/*@var $model app\models\Users
*/
use yii\helpers\Html;
?>


<h2>Hi, <?= Html::encode($user->username)?>!</h2>
<p>You received this message because you requested a password reset for the Yii2project. If you'd like to change your password, please, proceed to the following link: </p>
<p><?= Html::a(Yii::$app->urlManager->createAbsoluteUrl([
	'/user/retreive-password-form',
	'key'=> $user->authKey
	]))?></p>
