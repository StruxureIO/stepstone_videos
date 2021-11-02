<?php

namespace humhub\modules\stepstone_videos\controllers;

use humhub\modules\content\models\Content;
use humhub\modules\content\models\ContentContainer;
use humhub\modules\content\components\ContentContainerController;
use humhub\components\access\ControllerAccess;
use humhub\modules\content\components\ContentContainerControllerAccess;
use humhub\modules\space\models\Space;

use humhub\modules\stepstone_videos\models\VideoTags;
use humhub\modules\stepstone_videos\models\VideosContent;
use humhub\modules\stepstone_videos\models\VideosFavorites;
use humhub\modules\stepstone_videos\models\VideosSearch;
use humhub\modules\stepstone_videos\models\VideoTagList;
use humhub\modules\stepstone_videos\widgets\VideoWall;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\Url;
use yii\db\Query;


class VideosController extends ContentContainerController
{

    public $subLayout = "@stepstone_videos/views/layouts/default";
    public $mFavoritesList;
    public $mVideos;
    public $mVideoTags;
    public $mTagList;
    

    /**
     * Renders the index view for the module
     *
     * @return string
     */
    public function actionIndex(){
        
      //Yii::$app->cache->flush();
      
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
        
    public function actionAjaxView($tag, $tag_name, $search_text, $page = 0){
                  
      //Yii::debug('actionAjaxView');
      
//      $req = Yii::$app->request;
//            
//      $tag = $req->get('tag', "all");
//      
//      $tag_name = $req->get('tag_name', "All Tags");
//      
//      $page = $req->get('page', 0);
//      
//      $search_text = $req->get('search_text', '');
                                       
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
              //->groupBy(['videos.id'])
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
                //->groupBy(['videos.id'])
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
                  
      $this->mVideos = new \humhub\modules\stepstone_videos\models\VideosContent();
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
        
//    protected function findModel($tag){
//      
//      if($tag == 'all')
//        $videos = VideosContent::find()->orderBy('date_added')->all();
//      else
//        $videos = VideosContent::find()->where(['id' => '6'])->orderBy('date_added')->all();
//        //$videos = VideosContent::find()->orderBy('date_added')->all();
//      
//      //if (($model = VideosContent::find()->orderBy('date_added')->all()) !== null) {
//      if ($videos !== null) {
//        return $videos;
//      }
//
//      throw new NotFoundHttpException('The requested page does not exist.');
//    }
            
    public function actionAdminindex($cguid) {
        //return $this->render('index');
      
        Yii::$app->cache->flush();      
      
        $searchModel = new \humhub\modules\stepstone_videos\models\VideosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort->defaultOrder = ['video_title' => SORT_ASC];
                
        return $this->render('adminindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'cguid' => $cguid,
        ]);
      
    }
    
    public function actionAdd($cguid){
      
        $current_user_id = \Yii::$app->user->identity->ID;
      
        $model = new \humhub\modules\stepstone_videos\models\VideosContent($this->contentContainer);
        
        $this->mVideoTags = new \humhub\modules\stepstone_videos\models\VideoTags();
        
        $this->mTagList = new \humhub\modules\stepstone_videos\models\VideoTagList();
                
        $tags = $this->mVideoTags::find()->all();      
        
        //$currentTags = $this->mTagList::find()->all(); 

        if ($model->load(Yii::$app->request->post())) {
                    
          $model->created_at = date('Y-m-d H:i:s');
          $model->created_by = $current_user_id;
          $model->updated_at = date('Y-m-d H:i:s');
          $model->updated_by = $current_user_id;
                                                  
          $model->image = UploadedFile::getInstance($model, 'image');
          
          if ($model->image && $model->validate()) {                
                        
            $file_number = 1;
            $image_url = VIDEO_THUMBNAIL_PATH . $model->image->baseName . '.' . $model->image->extension;                    
            //$image_url = 'uploads/' . $model->image->baseName . '.' . $model->image->extension;
            
            // base path
            $image_path = Yii::getAlias('@app');
            $image_path = str_replace('protected', $image_url, $image_path);

            while(file_exists($image_path) && ($file_number < 10)) {
              $image_url = VIDEO_THUMBNAIL_PATH . $model->image->baseName . '-' . $file_number . '.' . $model->image->extension;
              $image_path = Yii::getAlias('@app');
              $image_path = str_replace('protected', $image_url, $image_path);
              $file_number++;
            }  
                                      
            $model->image_url = $image_url;
            $model->image->saveAs($image_url);          
                                     
            if ($model->save(false)) {

              //$this->mTagList::deleteAll(['video_id' => $model->id]);
              $selected_tags = explode(',', $model->tags);      
              foreach($selected_tags as $tag) {
                $new_tag = new \humhub\modules\stepstone_videos\models\VideoTagList();
                $new_tag->video_id = $model->id;
                $new_tag->tag_id = $tag;
                $new_tag->save();
              }
            }
          } else if ($model->validate()) {
            
            if ($model->save(false)) {

              //$this->mTagList::deleteAll(['video_id' => $model->id]);
              $selected_tags = explode(',', $model->tags);      
              foreach($selected_tags as $tag) {
                $new_tag = new \humhub\modules\stepstone_videos\models\VideoTagList();
                $new_tag->video_id = $model->id;
                $new_tag->tag_id = $tag;
                $new_tag->save();
              }
            }                        
          }      
          return $this->redirect(["videos/adminindex", 'cguid' => $cguid]);
        }

        return $this->render('addvideo', [
          'model' => $model,
          'tags' => $tags,  
          'cguid' => $cguid,  
        ]);
                
    }        
        
    public function actionUpdate($id, $cguid){
      
        //$this->mVideos = new \humhub\modules\stepstone_videos\models\Videos();
        $this->mVideos = new \humhub\modules\stepstone_videos\models\VideosContent($this->contentContainer);
        
        $this->mVideoTags = new \humhub\modules\stepstone_videos\models\VideoTags();
        
        $this->mTagList = new \humhub\modules\stepstone_videos\models\VideoTagList();
                
        $model = $this->mVideos::find()->where(['id' => $id])->one();      
        
        $tags = $this->mVideoTags::find()->all();      
        
        //$currentTags = $this->mTagList::find()->where(['id' => $id])->all();      
        $currentTags = $this->mTagList::find()->all(); 

        if ($model->load(Yii::$app->request->post())) {
          
          $model->updated_at = date('Y-m-d H:i:s');
          $model->updated_by = \Yii::$app->user->identity->ID;
                                        
          $model->image = UploadedFile::getInstance($model, 'image');
          
          if ($model->image && $model->validate()) {                
                        
            $file_number = 1;
            $image_url = VIDEO_THUMBNAIL_PATH . $model->image->baseName . '.' . $model->image->extension;                    
            //$image_url = 'uploads/' . $model->image->baseName . '.' . $model->image->extension;
            
            // base path
            $image_path = Yii::getAlias('@app');
            $image_path = str_replace('protected', $image_url, $image_path);

            while(file_exists($image_path) && ($file_number < 10)) {
              $image_url = VIDEO_THUMBNAIL_PATH . $model->image->baseName . '-' . $file_number . '.' . $model->image->extension;
              $image_path = Yii::getAlias('@app');
              $image_path = str_replace('protected', $image_url, $image_path);
              $file_number++;
            }  
                                      
            $model->image_url = $image_url;
            $model->image->saveAs($image_url);          
                                     
            if ($model->save(false)) {

              $this->mTagList::deleteAll(['video_id' => $model->id]);
              $selected_tags = explode(',', $model->tags);      
              foreach($selected_tags as $tag) {
                $new_tag = new \humhub\modules\stepstone_videos\models\VideoTagList();
                $new_tag->video_id = $model->id;
                $new_tag->tag_id = $tag;
                $new_tag->save();
              }
            }
          } else if ($model->validate()) {
            
            if ($model->save(false)) {

              $this->mTagList::deleteAll(['video_id' => $model->id]);
              $selected_tags = explode(',', $model->tags);      
              foreach($selected_tags as $tag) {
                $new_tag = new \humhub\modules\stepstone_videos\models\VideoTagList();
                $new_tag->video_id = $model->id;
                $new_tag->tag_id = $tag;
                $new_tag->save();
              }
            }                        
          }      
          return $this->redirect(["videos/adminindex", 'cguid' => $cguid]);
        }

        return $this->render('updatevideos', [
          'model' => $model,
          'tags' => $tags,  
          'cguid' => $cguid,  
        ]);
    }    
            
    public function actionDelete($id, $cguid) {
      
      // delete tag list records
      $this->mTagList = new \humhub\modules\stepstone_videos\models\VideoTagList();
      $this->mTagList::deleteAll(['video_id' => $id]);
      
      // delete video thumbnail
      $model = $this->findVideoModel($id);
      $image_url = $model->image_url;
              
      // base path
      $image_path = Yii::getAlias('@app');
      $image_path = str_replace('protected', $image_url, $image_path);
      
      if(file_exists($image_path)) {
        unlink($image_path);
      }  
        
      // delete the record        
      $model->delete();
            
      return $this->redirect(["videos/adminindex", 'cguid' => $cguid]);
            
    }        
        
    protected function findVideoModel($id){
      
      $mVideos = new \humhub\modules\stepstone_videos\models\VideosContent();

      if(($model = $mVideos::findOne($id)) !== null) {
        return $model;
      }

      throw new NotFoundHttpException('The requested page does not exist.');
    }  
        
    public function actionAjaxThumbnail() {
      
      $image_path = '';
      
      if(isset($_GET['video_id']))
        $video_id = $_GET['video_id'];
      else
        $video_id = '';      
                  
      if(isset($_GET['video_title']))
        $video_title = $_GET['video_title'];
      else
        $video_title = '';      
      
      $video_title = str_replace(' - ', '-', $video_title);      
            
      $file_name = str_replace(' ', '-', $video_title);      
      
      $file_name .= '.jpg';
      
      $thumbnail = $this->get_vimeo_thumbnail('https://vimeo.com/' . $video_id, $file_name);
              
      echo $thumbnail;
      
      die();
      
    }
    
    public function get_vimeo_thumbnail($url, $file_name) {
      
      $image_url = '';
      $image_path = 'image not found';
      
      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://vimeo.com/api/oembed.json?url=".$url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "",
        CURLOPT_HTTPHEADER => array(
          "Referer: ".$_SERVER['HTTP_REFERER'].""
        ),
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);
      
      $thumby = json_decode($response);
      
      curl_close($curl);
            
      if($thumby) {
        
        $thumbnail = $thumby->thumbnail_url;

        $thumbarr = explode('_',$thumbnail);
        $thumbnail = str_replace('https://i.vimeocdn.com/video/', '', $thumbarr[0]);

        $image_url = 'https://i.vimeocdn.com/video/'.$thumbnail.'_640.jpg';      

        $thumbnail_path = VIDEO_THUMBNAIL_PATH . $file_name;
        $image_path = Yii::getAlias('@app');
        $image_path = str_replace('protected', $thumbnail_path, $image_path);

        file_put_contents($image_path, file_get_contents($image_url));
                
      }
            
      return $thumbnail_path;
            
    }
    
    public function actionTags($cguid) {      
      
      // use when adding a new field
      //Yii::$app->cache->flush();      
      
      $this->mVideoTags = new \humhub\modules\stepstone_videos\models\VideoTags();
      $tags = $this->mVideoTags::find()->orderBy(['tag_name'=>SORT_ASC])->all();
      
      return $this->render('tags', array('model' => $tags, 'cguid' => $cguid));
    }
        
    public function actionUpdatetag($id, $cguid){
      
        //$cguid = $this->contentContainer->contentcontainer_id;
      
        $this->mVideoTags = new \humhub\modules\stepstone_videos\models\VideoTags();
        $model = $this->mVideoTags::find()->where(['tag_id' => $id])->one();      

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
          return $this->redirect(["videos/tags", 'cguid' => $cguid]);
        }

        return $this->render('update', [
          'model' => $model,
          'cguid' => $cguid,  
        ]);
    }
    
    public function actionDeletetag($id, $cguid){
      
        $this->findModel($id)->delete();

        //return $this->redirect(['vidoes/tags']);
        return $this->redirect(["videos/tags", 'cguid' => $cguid]);
    }
    
    public function actionAddtag($cguid) {
      
      $model = new \humhub\modules\stepstone_videos\models\VideoTags();

      if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
          return $this->redirect(["videos/tags&cguid=$cguid"]);
      }

      return $this->render('add', ['model' => $model]);
    }
    
    protected function findModel($id){
      
      $mVideoTags = new \humhub\modules\stepstone_videos\models\VideoTags();

      if(($model = $mVideoTags::findOne($id)) !== null) {
        return $model;
      }

      throw new NotFoundHttpException('The requested page does not exist.');
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
    
                                               
}