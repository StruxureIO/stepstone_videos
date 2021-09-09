<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

\humhub\modules\stepstone_videos\assets\Assets::register($this);

/* @var $this yii\web\View */
/* @var $model app\models\VideoTags */

$this->title = 'Add New Video';
$this->params['breadcrumbs'][] = ['label' => 'Video', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'New';
$type = 'Add';

?>

<div class="tag-update">

    <?= $this->render('_videoform', [
        'model' => $model,
        'tags' => $tags,  
        'type' => $type,
    ]) ?>

</div>
<?php
$ajax_thumbnail = yii\helpers\Url::to(['ajax-thumbnail']);
$csrf_param = Yii::$app->request->csrfParam;
$csrf_token = Yii::$app->request->csrfToken;
$base_url = Url::base() . '/';
$this->registerJs("  
  $(document).on('click', '#generate-thumbnail', function (e) {
  
    var video_title = $('#videos-video_title').val();    
    
    var embed_code = $('#videos-embed_code').val();
        
    var start = embed_code.indexOf('player.vimeo.com/video/') + 23;
    
    var end = embed_code.indexOf('?');
    
    var video_id = embed_code.substring(start, end);
  
    // test video id
    //video_id = '569375685';
    
    console.log('video_id', video_id);
          
    $.ajax({
      'type' : 'GET',
      'url' : '$ajax_thumbnail',
      'dataType' : 'html',
      'data' : {
        '$ csrf_param' : '$ csrf_token',
        'video_id' : video_id,
        'video_title' : video_title
      },
      'success' : function(data){        
        $('#videos-image_url').val(data);        
        $('#video-thumbnail-image').prop('src', '$base_url' + data);
        $('#video-thumbnail-image').show();        
      }
    });  
    
  });
      
");
?>