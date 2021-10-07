<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use humhub\modules\stepstone_videos\widgets\VideosMenu;
use yii\db\Query;

//include "protected/modules/stepstone_videos/widgets/VideosMenu.php";

// Register our module assets, this could also be done within the controller
\humhub\modules\stepstone_videos\assets\Assets::register($this);

?>

<div class="container-fluid">

  <div class="panel panel-default">
    
    <div class="panel-heading">
            
      <div>        
        <div class="col-md-3 video-title">    
          <strong class="video-page-sub-title">Latest </strong> Videos
        </div>

        <div class="col-md-6">
            <div class="form-group form-group-search">
              <input id="video-search-field" type="text" class="form-control form-search" name="keyword" value="" placeholder="search videos">
              <button id="video-search" type="submit" class="btn btn-default btn-sm form-button-search">Search</button>
            </div>
            <div class="col-md-3"></div>            
        </div>      
      <div class="clearfix"></div>
            
      <div>        
        <div class="col-md-9 video-title">    
          <p id="video-top-tags">
            <?php 
              $top_tag_list = '';
              foreach($top_tags as $ttags) {
                $top_tag_list .= '<a class="top-tag" data-tag-id="'.$ttags['tag_id'].'" data-tag-name="'.$ttags['tag_name'].'" >'.$ttags['tag_name'].'</a>';
                //$top_tag_list .= '<a class="top-tag" data-tag-id="'.$ttags['tag_id'].'" data-tag-name="'.$ttags['tag_name'].'" ><i class="'.$ttags['icon'].'"></i> '.$ttags['tag_name'].'</a>';
              }
            ?>
          </p>        
          <div id="top-tags">
            <?php 
              echo $top_tag_list;            
            ?>

          </div>
          </div>      
        
        <div class="col-md-3 video-title">    
          <p id="video-top-tags">
          </p>        
          <div id="tag-list-wraper">
            <select id="video-tag-list" class="form-control">
              <option value="all">All Tags</option>
              <?php 
              
              foreach($tags as $single_tag) {
                echo '<option value="'.$single_tag->tag_id.'">'.$single_tag->tag_name.'</option>';
              }
                
              ?>      
              
            </select>
          </div>        
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
</div>  
<?php
$watch_url =  Url::toRoute(['index/watch']);

if(strpos($watch_url, '?') !== false)
  $idparam = "&video_id=";    
else
  $idparam = "?video_id=";         

$ajax_favorite = yii\helpers\Url::to(['ajax-favorite']);
$ajax_url = yii\helpers\Url::to(['ajax-view']);
$csrf_param = Yii::$app->request->csrfParam;
$csrf_token = Yii::$app->request->csrfToken;

$this->registerJs("
  load_latest_vidoes('all', 'All Tags', 0); 
    
	$(document).on('change', '#video-tag-list', function (e) {						
    e.stopImmediatePropagation();
    var tag = $('#video-tag-list').val();
    var tag_name = $('#video-tag-list option:selected').text();
    $('#video-search-field').val('');
    load_latest_vidoes(tag, tag_name, 0);  
    $('.top-tag').removeClass('active-tag');
  });
  
  $(document).on('click', '.top-tag', function (e) {
    e.stopImmediatePropagation();
    var tag  = $(this).attr('data-tag-id');
    $('#video-tag-list').val(tag).trigger('change');
    $('.top-tag').removeClass('active-tag');
    $(this).addClass('active-tag')
  });
  
  function load_latest_vidoes(tag, tag_name, page) {
    $('#ajaxloader').show();
    $.ajax({
      'type' : 'GET',
      'url' : '$ajax_url',
      'dataType' : 'json',
      'data' : {
        '$csrf_param' : '$csrf_token',
        'tag' : tag,
        'tag_name' : tag_name,
        'search_text' : '',
        'page' : page
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
  
  $(document).on('keypress',function(e) {
    e.stopImmediatePropagation();
    if(e.which == 13) {
      do_search();
    }
  });
  
  $(document).on('click', '#step-video-prev, #step-video-next', function (e) {  
    e.stopImmediatePropagation();
    var page  = $(this).attr('data-page-id');   
    var tag_name = $('#video-tag-list option:selected').text();
    var tag = $('#video-tag-list option:selected').val();    
    load_latest_vidoes(tag, tag_name, page);   
  });
  
  $(document).on('click', '#video-search', function (e) {  
    e.stopImmediatePropagation();
    do_search();
  });
  
  function do_search() {
    var search_text = $('#video-search-field').val();
    $('#ajaxloader').show();
    $.ajax({
      'type' : 'GET',
      'url' : '$ajax_url',
      'dataType' : 'json',
      'data' : {
        '$csrf_param' : '$csrf_token',
        'tag' : 'all',
        'tag_name' : 'All Tags',
        'search_text' : search_text,
        'page' : 0
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
