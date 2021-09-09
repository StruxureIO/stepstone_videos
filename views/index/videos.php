<?php

use humhub\widgets\Button;

// Register our module assets, this could also be done within the controller
\humhub\modules\stepstone_videos\assets\Assets::register($this);

$displayName = (Yii::$app->user->isGuest) ? Yii::t('StepstoneVideosModule.base', 'Guest') : Yii::$app->user->getIdentity()->displayName;

// Add some configuration to our js module
//$this->registerJsConfig("videos", [
//    'username' => (Yii::$app->user->isGuest) ? $displayName : Yii::$app->user->getIdentity()->username,
//    'text' => [
//        'hello' => Yii::t('StepstoneVideosModule.base', 'Hi there {name}!', ["name" => $displayName])
//    ]
//])

?>

<!--<div class="panel-heading"><strong>Videos</strong> <?= Yii::t('StepstoneVideosModule.base', 'overview') ?></div>-->

<!--<div class="panel-body">-->
<!--    <p>< ?= Yii::t('StepstoneVideosModule.base', 'Hello World!') ?></p>

    < ?=  Button::primary(Yii::t('StepstoneVideosModule.base', 'Say Hello!'))->action("template.hello")->loader(false); ? >-->
<!--</div>-->
    <div class="panel panel-default">
      <div class="panel-heading"><strong>Videos</strong></div>
    </div>  
  