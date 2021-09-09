<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use humhub\modules\content\helpers\ContentContainerHelper;


\humhub\modules\stepstone_videos\assets\Assets::register($this);

$this->title = 'Videos';
$this->params['breadcrumbs'][] = $this->title;
$container = ContentContainerHelper::getCurrent();
$container_guid = ($container) ? $container->guid : null;

?>

<div class="container-fluid">
    <div class="panel panel-default">
        <div class="panel-heading"><strong>Videos</strong> <?= Yii::t('StepstoneVideosModule.base', 'Administration') ?></div>
    
        <div class="panel-body">
            <!--<p>< ?= Yii::t('StepstoneVideosModule.base', 'Welcome to the admin only area.') ? ></p>-->
          

          <?php   echo $this->render('_search', ['model' => $searchModel]); ?>

          <p id="tag-button-row">
              <?= Html::a('Add Video', ['add', 'cguid' => $container_guid], ['class' => 'btn btn-default']) ?>
          </p>

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
                              'template'=>'{update} {delete}',
                              'buttons' => [
                                'update' => function ($url, $model) use ($container_guid) {
                                    $url .= '&cguid=' . $container_guid; 
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url);
                                },                    
                                'delete' => function ($url, $model) use ($container_guid) {
                                    $url .= '&cguid=' . $container_guid; 
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url);
                                },                    
                              ],
                  ],
              ],
          ]); ?>
            
            
    <!--    < ?= GridView::widget([
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
                              'template'=>'{update} {delete}',
                              'urlCreator' => function ($action, $model, $key, $index) {
                                if ($action === 'update') {
                                    //$url ='index.php?r=stepstone_videos/videos/update&id='.$model->id.'&cguid=';
                                    //return $url;
                                    return Url::to(['videos/update', 'id' => $model->id, 'cguid' => $container_guid]);
                                }
                                if ($action === 'delete') {
                                    $url ='index.php?r=stepstone_videos/videos/delete&id='.$model->id.'&cguid=';
                                    return $url;
                                }
                              }                  
                      //https://stackoverflow.com/questions/31241336/yii2-pass-variable-from-view-to-gridview-custom-action-columns
                  ],
              ],
          ]); ?> -->

          </div>
                              
      </div>
    </div>
  </div>
</div>
