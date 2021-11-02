<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */


namespace humhub\modules\stepstone_videos\widgets;

use Yii;
use humhub\modules\ui\menu\MenuLink;
use humhub\modules\directory\Module;
use humhub\modules\ui\menu\widgets\LeftNavigation;
use humhub\components\Widget;
use humhub\modules\stepstone_videos\models\VideoTags;
use humhub\modules\user\models\User;
use humhub\modules\content\helpers\ContentContainerHelper;


/**
 * Directory module navigation
 *
 * @since 0.21
 * @author Luke
 */
class VideosMenu extends LeftNavigation
{
  
    public $mVideoTags;
    /**
     * @inheritdoc
     */
    public function init()
    {
        $menu_count = 400;
              
        Yii::debug('VideosMenu');
        
        $container = ContentContainerHelper::getCurrent();
                              
        $this->mVideoTags = new \humhub\modules\stepstone_videos\models\VideoTags();

        $tags = $this->mVideoTags::find()->where(['menu'=> 1])->all();      
            
        /** @var Module $module */
        $module = Yii::$app->getModule('stepstone_videos');

        $this->panelTitle = Yii::t('StepstoneVideosModule.base', '<strong>Videos</strong> menu');
        
        if($container) {
          
          // these links are used when the module is running in a space
          
          $this->addEntry(new MenuLink([
              'id' => 'videos-favorites',
              'icon' => 'fa-star',
              'label' => Yii::t('StepstoneVideosModule.base', 'Favorites'),
              //'url' => ['/stepstone_videos/index/favorites'],
              'url' => $container->createUrl('index/favorites'),
              'sortOrder' => 100,
              'isActive' => MenuLink::isActiveState('stepstone_videos', 'videos', 'favorites')
          ]));
          
          $this->addEntry(new MenuLink([
              'id' => 'videos-popular',
              'icon' => 'fa-thumbs-up',
              'label' => Yii::t('StepstoneVideosModule.base', 'Most Popular'),
              //'url' => ['/stepstone_videos/index/popular'],
              'url' => $container->createUrl('index/popular'),
              'sortOrder' => 200,
              'isActive' => MenuLink::isActiveState('stepstone_videos', 'videos', ['popular'])
          ]));

          $this->addEntry(new MenuLink([
              'id' => 'videos-index',
              'icon' => 'fa-video',
              'label' => Yii::t('StepstoneVideosModule.base', 'Latest'),
              //'url' => ['/stepstone_videos/index/index'],
              'url' => $container->createUrl('index/index'),
              'sortOrder' => 300,
              'isActive' => MenuLink::isActiveState('stepstone_videos', 'videos', 'index')
          ]));
          
          $this->addEntry(new MenuLink([
              'id' => 'videos-masterminds',
              'icon' => 'fa-brain',
              'label' => Yii::t('StepstoneVideosModule.base', 'Masterminds'),
              //'url' => ['/stepstone_videos/index/masterminds'],
              'url' => $container->createUrl('videos/masterminds'),
              'sortOrder' => 400,
              'isActive' => MenuLink::isActiveState('stepstone_videos', 'videos', 'masterminds')
          ]));

          $this->addEntry(new MenuLink([
              'id' => 'videos-training-videos',
              'icon' => 'fa-chalkboard-teacher',
              'label' => Yii::t('StepstoneVideosModule.base', 'Training Videos'),
              //'url' => ['/stepstone_videos/index/training-videos'],
              'url' => $container->createUrl('videos/training-videos'),
              'sortOrder' => 500,
              'isActive' => MenuLink::isActiveState('stepstone_videos', 'videos', 'training-videos')
          ]));

          $this->addEntry(new MenuLink([
              'id' => 'videos-case-studies',
              'icon' => 'fa-books',
              'label' => Yii::t('StepstoneVideosModule.base', 'Case Studies'),
              //'url' => ['/stepstone_videos/index/case-studies'],
              'url' => $container->createUrl('videos/case-studies'),
              'sortOrder' => 600,
              'isActive' => MenuLink::isActiveState('stepstone_videos', 'videos', 'case-studies')
          ]));
          
                            
          // admin items from the container
          $this->addEntry(new MenuLink([
              'id' => 'videos-admin',
              'icon' => 'fa-plus',
              'label' => Yii::t('StepstoneVideosModule.base', 'Add/Edit Videos'),
              'url' => $container->createUrl('videos/adminindex'),
              //'url' => [$container->createUrl('stepstone_videos/videos/admin/index')],
              'sortOrder' => 9000,
              'isActive' => MenuLink::isActiveState('stepstone_videos', 'videos', 'admiindex'),
              'isVisible' => Yii::$app->user->isAdmin()
              //'isVisible' => Yii::$app->user->getIdentity()->isSystemAdmin()    
          ]));
          
          $this->addEntry(new MenuLink([
              'id' => 'videos-tags-admin',
              'icon' => 'fa-tag',
              'label' => Yii::t('StepstoneVideosModule.base', 'Videos Tags'),
              'url' => $container->createUrl('videos/tags'),
              //'url' => [$container->createUrl('stepstone_videos/videos/admin/index')],
              'sortOrder' => 9200,
              'isActive' => MenuLink::isActiveState('stepstone_videos', 'videos', 'tags'),
              'isVisible' => Yii::$app->user->isAdmin()
              //'isVisible' => Yii::$app->user->getIdentity()->isSystemAdmin()    
          ]));
                   
                    
        } else {
        
          $this->addEntry(new MenuLink([
              'id' => 'videos-favorites',
              'icon' => 'fa-star',
              'label' => Yii::t('StepstoneVideosModule.base', 'Favorites'),
              'url' => ['/stepstone_videos/index/favorites'],
              'sortOrder' => 100,
              'isActive' => MenuLink::isActiveState('stepstone_videos', 'index', 'favorites')
          ]));

          $this->addEntry(new MenuLink([
              'id' => 'videos-popular',
              'icon' => 'fa-thumbs-up',
              'label' => Yii::t('StepstoneVideosModule.base', 'Most Popular'),
              'url' => ['/stepstone_videos/index/popular'],
              //'url' => [$container->createUrl('/stepstone_videos/index/popular')],
              'sortOrder' => 200,
              'isActive' => MenuLink::isActiveState('stepstone_videos', 'index', ['popular'])
          ]));

          $this->addEntry(new MenuLink([
              'id' => 'videos-index',
              'icon' => 'fa-video',
              'label' => Yii::t('StepstoneVideosModule.base', 'Latest'),
              'url' => ['/stepstone_videos/index/index'],
              'sortOrder' => 300,
              'isActive' => MenuLink::isActiveState('stepstone_videos', 'index', 'index')
          ]));

          $this->addEntry(new MenuLink([
              'id' => 'videos-masterminds',
              'icon' => 'fa-brain',
              'label' => Yii::t('StepstoneVideosModule.base', 'Masterminds'),
              'url' => ['/stepstone_videos/index/masterminds'],
              //'url' => [$container->createUrl('/stepstone_videos/index/masterminds')],
              'sortOrder' => 400,
              'isActive' => MenuLink::isActiveState('stepstone_videos', 'index', 'masterminds')
          ]));

          $this->addEntry(new MenuLink([
              'id' => 'videos-training-videos',
              'icon' => 'fa-chalkboard-teacher',
              'label' => Yii::t('StepstoneVideosModule.base', 'Training Videos'),
              'url' => ['/stepstone_videos/index/training-videos'],
              //'url' => [$container->createUrl('/stepstone_videos/index/training-videos')],
              'sortOrder' => 500,
              'isActive' => MenuLink::isActiveState('stepstone_videos', 'index', 'training-videos')
          ]));

          $this->addEntry(new MenuLink([
              'id' => 'videos-case-studies',
              'icon' => 'fa-books',
              'label' => Yii::t('StepstoneVideosModule.base', 'Case Studies'),
              'url' => ['/stepstone_videos/index/case-studies'],
              //'url' => [$container->createUrl('/stepstone_videos/index/case-studies')],
              'sortOrder' => 600,
              'isActive' => MenuLink::isActiveState('stepstone_videos', 'index', 'case-studies')
          ]));


//          $this->addEntry(new MenuLink([
//              'id' => 'videos-admin',
//              'icon' => 'fa-plus',
//              'label' => Yii::t('StepstoneVideosModule.base', 'Add Video'),
//              'url' => ['/stepstone_videos/admin/index'],
//              //'url' => [$container->createUrl('/stepstone_videos/admin/index')],
//              'sortOrder' => 9000,
//              'isActive' => MenuLink::isActiveState('stepstone_videos', 'admin', 'index'),
//              'isVisible' => Yii::$app->user->isAdmin()
//              //'isVisible' => Yii::$app->user->getIdentity()->isSystemAdmin()    
//          ]));
//
//          $this->addEntry(new MenuLink([
//              'id' => 'videos-tags-admin',
//              'icon' => 'fa-tag',
//              'label' => Yii::t('StepstoneVideosModule.base', 'Video Tags'),
//              'url' => ['/stepstone_videos/admin/tags'],
//              //'url' => [$container->createUrl('/stepstone_videos/admin/tags')],
//              'sortOrder' => 9200,
//              'isActive' => MenuLink::isActiveState('stepstone_videos', 'admin', 'tags'),
//              'isVisible' => Yii::$app->user->isAdmin()
//          ]));
        }        
                
                
        

        parent::init();
    }

}
