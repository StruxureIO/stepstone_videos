<?php

use humhub\modules\stepstone_videos\models\VideosContent;
use yii\helpers\Url;
use yii\helpers\Html;


$watch_link = Url::base() ."/index.php?r=stepstone_videos%2Findex%2Fwatch&video_id=" . $videos->id;
?>

<div>
  <p><a href="<?php echo $watch_link ?>"><?= Html::encode($videos->video_title); ?></a></p>  
  <div>
    <a href="<?php echo $watch_link ?>">    
      <img src="<?= Url::base() . '/' . $videos->image_url ?>" alt="<?= Html::encode($videos->video_title); ?> video thumbnail image" width="350" height="200">
    </a>  
  </div>
  <p><?= Html::encode($videos->description); ?></p>
</div>


