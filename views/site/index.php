<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Welcome!</h1>

        <p class="lead">This is another example of Yii2 Application</p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-12 text-center">
                <?php
                    if (!Yii::$app->user->isGuest) {
                        $user = Yii::$app->user->getIdentity(); ?>
                    <h2>Hi <?=$user->username?></h2>
                    <p>Your current balance is <strong>USD <?=$user->balance?></strong>. </p>
                <?php
                    } else {
                        ?>
                   <p> Please login to check your current balance. </p>
               <?php
                    } ?>
            </div>
        </div>

    </div>
</div>
