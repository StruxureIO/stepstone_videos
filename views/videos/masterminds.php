<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use humhub\modules\stepstone_videos\widgets\VideosMenu;
use yii\db\Query;
use humhub\modules\content\helpers\ContentContainerHelper;

include "protected/modules/stepstone_videos/widgets/VideosMenu.php";

\humhub\modules\stepstone_videos\assets\Assets::register($this);

$container = ContentContainerHelper::getCurrent();
$container_guid = ($container) ? $container->guid : null;

?>


<div class="container-fluid">

  <div class="panel panel-default">
    
    <div class="panel-heading">
            
      <div>        
        <div class="col-md-3 video-title">    
          <strong>Masterminds</strong>
        </div>

        <div class="col-md-6 video-title">    
          <div class="form-group form-group-search">
            <input type="text" id="mm-video-search-field" class="form-control form-search" value="" placeholder="search videos">
            <a id="mm-video-search" class="btn btn-default btn-sm form-button-search">Search</a>
          </div>
          <div class="col-md-3"></div>                    
        </div>        
      </div>      
      <div class="clearfix"></div>
                                       
    </div><!--panel-heading-->
        
    <div class="panel-body">
      
      <div id="alwrap">
        <div id="ajaxloader"></div>
      </div>      
      
      <div id="video-container" class="row">
        
      </div>
      
    </div>  
        
  </div><!--panel panel-default-->
     
</div><!--container-fluid-->
<?php
$watch_url = Url::toRoute(['index/watch']);

if(strpos($watch_url, '?') !== false)
  $idparam = "&video_id=";    
else
  $idparam = "?video_id=";         

$ajax_masterminds = yii\helpers\Url::to(['ajax-masterminds']);
$ajax_favorite = yii\helpers\Url::to(['ajax-favorite']);
$ajax_search_masterminds = yii\helpers\Url::to(['ajax-search-masterminds']);
$csrf_param = Yii::$app->request->csrfParam;
$csrf_token = Yii::$app->request->csrfToken;

$this->registerJs("
  load_masterminds_vidoes(0); 
      
  function load_masterminds_vidoes(page) {
    $('#ajaxloader').show();
    $.ajax({
      'type' : 'GET',
      'url' : '$ajax_masterminds',
      'dataType' : 'json',
      'data' : {
        'cguid' : '$container_guid',      
        '$csrf_param' : '$csrf_token',
        'page' : page  
      },
      'success' : function(data){
        $('#ajaxloader').hide();
        $('#video-container').html(data.html);
      }
    });
  }
  
  $(document).on('click', '.step-favorite', function (e) {
    e.stopImmediatePropagation();
    var favorite_icon = $(this).find('span.fa.fa-star')
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
  
  $(document).on('click', '.step-video-link', function (e) {
    e.stopImmediatePropagation();
    var video_id  = $(this).attr('data-video-id');   
    window.location.href = '$watch_url' + '$idparam' + video_id;
  });
  
  $(document).on('click', '#step-video-prev, #step-video-next', function (e) {  
    e.stopImmediatePropagation();
    var page  = $(this).attr('data-page-id');   
    load_masterminds_vidoes(page);   
  });
  
  $('#mm-video-search-field').on('keypress',function(e) {
    if(e.which == 13) {
      e.stopImmediatePropagation();
      do_mm_search();
    }
  });
  
  $(document).on('click', '#mm-video-search', function (e) {  
    e.stopImmediatePropagation();
    do_mm_search();
  });
  
  function do_mm_search() {
    console.log('do_mm_search()');
    var search_text = $('#mm-video-search-field').val();
    $('#ajaxloader').show();
    $.ajax({
      'type' : 'GET',
      'url' : '$ajax_search_masterminds',
      'dataType' : 'json',
      'data' : {
        'cguid' : '$container_guid',      
        '$csrf_param' : '$csrf_token',
        'tag' : '1',
        'tag_name' : 'Masterminds',
        'search_text' : search_text
      },
      'success' : function(data){
        $('#ajaxloader').hide();
        $('#video-container').html(data.html);
        $('.video-page-sub-title').html(data.tag);
      }
    });
  
  }

");
?>
  