<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Query;
use \yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Goal extends ActiveRecord {

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            [['person_id', 'name'], 'required'],
        ];
    }

    public function attributeLabels() {
        return [
            'name' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function getPerson() {
        return $this->hasOne(Person::className(), ['id' => 'person_id']);
    }

    public function getCoach() {
        return User::findOne(['id' => $this->person->coach_id]);
    }

    public function getResources() {
        return $this->hasMany(GoalResource::className(), ['goal_id' => 'id'])->orderBy(['is_desired' => SORT_DESC]);
    }

    public function getMilestones() {
        return $this->hasMany(GoalMilestone::className(), ['goal_id' => 'id']);
    }

    public function getResourcesToConserve() {
        return $this->getFilteredResources(true, true);
    }

    public function getResourcesToConquer() {
        return $this->getFilteredResources(true, false);
    }

    public function getResourcesToEliminate() {
        return $this->getFilteredResources(false, true);
    }

    public function getResourcesToAvoid() {
        return $this->getFilteredResources(false, false);
    }

    private function getFilteredResources($is_desired, $is_had) {
        $filteredResources = [];
        foreach ($this->resources as $resource)
            if ($resource->is_desired == $is_desired && $resource->is_had == $is_had)
                $filteredResources[] = $resource;
        return $filteredResources;
    }

}
