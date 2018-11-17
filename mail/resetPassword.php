<?php


/*@var $model app\models\Users
*/
use yii\helpers\Html;
?>


<h2>Hi, <?= Html::encode($user->username)?>!</h2>
<p>Someone requested a password reset for your account  on yiiwebsite.com.

If you did not request a password reset, please ignore this email.

Please use one of the following links to log in to your account:


</p>
<p><?= Html::a(Yii::$app->urlManager->createAbsoluteUrl([
	'/user/retreive-password-form',
	'key'=> $user->authKey
	]))?>  </p>
