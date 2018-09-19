<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SendEmailForm */
/* @var $form ActiveForm */
?>
<div class="user-SendEmail">
<h4 align="center">In order to restore your password, please, provide below email adress which you used while registering account.
</h4>
    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'email') ?>
    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- user-SendEmail -->
