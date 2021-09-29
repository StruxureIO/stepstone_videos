<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use humhub\modules\stepstone_videos\widgets\VideosMenu;
use yii\db\Query;

include "protected/modules/stepstone_videos/widgets/VideosMenu.php";

\humhub\modules\stepstone_videos\assets\Assets::register($this);

?>

<div class="container-fluid">

  <div class="panel panel-default">
    
    <div class="panel-heading">
      
      <?php
            
      ?>
            
      <div>        
        <div class="col-md-3 video-title">    
          <strong>Popular</strong>
        </div>

        <div class="col-md-6 video-title">    
          <div class="form-group form-group-search">
            <input type="text" id="video-search-field" class="form-control form-search" value="" placeholder="search popular videos">
            <a id="video-search" class="btn btn-default btn-sm form-button-search">Search</a>
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
$watch_url =  Url::toRoute(['index/watch']);

if(strpos($watch_url, '?') !== false)
  $idparam = "&video_id=";    
else
  $idparam = "?video_id=";         

$ajax_popular = yii\helpers\Url::to(['ajax-popular']);
$ajax_favorite = yii\helpers\Url::to(['ajax-favorite']);
$ajax_search_popular = yii\helpers\Url::to(['ajax-search-popular']);
$csrf_param = Yii::$app->request->csrfParam;
$csrf_token = Yii::$app->request->csrfToken;

$this->registerJs("
  load_popular_vidoes(0); 
    
  function load_popular_vidoes(page) {
    $('#ajaxloader').show();
    $.ajax({
      'type' : 'GET',
      'url' : '$ajax_popular',
      'dataType' : 'json',
      'data' : {
        '$csrf_param' : '$csrf_token',
        'page' : page  
      },
      'success' : function(data){
        $('#ajaxloader').hide();
        $('#video-container').html(data.html);
      }
    });
  }
  
  function update_favorite(user_id, video_id, status) {
    //console.log('user',user_id, video_id, status);
    $('#ajaxloader').show();
    $.ajax({
      'type' : 'GET',
      'url' : '$ajax_favorite',
      'dataType' : 'html',
      'data' : {
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
    var video_id  = $(this).attr('data-video-id');   
    window.location.href = '$watch_url' + '$idparam' + video_id;
    //http://localhost/humhub/index.php?r=stepstone_videos%2Findex%2Fwatch&video_id=1  
  });
  
  $(document).on('click', '#step-video-prev, #step-video-next', function (e) {  
    var page  = $(this).attr('data-page-id');   
    load_popular_vidoes(page);   
  });
  
  $(document).on('keypress',function(e) {
    if(e.which == 13) {
      do_popular_search();
    }
  });
  
  $(document).on('click', '#video-search', function (e) {  
    do_popular_search();
  });
  
  function do_popular_search() {
    var search_text = $('#video-search-field').val();
    $('#ajaxloader').show();
    $.ajax({
      'type' : 'GET',
      'url' : '$ajax_search_popular',
      'dataType' : 'json',
      'data' : {
        '$csrf_param' : '$csrf_token',
        'tag' : 'all',
        'tag_name' : 'All Tags',
        'search_text' : search_text
      },
      'success' : function(data){
        $('#ajaxloader').hide();
        $('#video-container').html(data.html);
        $('.video-page-sub-title').html(data.tag);
        //if(data.icon == '')
          $('.video-page-sub-title').html(data.tag);
        //else  
        //  $('.video-page-sub-title').html('<i class=\"'+ data.icon + '\"></i> ' + data.tag);
      }
    });
  
  }

");
?>
