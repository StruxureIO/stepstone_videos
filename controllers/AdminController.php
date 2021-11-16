<?php



namespace humhub\modules\stepstone_videos\controllers;

use humhub\modules\admin\components\Controller;
use humhub\modules\stepstone_videos\models\VideoTags;
use humhub\modules\stepstone_videos\models\Videos;
use humhub\modules\stepstone_videos\models\VideosSearch;
use humhub\modules\stepstone_videos\models\VideoTagList;
use Yii;
use yii\web\UploadedFile;

class AdminController extends Controller
{
  public $mVideoTags;
  public $mVideos;
  public $mTagList;
  
  public function accessRules() {

		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','tags','savetags'), 
				'users'=>array('*'),

			));
  }      

    /**
     * Render admin only page
     *
     * @return string
     */
    public function actionIndex() {
        //return $this->render('index');
      
        $searchModel = new \humhub\modules\stepstone_videos\models\VideosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort->defaultOrder = ['video_title' => SORT_ASC];
                
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
      
    }
        
    public function actionAdd() {
      
      //Yii::$app->cache->flush();      
            
      $model = new \humhub\modules\stepstone_videos\models\Videos();
      
      $this->mVideoTags = new \humhub\modules\stepstone_videos\models\VideoTags();
      
      //$currentTags = new \humhub\modules\stepstone_videos\models\VideoTagList();
      $this->mTagList = new \humhub\modules\stepstone_videos\models\VideoTagList();
      
      $tags = $this->mVideoTags::find()->all();      
      
      //$model = $this->mVideos::find()->where(['video_id' => $id])->one();      
      //$currentTags = $this->mTagList::find()->all(); 
      //$currentTags = $this->mTagList::find()->where(['video_id' => $id])->aa();      
            
      if ($model->load(Yii::$app->request->post())) { 
        
                
        $model->image = UploadedFile::getInstance($model, 'image');
        
      //if ($model->file && $model->validate()) {                
      //    $model->file->saveAs('uploads/' . $model->file->baseName . '.' . $model->file->extension);
      //}        

        if ($model->image && $model->validate()) {                
          
          //check for duplicate file names
          
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
                              
          $model->image->saveAs($image_url);          
          $model->image_url = $image_url;
          
          if($model->save(false)) {
            
            $this->mTagList::deleteAll(['video_id' => $model->id]);
            $selected_tags = explode(',', $model->tags);      
            foreach($selected_tags as $tag) {
              $new_tag = new \humhub\modules\stepstone_videos\models\VideoTagList();
              $new_tag->video_id = $model->id;
              $new_tag->tag_id = $tag;
              $new_tag->save();
            }
            
            $model->videoAdded($model->id);
                        
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

        return $this->redirect(['admin/index']);
      }

      return $this->render('addvideo', ['model' => $model, 'tags' => $tags] );
      
    }
        
    public function actionUpdate($id){
      
        $this->mVideos = new \humhub\modules\stepstone_videos\models\Videos();
        
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
          return $this->redirect(['admin/index']);
        }

        return $this->render('updatevideos', [
          'model' => $model,
          'tags' => $tags,  
        ]);
    }
    
    public function actionDelete($id) {
      
      // delete tag list records
      $this->mTagList = new \humhub\modules\stepstone_videos\models\VideoTagList();
      $this->mTagList::deleteAll(['video_id' => $id]);
      
      // delete video thumbnail
      $model = $this->findVideoModel($id);
      $image_url = $model->image_url;
      
      $content = $this->findContentModel($id);
      $content->delete();            
      
      if(!empty($image_url)) {
        // base path
        $image_path = Yii::getAlias('@app');
        $image_path = str_replace('protected', $image_url, $image_path);

        if(file_exists($image_path)) {
          unlink($image_path);
        }          
      }
              
        
      // delete the record        
      $model->delete();
            
      return $this->redirect(['admin/index']);
            
    }
    
    public function actionTags() {      
      
      // use when adding a new field
      //Yii::$app->cache->flush();      
      
      $this->mVideoTags = new \humhub\modules\stepstone_videos\models\VideoTags();
      $tags = $this->mVideoTags::find()->orderBy(['tag_name'=>SORT_ASC])->all();
      
      return $this->render('tags', array('model' => $tags));
    }
        
    public function actionUpdatetag($id){
      
        $this->mVideoTags = new \humhub\modules\stepstone_videos\models\VideoTags();
        $model = $this->mVideoTags::find()->where(['tag_id' => $id])->one();      

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
          return $this->redirect(['admin/tags']);
        }

        return $this->render('update', [
          'model' => $model,
        ]);
    }
    
    public function actionDeletetag($id){
      
        $this->findModel($id)->delete();

        return $this->redirect(['admin/tags']);
    }
    
    public function actionAddtag() {
      
      //Yii::$app->cache->flush();            
      
      $model = new \humhub\modules\stepstone_videos\models\VideoTags();

      if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
          return $this->redirect(['admin/tags']);
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
    
    protected function findVideoModel($id){
      
      $mVideos = new \humhub\modules\stepstone_videos\models\Videos();

      if(($model = $mVideos::findOne($id)) !== null) {
        return $model;
      }

      throw new NotFoundHttpException('The requested page does not exist.');
    }  
    
    protected function findContentModel($id){

      $mContent = new \humhub\modules\content\models\Content();

      if(($model = $mContent::findOne(['object_id' => $id, 'object_model' => 'humhub\\modules\\stepstone_videos\\models\\VideosContent'])) !== null) {
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
    
    
       
}

