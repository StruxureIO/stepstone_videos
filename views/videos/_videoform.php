<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$thumbnail_style = '';
?>

<div class="col-md-12">
            
  <div class="container-fluid">
      <div class="panel panel-default">
        <div class="panel-heading"><strong><?php echo $type ?> Video</strong></div>
        
          <div class="panel-body">
            
          <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>            
          <div>  

            <?= $form->field($model, 'video_title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'description')->textarea(['rows' => '3']) ?>

            <?= $form->field($model, 'embed_code')->textarea(['rows' => '3']) ?>
            
            <p id="thumbnail-button-row">
              <a id="generate-thumbnail" class="btn-primary">Generate Thumbnail Image</a>
            </p>
                        
            <?= $form->field($model, 'image_url')->textInput(['maxlength' => true]) ?>
            <div>
              <?php
                if(empty($model->image_url))
                  $thumbnail_style = 'style="display: none"';                
                $image_url = Url::base() . '/' . $model->image_url;
              ?>
              <img id="video-thumbnail-image" src="<?php echo $image_url ?>" <?php echo $thumbnail_style?> >
            </div>
                        
            <?= $form->field($model, 'image')->fileInput() ?>
            
            <input type="hidden" id="video-tags" name="Videos[tags]" value="<?php echo $model->tags ?>">
            
          </div>  

          <div class="form-group ">            
            <label class="control-label" for="videos-embed_code">Tags</label>            
            <ul id="tag-list">
              
              <?php
              
                $selected_tags = explode(',', $model->tags);      
                foreach($tags as $tag) {     
                  //print_r($tag);
                  
                  if(in_array($tag->tag_id, $selected_tags))
                    $checked = 'checked';      
                  else
                    $checked = '';      
                  
                  echo '<li><label class="switch"><input class="step-video-tag" type="checkbox" '.$checked.' data-id="'.$tag->tag_id.'" ><span class="slider round"></span></label><span class="tag-label">'.$tag->tag_name.'</span></li>';                  
                }
              ?>  
              
              
            </ul>  
          </div>  

          <div class="form-group">
              <?= Html::submitButton('Save', ['class' => 'btn btn-default', 'cguid' => $cguid]) ?>
          </div>

          <?php ActiveForm::end(); ?>
                        
          </div>

      </div>
  </div>
</div>

