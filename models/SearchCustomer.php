<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Customer;

/**
 * SearchCustomer represents the model behind the search form about `app\models\Customer`.
 */
class SearchCustomer extends Customer
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['phone', 'status', 'code', 'date'], 'safe'],
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
        $query = Customer::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);
        
        if(Yii::$app->user->identity->category == "merchant"){
            $query->andFilterWhere([
            	'OR',
		'code LIKE "'. Yii::$app->user->identity->username . '%"',
		'code LIKE "'. Yii::$app->user->identity->username . '%"'
            ]);
        }

        $query->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'date', $this->date]);

        return $dataProvider;
    }

    public function searchactive($params)
    {
        $query = Customer::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            
            return $dataProvider;
        }

        //$query->joinWith('product');
        $query->leftJoin('product', 'customer.code=product.code');

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'date', $this->date]);
            //->andFilterWhere(['like', 'date', '2016-02-08']);

        return $dataProvider;
    }
}
