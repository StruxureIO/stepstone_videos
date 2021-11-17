<?php

namespace humhub\modules\stepstone_videos\models;

use humhub\modules\space\models\Membership;
use humhub\modules\space\models\Space;
use humhub\modules\stepstone_videos\notifications\VideoAdded;
use humhub\modules\user\models\User;
use Yii;
use yii\web\UploadedFile;
use humhub\modules\content\models\Content;
use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\search\interfaces\Searchable;
use humhub\modules\search\events\SearchAddEvent;
use humhub\modules\stepstone_videos\activities\NewVideo;
use humhub\modules\stepstone_videos\widgets\VideoWall;

//include "protected/modules/videos/activities/NewVideo.php";


/**
 * This is the model class for table "videos".
 *
 * @property int $id
 * @property string $video_title
 * @property string $embed_code
 * @property string $description
 * @property string $date_added
 * @property string|null $tags
 * @property file $image
 * @property string|null image_url
 * @property int $views
 * @property string|null $created_at
 * @property int|null $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
**/
//class Videos extends \yii\db\ActiveRecord implements Searchable
class VideosContent extends ContentActiveRecord implements Searchable
{
    /**
     * {@inheritdoc}
     */
    public $image;

    public $contentContainer;

    public $wallEntryClass = "humhub\modules\stepstone_videos\widgets\VideoWall";

    public static function tableName()
    {
        return 'videos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['video_title', 'embed_code', 'description'], 'required'],
            [['embed_code', 'description', 'tags', 'image_url'], 'string'],
            [['date_added', 'created_at', 'updated_at'], 'safe'],
            [['views', 'created_by', 'updated_by'], 'integer'],
            [['image'],'file', 'skipOnEmpty' => true, 'extensions' => 'png,gif,jpg'],
            [['video_title'], 'string', 'max' => 120],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Video ID',
            'video_title' => 'Video Title',
            'embed_code' => 'Embed Code',
            'description' => 'Description',
            'date_added' => 'Date Added',
            'tags' => 'Tags',
            'image' => 'Upload Video Thumbnail',
            'image_url' => 'Video Thumbnail File',
            'views' => 'Views',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
      
        if($this->content->contentcontainer_id != null) {
          
          $users = $this->findSpaceMembers($this->content->contentcontainer_id);

          //Sending notification
          if (!empty($users)) {
              $notification = VideoAdded::instance()
                  ->from($this->createdBy)
                  ->about($this);
              $notification->sendBulk($users);
          }
        }

        parent::afterSave($insert, $changedAttributes);

    }

    /**
     * @param $spaceContainerId integer the space content container id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findSpaceMembers(int $spaceContainerId)
    {
        $space = Space::find()
            ->where(['contentcontainer_id' => $spaceContainerId])
            ->one();
        $members = Membership::find()
            ->where(['space_id' => $space->id])
            ->asArray()
            ->all();
        $usersIds = array_map(function ($member) {
            return $member['user_id'];
        }, $members);
        return User::find()
            ->where(['in', 'id', $usersIds])
            ->all();
    }

    public function getSearchAttributes()
    {
      $attributes = [
        'title' => $this->video_title,
        'description' => $this->description
      ];

      $this->trigger(self::EVENT_SEARCH_ADD, new SearchAddEvent($attributes));

      return $attributes;
    }

    public function getWallOut($params = Array())
    {
        return VideoWall::widget(['videos' => $this]);
    }

    public function videoAdded($id) {

      $activity = new \humhub\modules\stepstone_videos\activities\NewVideo();
      $activity->source = $this;
      $activity->originator = Yii::$app->user->getIdentity();
      $activity->create();

    }

}
