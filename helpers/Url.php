<?php

namespace humhub\modules\stepstone_videos\helpers;

use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\user\models\User;

use yii\helpers\Url as BaseUrl;

class Url extends BaseUrl {


    public static function toVideos(ContentContainerActiveRecord $container = null)
    {
        if($container) {
            return $container->createUrl('/stepstone_videos/videos');
        }

        return static::toGlobalVideos();
    }
    
    public static function toGlobalVideos()
    {
        return static::to(['/videos/index']);
    }
    
    public static function toAddVendors(ContentContainerActiveRecord $container = null) {
      
      if($container) {
        return $container->createUrl('/stepstone_videos/index/add');
      }
      return '/stepstone_vendors/index/';
      
    }
    
    

}