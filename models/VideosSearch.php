<?php

//namespace app\models;
namespace humhub\modules\stepstone_videos\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
//use app\models\Vidoes;
use humhub\modules\stepstone_videos\models\VideosContent;

/**
 * VideosSearch represents the model behind the search form of `app\models\Country`.
 */
class VideosSearch extends VideosContent
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['video_title', 'description'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = VideosContent::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        //$query->andFilterWhere([
        //    'population' => $this->population,
        //]);

//        $query->andFilterWhere(['like', 'video_title', $this->video_title])
//            ->andFilterWhere(['like', 'description', $this->description]);
        
        $query->andFilterWhere ( [ 'OR' ,
                    [ 'like' , 'video_title' , $this->video_title ],
                    [ 'like' , 'description' , $this->video_title ],
                ] );        
                

        return $dataProvider;
    }
}
