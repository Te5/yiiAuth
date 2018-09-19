<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RetreivePasswordForm */
/* @var $form ActiveForm */
?>
<div class="user-RetreivePasswordForm">

<h3>Please, type a new password: </h3>
    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- user-RetreivePasswordForm -->
