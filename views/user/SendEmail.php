<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SendEmailForm */
/* @var $form ActiveForm */
?>
<div class="user-SendEmail">
<h4 align="center">Recover Password
</h4>
    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'email')->label('Email adress') ?>
    
        <div class="form-group">
            <?= Html::submitButton('Recover Password', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- user-SendEmail -->
