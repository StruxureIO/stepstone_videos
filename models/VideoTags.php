<?php

namespace humhub\modules\stepstone_videos\models;

use Yii;

/**
 * This is the model class for table "video_tags".
 *
 * @property int $tag_id
 * @property string $tag_name
 * @property string|null $icon
 * @property int|null $menu
 * @property int $force_top
 * @property int $views
 * @property int $hide_top
 */
class VideoTags extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'video_tags';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tag_name'], 'required'],
            [['menu', 'force_top', 'views', 'hide_top'], 'integer'],
            [['tag_name'], 'string', 'max' => 40],
            [['icon'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tag_id' => 'Tag ID',
            'tag_name' => 'Tag Name',
            'icon' => 'Icon',
            'menu' => 'Menu',
            'force_top' => 'Force Top',
            'views' => 'Views',
            'hide_top' => 'Hide Top',
        ];
    }
}
