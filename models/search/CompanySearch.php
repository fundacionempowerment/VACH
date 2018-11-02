<?php

namespace app\models\search;


use app\models\Company;
use yii\db\Query;

class CompanySearch extends Company {
    public function init() {
        // Leave blank
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['name'], 'safe'],
        ];
    }


    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return Query
     */
    public function search($params) {
        $query = Company::browse();

        // add conditions that should always apply here

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $query;
        }

        // grid filtering conditions
        $query->andFilterWhere(['like', 'name', $this->name]);

        return $query;
    }
}
