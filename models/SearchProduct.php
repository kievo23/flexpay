<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Product;

/**
 * SearchProduct represents the model behind the search form about `app\models\Product`.
 */
class SearchProduct extends Product
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name', 'source'], 'safe'],
            [['price', 'flexpay_price', 'book_amount', 'installments'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = Product::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['code'=>SORT_DESC]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'code' => $this->code,
            'price' => $this->price,
            'flexpay_price' => $this->flexpay_price,
            'book_amount' => $this->book_amount,
            'installments' => $this->installments,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'source', $this->source]);

         if(yii::$app->user->identity->category == 'merchant'){
            $query->andFilterWhere(['OR',
            'code LIKE "%'.yii::$app->user->identity->username.'%"',
            'code LIKE "%'.yii::$app->user->identity->username.'%"']);
         }

        return $dataProvider;
    }
}