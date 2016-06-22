<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Receipts;

/**
 * SearchReceipts represents the model behind the search form about `app\models\Receipts`.
 */
class SearchReceipts extends Receipts
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'id', 'extra'], 'integer'],
            [['phone', 'receipt_no', 'productcode'], 'safe'],
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
        $query = Receipts::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'status' => $this->status,
            'id' => $this->id,
            'extra' => $this->extra,
        ]);

        $query->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'receipt_no', $this->receipt_no])
            ->andFilterWhere(['like', 'productcode', $this->productcode]);
        
        if(yii::$app->user->identity->category == 'merchant'){
            $query->andFilterWhere(['OR',
            'productcode LIKE "' . yii::$app->user->identity->username . '%" ',
            'productcode LIKE "' . yii::$app->user->identity->username . '%"']);
         }

        return $dataProvider;
    }
}
