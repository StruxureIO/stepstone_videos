<?php
//use Yii;
use yii\helpers\Url;

//if(defined('LOCALHOST')) {
//  define('VIDEO_ASSET_COVER', Url::base() . '/assets/3925053c/images/video-cover.jpg');
//} else {
//  define('VIDEO_ASSET_COVER', Url::base() . '/assets/10ef8c64/images/video-cover.jpg');
//}
define('VIDEO_ASSET_COVER', Url::base() . '/uploads/video-thumbnails/video-cover.jpg');


$html = '';

$current_user_id = \Yii::$app->user->identity->ID;

// different ways to get the asset url which are not working
//$html .= '<p>resource-manager ' . Url::to(['resource-manager']) . '</p>';
//$html .= \Yii::$app->assetManager()->getAssetUrl();
//$manager = Yii::$app->getAssetManager();
//$html .= $manager->getAssetUrl();
//$html .= Yii::$app->request->getAssetUrl();
//$assetManager = $this->getAssetManager();
//$bundle = $assetManager->getBundle(MyAsset::className());
//$url = $assetManager->getAssetUrl($bundle, 'js/script.js');
//$html .= $this->getAssetUrl();
//$html .= $this->module->assetsUrl;

//$html .= "page $page";

if($videos) {
  foreach($videos as $video) {
    $cover = Url::base() . '/' . $video['image_url'];
    //$cover = '/humhub/' . $video['image_url'];
    if(empty($video['image_url']))
      $cover = VIDEO_ASSET_COVER;
    $checked = ($video['favorite']) ? 'checked' : '';
    $html .= '<div class="col-md-4 video-block">' . PHP_EOL;
    $html .= '  <a class="step-video-link" data-video-id="'.$video['id'].'">'  . PHP_EOL;
    $html .= '    <img class="step-video-cover" src="' . $cover . '" alt="video thumbnail">' . PHP_EOL;
    $html .= '  </a>' . PHP_EOL;
    $html .= '  <div class="step-video-info">' . PHP_EOL;
    $html .= '    <div class="step-video-info-left"><a class="step-favorite" data-user="'.$current_user_id.'" data-video="'.$video['id'].'" ><span class="fa fa-star '. $checked .'"></span></a></div>' . PHP_EOL;
    $html .= '    <div class="step-video-info-right">' . PHP_EOL;
    $html .= '      <div class="step-video-title"><a class="step-video-link" data-video-id="'.$video['id'].'">'.$video['video_title'].'</a></div>' . PHP_EOL;
    $html .= '      <div class="step-video-description">'.$video['description'].'</div>' . PHP_EOL;
    $html .= '    </div>' . PHP_EOL;    
    $html .= '  </div>' . PHP_EOL;
    $html .= '</div>' . PHP_EOL;
        
  }
  
  $html .= '<div style="clear:both"></div>' . PHP_EOL;
  
  $html .= '<div id="video-page-navigation">' . PHP_EOL;
  if($page > 0)
    $html .= '  <a id="step-video-prev" data-page-id="'. ($page-1) .'">< Previous</a>' . PHP_EOL;
  if($page < $total_number_pages-1)
    $html .= '  <a id="step-video-next" data-page-id="'. ($page+1) .'">Next ></a>' . PHP_EOL;
  $html .= '</div>' . PHP_EOL;

} else {
  if($tag_name == 'All Tags' )
    $html = '<p id="no-videos-founds">No videos found</p>';
  else
    $html = '<p id="no-videos-founds">No videos found for ' . $tag_name . '</p>';
}

if($tag_name == 'All Tags')
  $tag_name = 'Latest';

//if(isset($search_text))
//  $html .= "<p>$search_text<p>";

//if(isset($query))
//  $html .= "<p>$query<p>";

//$data = array ('html' => $html, 'tag' => $tag_name, 'icon' => $icon);
$data = array ('html' => $html, 'tag' => $tag_name);
echo json_encode($data);

die();
?>
