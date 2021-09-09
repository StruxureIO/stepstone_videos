<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
\humhub\modules\stepstone_videos\assets\Assets::register($this);

/* @var $this yii\web\View */
/* @var $model app\models\VideoTags */

$this->title = 'Add New Tag';
$this->params['breadcrumbs'][] = ['label' => 'Video Tags', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Add';


?>
<div class="tag-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
