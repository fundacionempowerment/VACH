<?php

namespace app\models\search;

use app\models\Team;
use yii\db\Query;

class TeamSearch extends Team {
    public function init() {
        // Leave blank
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['name', 'coach_id', 'sponsor_id', 'company_id', 'team_type_id'], 'safe'],
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
        $query = Team::browse();

        // add conditions that should always apply here

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $query;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'team_type_id' => $this->team_type_id,
        ]);

        $query->andFilterWhere(['or',
            ['like', 'team.name', $this->name],
            ['like', 'company.name', $this->name]
        ]);

        return $query;
    }
}
