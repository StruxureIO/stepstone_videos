<?php

namespace humhub\modules\stepstone_videos\widgets;
use humhub\modules\content\widgets\stream\WallStreamModuleEntryWidget;

class VideoWall extends WallStreamModuleEntryWidget
{
  
    public $jsWidget = '';

    public $videos;
  
    protected function renderContent()
    {
        return $this->render('videoWall', ['videos' => $this->model]);
    }

    protected function getIcon()
    {
        return 'video';
    }

    protected function getTitle()
    {
        return $this->model->video_title;
    }
}

?>