<?php
use humhub\modules\stepstone_videos\widgets\VideosMenu;
//require_once "protected/modules/stepstone_videos/widgets/VideosMenu.php";
?>

<div class="container">
    <div class="row">
      
      <div class="col-md-2"> <!--menu column-->
        <div class="panel panel-default left-navigation">
           <!--< ?= CloseButton::widget(); ?>--> 
           <?= VideosMenu::widget(); ?> 
          <!--< ?= FirstWidget::widget() ?>-->
        </div>  
      </div>  
      
    <div class="col-md-10 videos-inner-row">
      
      <?= $content ?>
      
    </div>  




      
      
      
    </div>
</div>