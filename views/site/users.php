<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-users">
    <h1><?= Html::encode($this->title) ?></h1>

    <table class="table table-striped">
      <thead class="thead-light">
        <tr>
          <th scope="col">#</th>
          <th scope="col">Username</th>
          <th scope="col">Balance</th>
        </tr>
      </thead>
      <tbody>
        <?php 
            $rowNumber = $pagination->offset + 1;
            foreach ($users as $user) {
                ?>
            <tr>
              <th scope="row"><?=$rowNumber?></th>
              <td><?=$user->username?></td>
              <td><?=$user->balance?></td>          
            </tr>
        <?php
              $rowNumber++;
            } ?>
      </tbody>
    </table>

    <?php 
      echo LinkPager::widget([
          'pagination' => $pagination,
      ]);
    ?>
</div>
