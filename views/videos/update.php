<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
\humhub\modules\stepstone_videos\assets\Assets::register($this);

/* @var $this yii\web\View */
/* @var $model app\models\VideoTags */

$this->title = 'Update Tag: ' . $model->tag_name;
$this->params['breadcrumbs'][] = ['label' => 'Video Tags', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->tag_name, 'url' => ['view', 'id' => $model->tag_id]];
$this->params['breadcrumbs'][] = 'Update';


?>
<div class="tag-update">

    <?= $this->render('_form', [
        'model' => $model,
        'cguid' => $cguid,
    ]) ?>

</div>
