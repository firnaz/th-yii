<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Transaction';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('transferSuccess')): ?>

        <div class="alert alert-success">
            Success!! You have successfully transferred the amount of USD <?=Yii::$app->session->getFlash('amount')?> to <?=Yii::$app->session->getFlash('username')?>.
        </div>

    <?php else: ?>

        <p>
            Please fill in the recipient's username and the amount you want to transfer.
        </p>

        <div class="row">
            <div class="col-lg-5">

                <?php $form = ActiveForm::begin(['id' => 'transaction-form', 'layout' => 'horizontal']); ?>

                    <?= $form->field($model, 'username') ?>

                    <?= $form->field($model, 'amount') ?>

                    <div class="form-group">
                        <div class="col-lg-offset-1 col-lg-11">
                            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'transaction-button']) ?>
                        </div>
                    </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>

    <?php endif; ?>
</div>
