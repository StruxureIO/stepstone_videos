<?php

namespace humhub\modules\stepstone_videos\models;

use Yii;

/**
 * This is the model class for table "videos_favorites".
 *
 * @property int $fav_id
 * @property int $user_id
 * @property int $video_id
 */
class VideosFavorites extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'videos_favorites';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'video_id'], 'required'],
            [['user_id', 'video_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'fav_id' => 'Fav ID',
            'user_id' => 'User ID',
            'video_id' => 'Video ID',
        ];
    }
}
