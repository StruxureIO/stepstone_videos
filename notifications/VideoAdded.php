<?php

namespace humhub\modules\stepstone_videos\notifications;

use humhub\modules\notification\components\BaseNotification;
use humhub\modules\stepstone_videos\models\VideosContent;
use Yii;
use yii\db\IntegrityException;


class VideoAdded extends BaseNotification
{
    public $moduleId = 'stepstone_videos';
    public $requireOriginator = false;
    public $requireSource = false;

    /**
     * @inheritdoc
     */
    public $viewName = 'video-added';

    /**
     * @inheritdoc
     * @throws IntegrityException
     */
    public function getUrl()
    {
        if ($this->originator === null) {
            throw new IntegrityException('Originator cannot be null.');
        }

        return $this->originator->getUrl();
    }

    /**
     * @inheritdoc
     */
    public function html()
    {
        /**@var VideosContent $videoContent */
        $videoContent = $this->source;
        return Yii::t('UserModule.notification', 'User {displayName} has added the Video - {videoName}.', [
            'displayName' => $this->originator->displayName,
            'videoName' => $videoContent->video_title
        ]);
    }
}
