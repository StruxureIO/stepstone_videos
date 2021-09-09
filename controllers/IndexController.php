<?php

namespace humhub\modules\stepstone_videos\controllers;

use humhub\components\Controller;
use humhub\modules\stepstone_videos\models\VideoTags;
use humhub\modules\stepstone_videos\models\Videos;
use Yii;
use yii\helpers\Url;
use yii\db\Query;
//use humhub\modules\stepstone_videos\models\VideoTags;
//use humhub\modules\stepstone_videos\models\Videos;
//use humhub\modules\stepstone_videos\models\VideosFavorites;

//include "protected/modules/videos/models/VideoTags.php";
//include "protected/modules/videos/models/Videos.php";
//include "protected/modules/videos/models/VideosFavorites.php";

class IndexController extends Controller
{

    public $subLayout = "@stepstone_videos/views/layouts/default";
    public $mFavoritesList;
    public $mVideos;

    /**
     * Renders the index view for the module
     *
     * @return string
     */
    public function actionIndex(){
            
      $video_tags = VideoTags::find()->orderBy('tag_name')->all();            
      
      // not working correctly
      //$assetsUrl = $this->module->assetsUrl;
            
      $top_tags = (new Query())
                ->select(['video_tags.*'])
                ->from('video_tags')
                ->where('views > 0 and hide_top = 0') 
                ->orWhere(['force_top' => 1])
                ->limit(6)
                ->orderBy(['force_top' => SORT_DESC, 'views' => SORT_DESC])
                ->all();
      
      return $this->render('index', ['tags' => $video_tags, 'top_tags' => $top_tags]);
    }
        
    public function actionAjaxView($tag, $tag_name, $page = 0){
      
      $req = Yii::$app->request;
            
      $tag = $req->get('tag', "all");
      
      $tag_name = $req->get('tag_name', "All Tags");
      
      $page = $req->get('page', 0);
      
      $search_text = $req->get('search_text', '');
                                       
      $current_user_id = \Yii::$app->user->identity->ID;
      $icon = '';
      
      if($search_text != '') {
        
        $count = (new Query())
              ->select(['videos.id'])
              ->from('videos')
              ->join('left join', 'videos_favorites', 'videos.id = videos_favorites.video_id and videos_favorites.user_id = ' . $current_user_id)
              ->where([ 'OR' ,
                          [ 'like' , 'video_title' , $search_text ],
                          [ 'like' , 'description' , $search_text ],
                      ])
              ->orderBy(['date_added' => SORT_DESC])
              ->groupBy(['videos.id'])
              ->count();
        
        $offset = $page * MAX_ITEMS;
				$total_number_pages = ceil($count / MAX_ITEMS);        
                        
        $videos = (new Query())
                ->select(['videos.*', 'videos_favorites.fav_id as favorite'])
                ->from('videos')
                ->join('left join', 'videos_favorites', 'videos.id = videos_favorites.video_id and videos_favorites.user_id = ' . $current_user_id)
                ->where([ 'OR' ,
                            [ 'like' , 'video_title' , $search_text ],
                            [ 'like' , 'description' , $search_text ],
                        ])
                ->orderBy(['date_added' => SORT_DESC])
                ->groupBy(['videos.id'])
                //->limit(1)
                ->limit(MAX_ITEMS)
                ->offset($offset)
                ->all();
                            
      } else if($tag == 'all') {
                
        $count = (new Query())
          ->from('videos')
          ->count();
        
        $offset = $page * MAX_ITEMS;
				$total_number_pages = ceil($count / MAX_ITEMS);        
      
        $videos = (new Query())
                ->select(['videos.*', 'videos_favorites.fav_id as favorite'])
                ->from('videos')
                ->join('left join', 'videos_favorites', 'videos.id = videos_favorites.video_id and videos_favorites.user_id = ' . $current_user_id)
                ->orderBy(['date_added' => SORT_DESC])
                ->groupBy(['videos.id'])
                ->limit(MAX_ITEMS)
                ->offset($offset)
                ->all();
        
          // use to view the SQL
//        $command = (new \yii\db\Query())      
//                ->select(['videos.*', 'videos_favorites.fav_id as favorite'])
//                ->from('videos')
//                ->join('left join', 'videos_favorites', 'videos.id = videos_favorites.video_id and videos_favorites.user_id = ' . $current_user_id)
//                ->orderBy(['video_title' => SORT_ASC])
//                ->groupBy(['videos.id'])
//                ->createCommand();
//        
//        echo $command->sql;
//
//        $videos = $command->queryAll();
                
      } else {  
                                
        $video_tag = VideoTags::find()->where(['tag_id' => $tag])->one();
        $video_tag->views = $video_tag->views + 1;
        $video_tag->save();        
        
        $icon = $video_tag->icon;
        
        $count = (new Query())
                ->select(['videos.id'])
                ->from('videos')
                ->leftJoin('video_tag_list', 'videos.id = video_tag_list.video_id')
                ->join('left join', 'videos_favorites', 'videos.id = videos_favorites.video_id and videos_favorites.user_id = ' . $current_user_id)
                ->where(['tag_id' => $tag])
                ->orderBy(['video_title' => SORT_ASC])
                ->groupBy(['videos.id'])
                ->count();
        
        $offset = $page * MAX_ITEMS;
				$total_number_pages = ceil($count / MAX_ITEMS);        
              
        $videos = (new Query())
                ->select(['videos.*', 'videos_favorites.fav_id as favorite'])
                ->from('videos')
                ->leftJoin('video_tag_list', 'videos.id = video_tag_list.video_id')
                ->join('left join', 'videos_favorites', 'videos.id = videos_favorites.video_id and videos_favorites.user_id = ' . $current_user_id)
                ->where(['tag_id' => $tag])
                ->orderBy(['video_title' => SORT_ASC])
                ->groupBy(['videos.id'])
                ->limit(MAX_ITEMS)
                ->offset($offset)
                ->all();
        
          // use to view the SQL
//        $command = (new \yii\db\Query())      
//                ->select(['videos.*', 'videos_favorites.fav_id as favorite'])
//                ->from('videos')
//                ->leftJoin('video_tag_list', 'videos.id = video_tag_list.video_id')
//                ->join('left join', 'videos_favorites', 'videos.id = videos_favorites.video_id and videos_favorites.user_id = ' . $current_user_id)
//                ->where(['tag_id' => $tag])
//                ->orderBy(['video_title' => SORT_ASC])
//                ->groupBy(['videos.id'])
//                ->createCommand();
//        echo $tag . " ";
//        echo $command->sql;
//
//        $videos = $command->queryAll();
            
      }
           
      //echo $this->module->assetsUrl.'/images/video-cover.jpg';
      
      //echo Yii::app()->assetManager->publish('videos');
            
      return $this->renderPartial('_view', [
        'videos' => $videos,
        'tag_name' => $tag_name,
        'page' => $page,
        'total_number_pages' => $total_number_pages  
      ]);
      
      die();
    }
    
    public function actionAjaxFavorite($user_id, $video_id, $status) {
      
      if($status == 'true') {
        
        $new_favorites = new \humhub\modules\stepstone_videos\models\VideosFavorites();
        $new_favorites->user_id = $user_id;
        $new_favorites->video_id = $video_id;
        $new_favorites->save();
                        
      } else {
      
        $this->mFavoritesList = new \humhub\modules\stepstone_videos\models\VideosFavorites();

        $result = $this->mFavoritesList->findOne(['user_id' => $user_id, 'video_id' => $video_id]);      
        if($result)
          $result->delete();      
              
      }

      die();
      
    }    
    
    public function actionAjaxFavorites() {
      
      $req = Yii::$app->request;
                  
      $page = $req->get('page', 0);
                  
      $current_user_id = \Yii::$app->user->identity->ID;
      
      $count = (new Query())
            ->select(['videos.id'])
            ->from('videos')
            ->join('left join', 'videos_favorites', 'videos.id = videos_favorites.video_id and videos_favorites.user_id = ' . $current_user_id)
            ->where(['not', ['videos_favorites.fav_id' => null]])
            ->orderBy(['video_title' => SORT_DESC])
            ->groupBy(['videos.id'])
            ->count();

      $offset = $page * MAX_ITEMS;
      $total_number_pages = ceil($count / MAX_ITEMS);        
                  
      $videos = (new Query())
              ->select(['videos.*', 'videos_favorites.fav_id as favorite'])
              ->from('videos')
              ->join('left join', 'videos_favorites', 'videos.id = videos_favorites.video_id and videos_favorites.user_id = ' . $current_user_id)
              ->where(['not', ['videos_favorites.fav_id' => null]])
              ->orderBy(['video_title' => SORT_DESC])
              ->groupBy(['videos.id'])
              ->limit(MAX_ITEMS)
              ->offset($offset)
              ->all();            
                    
      return $this->renderPartial('_view', [
        'videos' => $videos,
        'tag_name' => '',
        'page' => $page,
        'total_number_pages' => $total_number_pages  
      ]);
        
      die();
    }
    
    public function actionAjaxSearchFavorites() {
      
      $req = Yii::$app->request;
                  
      $search_text = $req->get('search_text', '');
      
      $page = $req->get('page', 0);
                  
      $current_user_id = \Yii::$app->user->identity->ID;
      
      $count = (new Query())
              ->select(['videos.id'])
              ->from('videos')
              ->join('left join', 'videos_favorites', 'videos.id = videos_favorites.video_id and videos_favorites.user_id = ' . $current_user_id)              
              ->where(
                      ['and',
                        ['not', ['fav_id' => null]],
                        ['like', 'video_title', $search_text]])
              ->orWhere(
                      ['and',
                        ['not', ['fav_id' => null]],
                        ['like', 'description', $search_text]])              
              ->orderBy(['video_title' => SORT_DESC])
              ->groupBy(['videos.id'])
              ->count();

      $offset = $page * MAX_ITEMS;
      $total_number_pages = ceil($count / MAX_ITEMS);        
       
      $videos = (new Query())
              ->select(['videos.*', 'videos_favorites.fav_id as favorite'])
              ->from('videos')
              ->join('left join', 'videos_favorites', 'videos.id = videos_favorites.video_id and videos_favorites.user_id = ' . $current_user_id)              
              ->where(
                      ['and',
                        ['not', ['fav_id' => null]],
                        ['like', 'video_title', $search_text]])
              ->orWhere(
                      ['and',
                        ['not', ['fav_id' => null]],
                        ['like', 'description', $search_text]])              
              ->orderBy(['video_title' => SORT_DESC])
              ->groupBy(['videos.id'])
              ->limit(MAX_ITEMS)
              ->offset($offset)
              ->all();            
                    
//      $command = (new \yii\db\Query())      
//              ->select(['videos.*', 'videos_favorites.fav_id as favorite'])
//              ->from('videos')
//              ->join('left join', 'videos_favorites', 'videos.id = videos_favorites.video_id and videos_favorites.user_id = ' . $current_user_id)
//              ->where(
//                      ['and',
//                        ['not', ['fav_id' => null]],
//                        ['like', 'video_title', $search_text]])
//              ->orWhere(
//                      ['and',
//                        ['not', ['fav_id' => null]],
//                        ['like', 'description', $search_text]])              
//              ->orderBy(['video_title' => SORT_DESC])
//              ->groupBy(['videos.id'])
//              ->createCommand();
//      
//      $query = $command->sql;
//
//      $videos = $command->queryAll();
//              
//      return $this->renderPartial('_view', [
//        'videos' => $videos,
//        'tag_name' => '',
//        'search_text' => $search_text,
//        'query' => $query
//      ]);
      
      return $this->renderPartial('_view', [
        'videos' => $videos,
        'tag_name' => '',
        'page' => $page,
        'total_number_pages' => $total_number_pages  
      ]);
              
      die();
    }
        
    public function actionAjaxPopular() {
      
      $req = Yii::$app->request;
            
      $tag = "all";
      
      $tag_name = "All Tags";
      
      $page = $req->get('page', 0);
      
      $search_text = '';
      
      $current_user_id = \Yii::$app->user->identity->ID;
      
      $count = (new Query())
              ->select(['videos.id'])
              ->from('videos')
              ->join('left join', 'videos_favorites', 'videos.id = videos_favorites.video_id and videos_favorites.user_id = ' . $current_user_id)
              ->orderBy(['views' => SORT_DESC])
              ->groupBy(['videos.id'])
              ->count();

      $offset = $page * MAX_ITEMS;
      $total_number_pages = ceil($count / MAX_ITEMS);        
                  
      $videos = (new Query())
              ->select(['videos.*', 'videos_favorites.fav_id as favorite'])
              ->from('videos')
              ->join('left join', 'videos_favorites', 'videos.id = videos_favorites.video_id and videos_favorites.user_id = ' . $current_user_id)
              ->orderBy(['views' => SORT_DESC])
              ->groupBy(['videos.id'])
              ->limit(MAX_ITEMS)
              ->offset($offset)
              ->all();            
        
      return $this->renderPartial('_view', [
        'videos' => $videos,
        'tag_name' => '',
        'page' => $page,
        'total_number_pages' => $total_number_pages  
      ]);
        
      die();
    }
    
    public function actionAjaxSearchPopular() {
      
      $req = Yii::$app->request;
                  
      $search_text = $req->get('search_text', '');
                              
      $current_user_id = \Yii::$app->user->identity->ID;
      
      $page = $req->get('page', 0);
                        
      $count = (new Query())
              ->select(['videos.id'])
              ->from('videos')
              ->join('left join', 'videos_favorites', 'videos.id = videos_favorites.video_id and videos_favorites.user_id = ' . $current_user_id)
              ->where(['like', 'video_title', $search_text])
              ->orWhere(['like' , 'description' , $search_text])
              ->orderBy(['views' => SORT_DESC])
              ->groupBy(['videos.id'])
              ->count();

      $offset = $page * MAX_ITEMS;
      $total_number_pages = ceil($count / MAX_ITEMS);        
      
                  
      $videos = (new Query())
              ->select(['videos.*', 'videos_favorites.fav_id as favorite'])
              ->from('videos')
              ->join('left join', 'videos_favorites', 'videos.id = videos_favorites.video_id and videos_favorites.user_id = ' . $current_user_id)
              ->where(['like', 'video_title', $search_text])
              ->orWhere(['like' , 'description' , $search_text])
              ->orderBy(['views' => SORT_DESC])
              ->groupBy(['videos.id'])
              ->limit(MAX_ITEMS)
              ->offset($offset)
              ->all();            
      
        
      return $this->renderPartial('_view', [
        'videos' => $videos,
        'tag_name' => '',
        'page' => $page,
        'total_number_pages' => $total_number_pages  
      ]);
        
      die();
    }
        
    public function actionWatch($video_id) {
      
      $current_user_id = \Yii::$app->user->identity->ID;
                  
      $this->mVideos = new \humhub\modules\stepstone_videos\models\Videos();
      $videos = $this->mVideos::find()->where(['id' => $video_id])->one();
      
      $videos->views = $videos->views + 1;
      $videos->save();        
      
      $this->mFavoritesList = new \humhub\modules\stepstone_videos\models\VideosFavorites();

      $result = $this->mFavoritesList->findOne(['user_id' => $current_user_id, 'video_id' => $video_id]);      
      $checked = ($result) ? 'checked' : ''; 

      return $this->render('watch', ['model' => $videos, 'checked' => $checked]);
    }    
        
    public function actionVideosMenu() { 
      return $this->render('VideosMenu'); 
    }    
        
    public function actionFavorites(){
      return $this->render('favorites');
    }
    
    public function actionPopular(){
      
      $video_tags = VideoTags::find()->orderBy('tag_name')->all();
      
      return $this->render('popular', ['model' => $video_tags]);
      
    }

    public function actionMasterminds(){
            
      return $this->render('masterminds');
      
    }        
    
    public function actionTrainingVideos(){
            
      return $this->render('training-videos');
      
    }        
    
    public function actionCaseStudies(){
            
      return $this->render('case-studies');
      
    }        
            
    protected function findModel($tag){
      
      if($tag == 'all')
        $videos = Videos::find()->orderBy('date_added')->all();
      else
        $videos = Videos::find()->where(['video_id' => '6'])->orderBy('date_added')->all();
        //$videos = Videos::find()->orderBy('date_added')->all();
      
      //if (($model = Videos::find()->orderBy('date_added')->all()) !== null) {
      if ($videos !== null) {
        return $videos;
      }

      throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function actionAjaxMasterminds() {
      
      $req = Yii::$app->request;
                  
      $page = $req->get('page', 0);
                  
      $current_user_id = \Yii::$app->user->identity->ID;
      
      $count = (new Query())
              ->select(['videos.id'])
              ->from('videos')
              ->leftJoin('video_tag_list', 'videos.id = video_tag_list.video_id')
              ->join('left join', 'videos_favorites', 'videos.id = videos_favorites.video_id and videos_favorites.user_id = ' . $current_user_id)
              ->where(['tag_id' => MASTERMINDS])
              ->orderBy(['video_title' => SORT_ASC])
              ->groupBy(['videos.id'])
              ->count();

      $offset = $page * MAX_ITEMS;
      $total_number_pages = ceil($count / MAX_ITEMS);        
                        
      $videos = (new Query())
              ->select(['videos.*', 'videos_favorites.fav_id as favorite'])
              ->from('videos')
              ->leftJoin('video_tag_list', 'videos.id = video_tag_list.video_id')
              ->join('left join', 'videos_favorites', 'videos.id = videos_favorites.video_id and videos_favorites.user_id = ' . $current_user_id)
              ->where(['tag_id' => MASTERMINDS])
              ->orderBy(['video_title' => SORT_ASC])
              ->groupBy(['videos.id'])
              ->limit(MAX_ITEMS)
              ->offset($offset)
              ->all();
      
                    
      return $this->renderPartial('_view', [
        'videos' => $videos,
        'tag_name' => 'Masterminds',
        'page' => $page,
        'total_number_pages' => $total_number_pages  
      ]);
        
      die();
    }
    
    public function actionAjaxSearchMasterminds() {
      
      $req = Yii::$app->request;
                  
      $search_text = $req->get('search_text', '');
      
      $page = $req->get('page', 0);
                  
      $current_user_id = \Yii::$app->user->identity->ID;
      
      $count = (new Query())
              ->select(['videos.id'])
              ->from('videos')
              ->leftJoin('video_tag_list', 'videos.id = video_tag_list.video_id')
              ->join('left join', 'videos_favorites', 'videos.id = videos_favorites.video_id and videos_favorites.user_id = ' . $current_user_id)              
              ->where(
                      ['and',
                        ['tag_id' => MASTERMINDS],
                        ['like', 'video_title', $search_text]])
              ->orWhere(
                      ['and',
                        ['tag_id' => MASTERMINDS],
                        ['like', 'description', $search_text]])              
              ->orderBy(['video_title' => SORT_DESC])
              ->groupBy(['videos.id'])
              ->count();

      $offset = $page * MAX_ITEMS;
      $total_number_pages = ceil($count / MAX_ITEMS);        

                        
      $videos = (new Query())
              ->select(['videos.*', 'videos_favorites.fav_id as favorite'])
              ->from('videos')
              ->leftJoin('video_tag_list', 'videos.id = video_tag_list.video_id')
              ->join('left join', 'videos_favorites', 'videos.id = videos_favorites.video_id and videos_favorites.user_id = ' . $current_user_id)              
              ->where(
                      ['and',
                        ['tag_id' => MASTERMINDS],
                        ['like', 'video_title', $search_text]])
              ->orWhere(
                      ['and',
                        ['tag_id' => MASTERMINDS],
                        ['like', 'description', $search_text]])              
              ->orderBy(['video_title' => SORT_DESC])
              ->groupBy(['videos.id'])
              ->limit(MAX_ITEMS)
              ->offset($offset)
              ->all();            
                          
      return $this->renderPartial('_view', [
        'videos' => $videos,
        'tag_name' => 'Masterminds',
        'page' => $page,
        'total_number_pages' => $total_number_pages  
      ]);
     
      die();
    }
    
    public function actionAjaxTrainingVideos() {
      
      $req = Yii::$app->request;
                  
      $page = $req->get('page', 0);
                  
      $current_user_id = \Yii::$app->user->identity->ID;
      
      $count = (new Query())
              ->select(['videos.id'])
              ->from('videos')
              ->leftJoin('video_tag_list', 'videos.id = video_tag_list.video_id')
              ->join('left join', 'videos_favorites', 'videos.id = videos_favorites.video_id and videos_favorites.user_id = ' . $current_user_id)
              ->where(['tag_id' => TRAINING_VIDEOS])
              ->orderBy(['video_title' => SORT_ASC])
              ->groupBy(['videos.id'])
              ->count();

      $offset = $page * MAX_ITEMS;
      $total_number_pages = ceil($count / MAX_ITEMS);        
                        
      $videos = (new Query())
              ->select(['videos.*', 'videos_favorites.fav_id as favorite'])
              ->from('videos')
              ->leftJoin('video_tag_list', 'videos.id = video_tag_list.video_id')
              ->join('left join', 'videos_favorites', 'videos.id = videos_favorites.video_id and videos_favorites.user_id = ' . $current_user_id)
              ->where(['tag_id' => TRAINING_VIDEOS])
              ->orderBy(['video_title' => SORT_ASC])
              ->groupBy(['videos.id'])
              ->limit(MAX_ITEMS)
              ->offset($offset)
              ->all();
      
                    
      return $this->renderPartial('_view', [
        'videos' => $videos,
        'tag_name' => 'Training Videos',
        'page' => $page,
        'total_number_pages' => $total_number_pages  
      ]);
        
      die();
    }
    
    public function actionAjaxSearchTrainingVideos() {
      
      $req = Yii::$app->request;
                  
      $search_text = $req->get('search_text', '');
      
      $page = $req->get('page', 0);
                  
      $current_user_id = \Yii::$app->user->identity->ID;
      
      $count = (new Query())
              ->select(['videos.id'])
              ->from('videos')
              ->leftJoin('video_tag_list', 'videos.id = video_tag_list.video_id')
              ->join('left join', 'videos_favorites', 'videos.id = videos_favorites.video_id and videos_favorites.user_id = ' . $current_user_id)              
              ->where(
                      ['and',
                        ['tag_id' => TRAINING_VIDEOS],
                        ['like', 'video_title', $search_text]])
              ->orWhere(
                      ['and',
                        ['tag_id' => TRAINING_VIDEOS],
                        ['like', 'description', $search_text]])              
              ->orderBy(['video_title' => SORT_DESC])
              ->groupBy(['videos.id'])
              ->count();

      $offset = $page * MAX_ITEMS;
      $total_number_pages = ceil($count / MAX_ITEMS);        

                        
      $videos = (new Query())
              ->select(['videos.*', 'videos_favorites.fav_id as favorite'])
              ->from('videos')
              ->leftJoin('video_tag_list', 'videos.id = video_tag_list.video_id')
              ->join('left join', 'videos_favorites', 'videos.id = videos_favorites.video_id and videos_favorites.user_id = ' . $current_user_id)              
              ->where(
                      ['and',
                        ['tag_id' => TRAINING_VIDEOS],
                        ['like', 'video_title', $search_text]])
              ->orWhere(
                      ['and',
                        ['tag_id' => TRAINING_VIDEOS],
                        ['like', 'description', $search_text]])              
              ->orderBy(['video_title' => SORT_DESC])
              ->groupBy(['videos.id'])
              ->limit(MAX_ITEMS)
              ->offset($offset)
              ->all();            
                          
      return $this->renderPartial('_view', [
        'videos' => $videos,
        'tag_name' => 'Training Videos',
        'page' => $page,
        'total_number_pages' => $total_number_pages  
      ]);
     
      die();
    }
    
    public function actionAjaxCaseStudies() {
      
      $req = Yii::$app->request;
                  
      $page = $req->get('page', 0);
                  
      $current_user_id = \Yii::$app->user->identity->ID;
      
      $count = (new Query())
              ->select(['videos.id'])
              ->from('videos')
              ->leftJoin('video_tag_list', 'videos.id = video_tag_list.video_id')
              ->join('left join', 'videos_favorites', 'videos.id = videos_favorites.video_id and videos_favorites.user_id = ' . $current_user_id)
              ->where(['tag_id' => CASE_STUDIES])
              ->orderBy(['video_title' => SORT_ASC])
              ->groupBy(['videos.id'])
              ->count();

      $offset = $page * MAX_ITEMS;
      $total_number_pages = ceil($count / MAX_ITEMS);        
                        
      $videos = (new Query())
              ->select(['videos.*', 'videos_favorites.fav_id as favorite'])
              ->from('videos')
              ->leftJoin('video_tag_list', 'videos.id = video_tag_list.video_id')
              ->join('left join', 'videos_favorites', 'videos.id = videos_favorites.video_id and videos_favorites.user_id = ' . $current_user_id)
              ->where(['tag_id' => CASE_STUDIES])
              ->orderBy(['video_title' => SORT_ASC])
              ->groupBy(['videos.id'])
              ->limit(MAX_ITEMS)
              ->offset($offset)
              ->all();
      
                    
      return $this->renderPartial('_view', [
        'videos' => $videos,
        'tag_name' => 'Case Studies',
        'page' => $page,
        'total_number_pages' => $total_number_pages  
      ]);
        
      die();
    }
    
    public function actionAjaxSearchCaseStudies() {
      
      $req = Yii::$app->request;
                  
      $search_text = $req->get('search_text', '');
      
      $page = $req->get('page', 0);
                  
      $current_user_id = \Yii::$app->user->identity->ID;
      
      $count = (new Query())
              ->select(['videos.id'])
              ->from('videos')
              ->leftJoin('video_tag_list', 'videos.id = video_tag_list.video_id')
              ->join('left join', 'videos_favorites', 'videos.id = videos_favorites.video_id and videos_favorites.user_id = ' . $current_user_id)              
              ->where(
                      ['and',
                        ['tag_id' => CASE_STUDIES],
                        ['like', 'video_title', $search_text]])
              ->orWhere(
                      ['and',
                        ['tag_id' => CASE_STUDIES],
                        ['like', 'description', $search_text]])              
              ->orderBy(['video_title' => SORT_DESC])
              ->groupBy(['videos.id'])
              ->count();

      $offset = $page * MAX_ITEMS;
      $total_number_pages = ceil($count / MAX_ITEMS);        

                        
      $videos = (new Query())
              ->select(['videos.*', 'videos_favorites.fav_id as favorite'])
              ->from('videos')
              ->leftJoin('video_tag_list', 'videos.id = video_tag_list.video_id')
              ->join('left join', 'videos_favorites', 'videos.id = videos_favorites.video_id and videos_favorites.user_id = ' . $current_user_id)              
              ->where(
                      ['and',
                        ['tag_id' => CASE_STUDIES],
                        ['like', 'video_title', $search_text]])
              ->orWhere(
                      ['and',
                        ['tag_id' => CASE_STUDIES],
                        ['like', 'description', $search_text]])              
              ->orderBy(['video_title' => SORT_DESC])
              ->groupBy(['videos.id'])
              ->limit(MAX_ITEMS)
              ->offset($offset)
              ->all();            
                          
      return $this->renderPartial('_view', [
        'videos' => $videos,
        'tag_name' => 'Case Studies',
        'page' => $page,
        'total_number_pages' => $total_number_pages  
      ]);
     
      die();
    }
                                       
}