<?php 
use yii\helpers\Html;
use yii\grid\GridView;


\humhub\modules\stepstone_videos\assets\Assets::register($this);

$this->title = 'Videos';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="container-fluid">
    <div class="panel panel-default">
        <div class="panel-heading"><strong>Videos</strong> <?= Yii::t('StepstoneVideosModule.base', 'Administration') ?></div>
    
        <div class="panel-body">
            <!--<p>< ?= Yii::t('StepstoneVideosModule.base', 'Welcome to the admin only area.') ? ></p>-->
          

          <?php   echo $this->render('_search', ['model' => $searchModel]); ?>

<!--          <p id="tag-button-row">
            < ?= Html::a('Add Video', ['add'], ['class' => 'btn btn-default']) ?>
          </p>-->

          <div id="video-grid-container">
            
          <?= GridView::widget([
              'dataProvider' => $dataProvider,
              'filterModel' => $searchModel,
              'tableOptions' => ['class' => 'table'],
              'summary'=>'',
              'showFooter'=>false,
              'showHeader' => false,        
              'columns' => [
                  ['class' => 'yii\grid\SerialColumn'],
                    'video_title',
                    'description',
                  ['class' => 'yii\grid\ActionColumn',
                      'template' => '{update} {delete}',
                      'buttons' => [
                          'delete' => function($url, $model){
                              return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->id], [
                                  'class' => '',
                                  'data' => [
                                      'confirm' => 'Are you sure you want to delete ' . $model->video_title  .'?',
                                      'method' => 'post',
                                  ],
                              ]);
                          }
                      ]
                  ],              
              ],
              
          ]); ?>

          </div>
                              
      </div>
    </div>
  </div>
</div>
