<?php

namespace app\models\search;

use app\models\Wheel;
use yii\db\Query;

class WheelSearch extends Wheel {
    public function init() {
        // Leave blank
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['team_id', 'observer_id', 'observed_id', 'type'], 'safe'],
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
        $query = Wheel::browse();

        // add conditions that should always apply here

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $query;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'wheel.team_id' => $this->team_id,
            'wheel.observer_id' => $this->observer_id,
            'wheel.observed_id' => $this->observed_id,
            'wheel.type' => $this->type,
        ]);

        return $query;
    }
}
