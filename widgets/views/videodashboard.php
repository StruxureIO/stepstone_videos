<?php

use humhub\libs\Html;
use humhub\widgets\PanelMenu;
use humhub\modules\ui\view\components\View;
use yii\helpers\Url;

?>

<div class="panel panel-default panel-video" id="panel-video">

    <style>
      
      ul#videos-widget {
        list-style: none;
        padding-left: 0;
      }      
      
      ul#videos-widget li {
        width: 100%;
        overflow: hidden;
      }      
      
      .vw-title {
        font-weight: 700;
        padding-bottom: 5px;
      }

      img.vw-video-thumbnail {
        height: auto;
        width: 100%;       
      }      
      
      .vw-description {
        display: block;
        min-height: 22px;
        padding-bottom: 10px;
      }
    </style>

    <?= PanelMenu::widget(['id' => 'panel-video']); ?>
    
    <div style="clear:both;"></div>

    <div class="panel-heading">
        <i class="far fa-video">&nbsp;</i><strong>Latest Videos</strong>
    </div>

    <div class="panel-body">
      <ul id="videos-widget">
        <?php if($videos) { ?>
          <?php foreach($videos as $video) { ?>
           <?php $cover = Url::base() . '/' . $video['image_url'] ?>
            <li> 
              <div class="vw-title"><a href="<?php echo Url::base() ."/index.php?r=stepstone_videos%2Findex%2Fwatch&video_id=" . $video['id'] ?>"><?php echo $video['video_title'] ?></a></div>
              <a href="<?php echo Url::base() ."/index.php?r=stepstone_videos%2Findex%2Fwatch&video_id=" . $video['id'] ?>">
                <img class="vw-video-thumbnail" src="<?php echo $cover ?>" alt="<?php echo $video['video_title'] ?> thumbnail image" >
              </a>  
              <div class="vw-description"><?php echo $video['description'] ?></div>
            </li>
          <?php } ?>
        <?php } ?>
      </ul>
    </div>
</div>
