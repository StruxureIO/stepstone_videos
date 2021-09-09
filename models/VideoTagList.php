<?php

namespace humhub\modules\stepstone_videos\models;

use Yii;

/**
 * This is the model class for table "video_tag_list".
 *
 * @property int $list_id
 * @property int $video_id
 * @property int $tag_id
 */
class VideoTagList extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'video_tag_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['video_id', 'tag_id'], 'required'],
            [['video_id', 'tag_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'list_id' => 'List ID',
            'video_id' => 'Video ID',
            'tag_id' => 'Tag ID',
        ];
    }
}
