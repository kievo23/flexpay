<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mpesa;

/**
 * SearchMpesa represents the model behind the search form about `app\models\Mpesa`.
 */
class SearchMpesa extends Mpesa
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transaction_reference', 'transaction_timestamp', 'sender_phone','product_code', 'first_name', 'last_name'], 'safe'],
            [['amount', 'status'], 'integer'],
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
        $query = Mpesa::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
             'sort' => [
                'defaultOrder' => ['transaction_timestamp'=>SORT_DESC]
            ],
            'pagination' => [
                'pageSize' => 500,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'transaction_timestamp' => $this->transaction_timestamp,
            'amount' => $this->amount,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'transaction_reference', $this->transaction_reference])
            ->andFilterWhere(['like', 'sender_phone', $this->sender_phone])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'product_code', $this->product_code]);
            
        if(yii::$app->user->identity->category == 'merchant'){
            $query->andFilterWhere(['OR',
            'product_code LIKE "' . yii::$app->user->identity->username . '%" ',
            'product_code LIKE "' . yii::$app->user->identity->username . '%"']);
         }
            

        return $dataProvider;
    }
}