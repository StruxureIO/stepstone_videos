<?php

use humhub\modules\calendar\models\CalendarDateFormatter;
use humhub\modules\calendar\models\CalendarEntry;
use yii\helpers\Html;

$formatter = new CalendarDateFormatter(['calendarItem' => $source]);

echo Yii::t('StepstoneVideosModule.views_activities_NewVideo', '%displayName% has added a video, %contentTitle%.', [
    '%displayName%' => '<strong>' . Html::encode($originator->displayName) . '</strong>',
    '%contentTitle%' => $this->context->getContentInfo($source).' on '.$formatter->getFormattedTime()
]);

?>