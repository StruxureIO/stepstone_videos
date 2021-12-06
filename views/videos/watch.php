<?php


use humhub\modules\stepstone_videos\widgets\VideosMenu;
include "protected/modules/stepstone_videos/widgets/VideosMenu.php";
use humhub\modules\content\helpers\ContentContainerHelper;

// Register our module assets, this could also be done within the controller
\humhub\modules\stepstone_videos\assets\Assets::register($this);

$current_user_id = \Yii::$app->user->identity->ID;
//$checked = ($favorite['favorite']) ? 'checked' : '';


$container = ContentContainerHelper::getCurrent();
$container_guid = ($container) ? $container->guid : null;

?>

<div class="container-fluid">

  <div class="panel panel-default">
    
    <div class="panel-heading">
      
      <div>        
        <div class="col-md-12 video-title">    
          <span class="left"><strong>Watch</strong></span>
          <a class="back-link" onclick="history.back();">Back to Videos</a>
        </div>

      </div>      
      <div class="clearfix"></div>
            
      <div class="clearfix"></div>
      <p class="video-page-sub-title"><a class="step-favorite" data-user="<?php echo $current_user_id ?>" data-video="<?php echo $model['id'] ?>" ><span class="fa fa-star <?php echo $checked ?>"></span></a> <?php echo $model['video_title'] ?></p>        
      
    </div><!--panel-heading-->
        
    <div class="panel-body video-watch">
      <div><!--138456453-->
        <?php echo $model['embed_code'] ?>
        <!--<iframe id="watch" src="https://player.vimeo.com/video/138456453?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen="" title="" width="600" height="340" frameborder="0"></iframe>-->

      <div> 
      <p> <?php echo $model['description'] ?> </p>  
    </div>  
        
  </div><!--panel panel-default-->
     
</div><!--container-fluid-->
<?php
$ajax_favorite = yii\helpers\Url::to(['ajax-favorite']);
$csrf_param = Yii::$app->request->csrfParam;
$csrf_token = Yii::$app->request->csrfToken;

$this->registerJs("
  $(document).on('click', '.step-favorite', function (e) {
    e.stopImmediatePropagation();
    var favorite_icon = $(this).find('svg.svg-inline--fa.fa-star')
    var user_id = $(this).attr('data-user');
    var video_id  = $(this).attr('data-video');
    if( $(favorite_icon).hasClass('checked') ) {
      $(favorite_icon).removeClass('checked');
      update_favorite(user_id, video_id, false);
    } else {
      $(favorite_icon).addClass('checked');
      update_favorite(user_id, video_id, true);
    }  
  });

  function update_favorite(user_id, video_id, status) {
    console.log('user_id',user_id,'video_id',video_id,'status',status);
    $('#ajaxloader').show();
    $.ajax({
      'type' : 'GET',
      'url' : '$ajax_favorite',
      'dataType' : 'html',
      'data' : {
        'cguid' : '$container_guid',      
        '$csrf_param' : '$csrf_token',
        'user_id' : user_id,
        'video_id' : video_id,
        'status' : status
      },
      'success' : function(data){
        $('#ajaxloader').hide();
      }
    });  
  }
");
?>

