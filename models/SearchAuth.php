<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Auth;

/**
 * SearchAuth represents the model behind the search form about `app\models\Auth`.
 */
class SearchAuth extends Auth
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id',  'sms_amount'], 'integer'],
            [['empno', 'firstName', 'lastName', 'username', 'password', 'category'], 'safe'],
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
        $query = Auth::find();

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
            'id' => $this->id,
            'sms_amount' => $this->sms_amount,
        ]);

        $query->andFilterWhere(['like', 'empno', $this->empno])
            ->andFilterWhere(['like', 'firstName', $this->firstName])
            ->andFilterWhere(['like', 'lastName', $this->lastName])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'category', $this->category]);

        return $dataProvider;
    }
}
