<?php

namespace  humhub\modules\stepstone_videos;

use Yii;
//use yii\helpers\Url;
use humhub\modules\stepstone_videos\helpers\Url;
use humhub\modules\stepstone_videos\widgets;
use humhub\modules\stepstone_videos\models\videos;
use humhub\modules\search\interfaces\Searchable;
use humhub\modules\stepstone_videos\widgets\VideoDashboard;
use humhub\modules\ui\menu\MenuLink;

include "protected/modules/stepstone_videos/widgets/VideoDashboard.php";


class Events
{
    /**
     * Defines what to do when the top menu is initialized.
     *
     * @param Events $event
     */
    public static function onTopMenuInit($event)
    {
        $event->sender->addItem([
            'label' => 'Videos',
            'icon' => '<i class="far fa-video"></i>',
            'url' => Url::to(['/stepstone_videos/index']),
            'sortOrder' => 99999,
            'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'videos' && Yii::$app->controller->id == 'index'),
        ]);
    }

    /**
     * Defines what to do if admin menu is initialized.
     *
     * @param Events $event
     */
    public static function onAdminMenuInit($event)
    {
      
        Yii::$app->cache->flush();      

        $event->sender->addItem([
            'label' => 'Videos',
            'url' => Url::to(['/stepstone_videos/admin']),
            'group' => 'manage',
            'icon' => '<i class="far fa-video"></i>',
            'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'videos' && Yii::$app->controller->id == 'admin'),
            'sortOrder' => 99998,
        ]);
        
        $event->sender->addItem([
            'label' => 'Video Tags',
            'url' => Url::to(['/stepstone_videos/admin/tags']),
            'group' => 'manage',
            'icon' => '<i class="far fa-tag"></i>',
            'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'videos' && Yii::$app->controller->id == 'tags'),
            'sortOrder' => 99999,
        ]);
        
    }
        
    public static function addVideoDashboard($event){
      
      if (Yii::$app->hasModule('stepstone_videos')) {      
        $event->sender->addWidget(widgets\VideoDashboard::class, [], ['sortOrder' => 150]);
      }  
    }
    
    public static function onSearchRebuild($event){
      foreach (Videos::find()->visible()->each() as $video) {
        Yii::$app->search->add($video);
      }
    }
    
    public static function onSearchAttributes(SearchAttributesEvent $event)
    {      
      if (!isset($event->attributes['videos'])) {
          $event->attributes['videos'] = [];
      }
            
      foreach (Videos::find()->visible()->each() as $video) {
        
        $event->attributes['videos'][$video->id] = [                
          'video_title' => $video->video_title,
          'description' => $video->description
        ];
                
        Event::trigger(Search::class, Search::EVENT_SEARCH_ATTRIBUTES, new SearchAttributesEvent($event->attributes['videos'][$video->id], $video));
      }
      
    }
    
    public static function onSpaceMenuInit($event)
    {
      try {
          $space = $event->sender->space;
          if ($space->isModuleEnabled('stepstone_videos')) {
              $event->sender->addItem([
                  'label' => Yii::t('StepstoneVideosModule.base', 'Videos'),
                  'group' => 'modules',
                  'url' => Url::toVideos($space),
                  'icon' => '<i class="far fa-video"></i>',
                  'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'videos' && Yii::$app->controller->id == 'index'),
                  //'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'stepstone_videos'),                  
              ]);
          }
      } catch (\Throwable $e) {
          Yii::error($e);
      }
    }
    
            
}
