<?php

namespace humhub\modules\stepstone_videos;

use Yii;
use yii\helpers\Url;
use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\content\components\ContentContainerModule;
use humhub\modules\space\models\Space;
use humhub\modules\user\models\User;
use humhub\modules\stepstone_videos\models\VideoTags;
use humhub\modules\stepstone_videos\widgets;

if(!defined('VIDEO_THUMBNAIL_PATH')) 
  define('VIDEO_THUMBNAIL_PATH','uploads/video-thumbnails/');

if(defined('LOCALHOST')) {
  if(!defined('MAX_ITEMS')) 
    define('MAX_ITEMS', 3);
} else {
  if(!defined('MAX_ITEMS')) 
    define('MAX_ITEMS', 9);
}  

// id numbers of tags
if(!defined('MASTERMINDS')) 
  define('MASTERMINDS', '1');
if(!defined('TRAINING_VIDEOS')) 
  define('TRAINING_VIDEOS', '2');

if(defined('LOCALHOST')) {
  if(!defined('CASE_STUDIES')) 
   define('CASE_STUDIES', '7');
} else {
  if(!defined('CASE_STUDIES'))   
    define('CASE_STUDIES', '3');
}

class Module extends ContentContainerModule
{
  
    private $_assetsUrl;

    /**
    * @inheritdoc
    */
    public function getContentContainerTypes()
    {
        return [
            Space::class,
            User::class.
            VideoTags::class
        ];
    }

    /**
    * @inheritdoc
    */
    public function getConfigUrl()
    {
        return Url::to(['/videos/admin']);
    }

    /**
    * @inheritdoc
    */
    public function disable()
    {
        // Cleanup all module data, don't remove the parent::disable()!!!
        parent::disable();
    }

    /**
    * @inheritdoc
    */
    public function disableContentContainer(ContentContainerActiveRecord $container)
    {
        // Clean up space related data, don't remove the parent::disable()!!!
        parent::disable();
    }

    /**
    * @inheritdoc
    */
    public function getContentContainerName(ContentContainerActiveRecord $container)
    {
        return Yii::t('StepstoneVideosModule.base', 'Videos');
    }

    /**
    * @inheritdoc
    */
    public function getContentContainerDescription(ContentContainerActiveRecord $container)
    {
        return Yii::t('StepstoneVideosModule.base', 'This is the StepStone Videos module.');
    }
    
    public function getAssetUrl() {
      return $this->_assetsUrl;      
    }
    
    public static function onSidebarInit($event){
      if (Yii::$app->hasModule('Videos')) {

        $event->sender->addWidget(widgets\Sidebar::className(), array(), array(
            'sortOrder' => 150
        ));
      }
    }
    
    
}
