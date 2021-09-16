<?php

use yii\db\Migration;

/**
 * Class m210908_173924_videos_inital
 */
class m210908_173924_videos_inital extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      
      $this->createTable('videos', [
        'id' => 'pk',
        'video_title' => 'varchar(120) NOT NULL',
        'embed_code' => 'text NOT NULL',
        'description' => 'text NOT NULL',
        'date_added' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
        'tags' => 'text',
        'image_url' => 'text',
        'views' => "bigint(20) NOT NULL DEFAULT '0'",
        'created_at' => 'datetime DEFAULT NULL',
        'created_by' => 'int(11) DEFAULT NULL',
        'updated_at' => 'datetime DEFAULT NULL',
        'updated_by' => 'int(11) DEFAULT NULL',
      ], '');
      
      $this->createTable('video_tag_list', [
        'list_id' => 'pk',
        'video_id' => 'bigint(20) NOT NULL',
        'tag_id' => 'bigint(20) NOT NULL',
      ], '');
      
      $this->createTable('video_tags', [
        'tag_id' => 'pk',
        'tag_name' => 'varchar(40) NOT NULL',
        'icon' => 'varchar(30) NOT NULL',
        'menu' => "tinyint(1) NOT NULL DEFAULT '0'",
        'force_top' => "tinyint(1) NOT NULL DEFAULT '0'",
        'views' => "bigint(20) NOT NULL DEFAULT '0'",
        'hide_top' => "tinyint(4) NOT NULL DEFAULT '0'",
      ], '');
      
      $this->createTable('videos_favorites', [
        'fav_id' => 'pk',
        'user_id' => 'bigint(20) NOT NULL',
        'video_id' => 'int(11) NOT NULL',
      ], '');
      
      $this->insert('video_tags', [
          'tag_id' => 1,
          'tag_name' => 'Masterminds',
          'icon' => 'fal fa-book',
          'menu' => 0,
          'force_top' => 0,
          'views' => 0,
          'hide_top' => 0,
      ]);
      
      $this->insert('video_tags', [
          'tag_id' => 2,
          'tag_name' => 'Training Videos',
          'icon' => 'fad fa-books',
          'menu' => 0,
          'force_top' => 0,
          'views' => 0,
          'hide_top' => 0,
      ]);
      
      $this->insert('video_tags', [
          'tag_id' => 5,
          'tag_name' => 'Sub-Transactions',
          'icon' => 'far fa-envelope-open-dollar',
          'menu' => 0,
          'force_top' => 0,
          'views' => 0,
          'hide_top' => 0,
      ]);
      
      $this->insert('video_tags', [
          'tag_id' => 6,
          'tag_name' => 'IRAs',
          'icon' => 'far fa-door-closed',
          'menu' => 0,
          'force_top' => 0,
          'views' => 0,
          'hide_top' => 0,
      ]);
      
      $this->insert('video_tags', [
          'tag_id' => 7,
          'tag_name' => 'Case Studies',
          'icon' => 'fal fa-globe-europe',
          'menu' => 0,
          'force_top' => 0,
          'views' => 0,
          'hide_top' => 0,
      ]);
      
      $this->insert('video_tags', [
          'tag_id' => 9,
          'tag_name' => 'Foreclosures',
          'icon' => 'fal fa-angry',
          'menu' => 0,
          'force_top' => 0,
          'views' => 0,
          'hide_top' => 0,
      ]);
      
      $this->insert('video_tags', [
          'tag_id' => 10,
          'tag_name' => 'Leasing',
          'icon' => 'fal fa-bed-empty',
          'menu' => 0,
          'force_top' => 0,
          'views' => 0,
          'hide_top' => 0,
      ]);
      
      $this->insert('video_tags', [
          'tag_id' => 11,
          'tag_name' => 'Taxes',
          'icon' => 'far fa-file-invoice-dollar',
          'menu' => 0,
          'force_top' => 0,
          'views' => 0,
          'hide_top' => 0,
      ]);
      
      $this->insert('video_tags', [
          'tag_id' => 12,
          'tag_name' => 'Loans',
          'icon' => 'far fa-money-bill-wave',
          'menu' => 0,
          'force_top' => 0,
          'views' => 0,
          'hide_top' => 0,
      ]);      

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210908_173924_videos_inital cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210908_173924_videos_inital cannot be reverted.\n";

        return false;
    }
    */
}
