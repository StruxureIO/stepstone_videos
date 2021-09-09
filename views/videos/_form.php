<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Country */
/* @var $form yii\widgets\ActiveForm */
//$menu_checked = ($model->menu) ? 'checked' : '';
$force_top_checked = ($model->force_top) ? 'checked' : '';
$hide_top_checked = ($model->hide_top) ? 'checked' : '';

?>
<div class="container-fluid">
  <div class="panel panel-default">
    <div class="panel-heading"><?= Html::encode($this->title) ?></div>
      <div id="video-tags-form">
    
      <?php $form = ActiveForm::begin(); ?>
    
          <input type="hidden" name="VideoTags[tag_id]" value="<?php echo $model->tag_id ?>">
          <?php if(isset($model->tag_id)) { ?>
            <input type="hidden" id="video-tags-icon" name="VideoTags[icon]" value="<?php echo $model->icon ?>">
            <input type="hidden" id="menu" name="VideoTags[menu]" value="<?php echo $model->menu ?>">
            <input type="hidden" id="force_top" name="VideoTags[force_top]" value="<?php echo $model->force_top ?>">
            <input type="hidden" id="hide_top" name="VideoTags[hide_top]" value="<?php echo $model->hide_top ?>">
          <?php } else { ?>
            <input type="hidden" id="video-tags-icon" name="VideoTags[icon]" value="">
            <input type="hidden" id="menu" name="VideoTags[menu]" value="0">
            <input type="hidden" id="force_top" name="VideoTags[force_top]" value="0">
            <input type="hidden" id="hide_top" name="VideoTags[hide_top]" value="0">
          <?php } ?>
          
          
          <table id="tag-list" class="table-padding">
            <thead>
              <tr>
                <td class="tb-tag-name"><strong>Tag Name</strong></td>
                <!--<td class="tb-tag-icon center"><strong>Icon</strong></td>-->
                <!--<td class="tb-tag-edit-icon"></td>-->
                <!--<td class="tb-tag-menu center"><strong>Menu</strong></td>-->
                <td class="tb-tag-force-top center"><strong>Force Top</strong></td>
                <td class="tb-tag-hide-top center"><strong>Hide Top</strong></td>
              </tr>
            </thead>
            <tbody>
              <tr data-id="<?php echo $model->tag_id ?>">
                <td class="tb-tag-name"><input class="step-video-tag" name="VideoTags[tag_name]" type="text" value="<?php echo $model->tag_name ?>"></td>                
                <!--<td class="tb-tag-icon center"><i class="< ?php echo $model->icon ?>"></i></td>-->
                <!--<td class="tb-tag-edit-icon"><a class="step-choose-icon">Edit Icon</a></td>-->

                <!--<td class="tb-tag-menu center"><label class="switch"><input class="step-tag-menu" type="checkbox" < ?php echo $menu_checked ?> ><span class="slider round"></span></label></td>-->
                <td class="tb-tag-force-top center"><label class="switch"><input class="step-tag-forcetop" type="checkbox" <?php echo $force_top_checked ?> ><span class="slider round"></span></label></td>                  
                <td class="tb-tag-hide-top center"><label class="switch"><input class="step-tag-hidetop" type="checkbox" <?php echo $hide_top_checked ?> ><span class="slider round"></span></label></td>
                
              </tr>                              
            </tbody>
          </table>
<!--    <p>
      <a id="fontawesome-link" href="https://fontawesome.com/icons?d=gallery&p=2" target="_blank">Click here to find an icon</a>
    </p>-->
                        
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-default']) ?>
    </div>
    
    <!--display validation errors-->
    <?= $form->errorSummary($model); ?>    
       

    <?php ActiveForm::end(); ?>
    </div>
  </div>
</div>

