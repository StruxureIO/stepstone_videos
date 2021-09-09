<?php

namespace humhub\modules\stepstone_videos\widgets;

use Yii;
use yii\helpers\Url;
use yii\db\Query;
use humhub\components\Widget;
use humhub\modules\stepstone_videos\models\Videos;

/**
 *
 * @author Felli
 */
class VideoDashboard extends Widget
{
    public $contentContainer;

    /**
     * @inheritdoc
     */
    public function run() {
      
      $videos = (new Query())
              ->select('videos.*')
              ->from('videos')
              ->orderBy(['date_added' => SORT_DESC])
              ->limit(2)
              ->all();
               
      return $this->render('videodashboard', ['videos' => $videos]);
    }
}
