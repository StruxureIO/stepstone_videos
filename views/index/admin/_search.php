<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CountrySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="video-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

   
    <div class="form-group">
        <!--< ?= $form->field($model, 'video_title') ?>-->
        <input type="text" id="videossearch-video_title" class="form-control" name="VideosSearch[video_title]" style="display:inline-block;">      
        <div id="video-admin-search-wraper">
          <?= Html::submitButton('<i class="fa fa-search"></i>', ['id' => 'video-admin-search', 'class' => 'btn pull-right']) ?>
        </div>  
    </div>

    <?php ActiveForm::end(); ?>

</div>
