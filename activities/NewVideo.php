<?php

namespace humhub\modules\stepstone_videos\activities;

use humhub\modules\activity\components\BaseActivity;
use humhub\modules\activity\interfaces\ConfigurableActivityInterface;
use Yii;

class NewVideo extends BaseActivity implements ConfigurableActivityInterface
{

    /**
     * @inheritdoc
     */
    public $moduleId = 'videos';
    
    /**
     * @inheritdoc
     */
    public $viewName = 'newVideo';

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return Yii::t('StepstoneVideosModule.activities', 'Videos');
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return Yii::t('StepstoneVideosModule.activities', 'Whenever a new video is added.');
    }

}
