<?php

use humhub\libs\Html;
use humhub\widgets\PanelMenu;
use humhub\modules\ui\view\components\View;
use yii\helpers\Url;
use yii\web\UrlManager;


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
        margin-bottom:15px;
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
        </i><strong>Latest </strong> videos
    </div>

    <div class="panel-body">
      <ul id="videos-widget">
        <?php if($videos) { ?>
          <?php foreach($videos as $video) { ?>
            <?php 
              if (\Yii::$app->urlManager->enablePrettyUrl) 
                $watch_link = Url::base() . "/stepstone_videos/index/watch?video_id=" . $video['id'];
              else
                $watch_link = Url::base() ."/index.php?r=stepstone_videos%2Findex%2Fwatch&video_id=" . $video['id'];
              
              $cover = Url::base() . '/' . $video['image_url'];              
            ?>
            <li> 
              <div class="vw-title"><a href="<?php echo $watch_link ?>"><?php echo $video['video_title'] ?></a></div>
              <a href="<?php echo $watch_link ?>">
                <img class="vw-video-thumbnail" src="<?php echo $cover ?>" alt="<?php echo $video['video_title'] ?> thumbnail image" >
              </a>  
            </li>
          <?php } ?>
        <?php } ?>
      </ul>
    </div>
</div>
