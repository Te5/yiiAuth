<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="users-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'login')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true])->hint('6 characters minimun and two special characters') ?>
    
    <p align="center"> Or register via social networks:</p>
    </p><?= yii\authclient\widgets\AuthChoice::widget([
             'baseAuthUrl' => ['site/auth']
    ]) ?>           

        <?= Html::submitButton('Create an account', ['class' => 'btn btn-success']) ?>


    <?php ActiveForm::end(); ?>

</div>
