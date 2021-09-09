<?php

use yii\helpers\Html;
use yii\helpers\Url;
use humhub\modules\stepstone_videos\VideoTags;

\humhub\modules\stepstone_videos\assets\Assets::register($this);

?>

<div class="container-fluid">
    <div class="panel panel-default">
        <div class="panel-heading"><strong>Videos</strong> <?= Yii::t('StepstoneVideosModule.base', 'Edit Tags') ?></div>

        <div class="panel-body">
          <input type="hidden" id="next-tag-id" value="1">
          <table id="tag-list">
            <thead>
              <tr>
                <td class="tb-tag-name"><strong>Tag Name</strong></td>
                <!--<td class="tb-tag-icon center"><strong>Icon</strong></td>-->
                <!--<td class="tb-tag-menu center"><strong>Menu</strong></td>-->
                <td class="tb-tag-force-top center"><strong>Force Top</strong></td>
                <td class="tb-tag-force-top center"><strong>Hide Top</strong></td>
                <td class="tb-tag-edit center"><strong>Edit</strong></td>
                <td class="tb-tag-delete center"><strong>Delete</strong></td>
              </tr>
            </thead>
            <tbody>
              <?php
                foreach($model as $tag) { 
                  
//                  $menu_checked = ($tag->menu) ? '<i class="far fa-check"></i>' : '';
                  $force_top_checked = ($tag->force_top) ? '<i class="far fa-check"></i>' : '';
                  $hide_top_checked = ($tag->hide_top) ? '<i class="far fa-check"></i>' : '';
                  //$hide_top_checked = '';
                  
                ?>
              
                <tr data-id="<?php echo $tag->tag_id ?>">
                  <td class="tb-tag-name"><?php echo $tag->tag_name ?></td>
                  <!--<td class="tb-tag-icon center"><i class="< ?php echo $tag->icon ?>"></i></td>-->
                  <!--<td class="tb-tag-menu center">< ?php echo $menu_checked ?></td>-->
                  <td class="tb-tag-force-top center"><?php echo $force_top_checked ?></td>                  
                  <td class="tb-tag-force-top center"><?php echo $hide_top_checked ?></td>                  
                  <td class="tb-tag-edit center"><a href="index.php?r=stepstone_videos%2Fvideos%2Fupdatetag&amp;id=<?php echo $tag->tag_id ?>&cguid=<?php echo $cguid ?>"><i class="far fa-edit"></i></i></a></td>
                  <td class="tb-tag-delete center"><?= Html::a('<i class="fal fa-trash-alt"></i>', ['deletetag', 'id' => $tag->tag_id, 'cguid' => $cguid], [
                    'class' => 'step-delete-tag',
                    'data' => [
                      'confirm' => 'Are you sure you want to delete this item?',
                      'method' => 'post',
                    ],
                  ]) ?></td>
                </tr>
                                    
                <?php  
                }
              
              ?>
              
            </tbody>
          </table>
          <div id="tag-button-row">
            <a id="step-add-video-tag" href="<?php echo Url::base() ?>/index.php?r=stepstone_videos%2Fvideos%2Faddtag&cguid=<?php echo $cguid ?>" class="btn btn-default">Add Tag</a>&nbsp;&nbsp;
            <!--http://localhost/humhub/index.php?r=stepstone_videos%2Fvideos%2Faddtags&cguid=178fdc90-6ef5-4b12-ba86-d66d2a018776-->
          </div>  
        </div>
    </div>
</div>
