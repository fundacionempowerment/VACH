<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "team".
 * @package app\models
 * @property integer $id
 * @property string $name
 * @property integer $sponsor_id
 * @property integer $company_id
 * @property integer $coach_id
 * @property integer $team_type_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Person $sponsor
 * @property Company $company
 * @property TeamType $teamType
 *
 * @property array $relationStatistics
 */
class Team extends ActiveRecord {
    public $fullname;
    public $deletable;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'team';
    }

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            [['name', 'coach_id', 'sponsor_id', 'company_id', 'team_type_id'], 'required'],
            [['name', 'company_id'], 'unique', 'targetAttribute' => ['name', 'company_id']],
            ['notes', 'safe'],
            [['notes'], 'string', 'max' => 1000],
            [['notes'], 'filter', 'filter' => 'trim'],
        ];
    }

    public function attributeLabels() {
        return [
            'name' => Yii::t('app', 'Name'),
            'team_type_id' => Yii::t('app', 'Type'),
            'coach_id' => Yii::t('team', 'Coach'),
            'sponsor_id' => Yii::t('team', 'Sponsor'),
            'company_id' => Yii::t('team', 'Company'),
            'coaches' => Yii::t('team', 'Allowed coaches'),
            'IndividualWheelStatus' => Yii::t('wheel', 'Individual Wheels'),
            'GroupWheelStatus' => Yii::t('wheel', 'Group Wheels'),
            'OrganizationalWheelStatus' => Yii::t('wheel', 'Organizational Wheels'),
            'notes' => Yii::t('app', 'Notes'),
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

    public function beforeValidate() {
        if (!isset($this->coach_id)) {
            $this->coach_id = Yii::$app->user->id;
        }

        return parent::beforeValidate();
    }

    public function afterFind() {
        $this->fullname = $this->company->name . ' ' . $this->name;

        $this->deletable = count($this->wheels) == 0;

        parent::afterFind();
    }

    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        $this->afterFind();
    }

    public static function browse() {
        return Team::find()
            ->innerJoin('company', 'company.id = team.company_id')
            ->leftJoin('team_coach', '`team_coach`.`team_id` = `team`.`id`')
            ->where(['team.coach_id' => Yii::$app->user->id])
            ->orWhere(['team_coach.coach_id' => Yii::$app->user->id])
            ->orderBy('team.created_at desc');
    }

    public function getTeamType() {
        return $this->hasOne(TeamType::className(), ['id' => 'team_type_id']);
    }

    public function getCoach() {
        return $this->hasOne(User::className(), ['id' => 'coach_id']);
    }

    public function getCompany() {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }

    public function getSponsor() {
        return $this->hasOne(Person::className(), ['id' => 'sponsor_id']);
    }

    public function getMembers() {
        return $this->hasMany(TeamMember::className(), ['team_id' => 'id']);
    }

    public function getActiveMemberList() {
        $activeMembers = [];
        foreach ($this->getMembers()->where(['active' => true])->all() as $teamMember) {
            $activeMembers[$teamMember->person_id] = $teamMember->member->fullname;
        }

        return $activeMembers;
    }

    public function hasMember($personId) {
        return TeamMember::find()
            ->where(['person_id' => $personId, 'team_id' => $this->id])
            ->exists();
    }

    public function getWheels() {
        return $this->hasMany(Wheel::className(), ['team_id' => 'id']);
    }

    public function getTeamCoaches() {
        return $this->hasMany(TeamCoach::className(), ['team_id' => 'id']);
    }

    public function getReport() {
        return $this->hasOne(Report::className(), ['team_id' => 'id']);
    }

    static public function getDashboardList($companyId) {
        $teams = self::find()
            ->leftJoin('team_coach', 'team_coach.team_id = team.id')
            ->where(['team.coach_id' => Yii::$app->user->id])
            ->orWhere(['team_coach.coach_id' => Yii::$app->user->id])
            ->andWhere(['team.company_id' => $companyId])
            ->with(['coach', 'company'])
            ->all();

        return ArrayHelper::map($teams, 'id', 'name');
    }

    public function getIndividualWheels() {
        return $this->hasMany(Wheel::className(), ['team_id' => 'id'])->where(['type' => Wheel::TYPE_INDIVIDUAL])->with('answers');
    }

    public function getGroupWheels() {
        $result = [];
        foreach ($this->wheels as $wheel) {
            if ($wheel->type == Wheel::TYPE_GROUP) {
                $result[] = $wheel;
            }
        }
        return $result;
    }

    public function getOrganizationalWheels() {
        $result = [];
        foreach ($this->wheels as $wheel) {
            if ($wheel->type == Wheel::TYPE_ORGANIZATIONAL) {
                $result[] = $wheel;
            }
        }
        return $result;
    }

    public function getIndividualWheelStatus() {
        $answers = $this->answerCountByType(Wheel::TYPE_INDIVIDUAL);
        $members = count($this->members);
        $questions = $members * WheelQuestion::getQuestionCount(Wheel::TYPE_INDIVIDUAL);
        if ($questions == 0)
            $questions = 1;

        return round($answers / $questions * 100, 1) . '%';
    }

    public function getGroupWheelStatus() {
        $answers = $this->answerCountByType(Wheel::TYPE_GROUP);
        $members = count($this->members);
        $questions = $members * $members * WheelQuestion::getQuestionCount(Wheel::TYPE_GROUP);
        if ($questions == 0)
            $questions = 1;
        return round($answers / $questions * 100, 1) . '%';
    }

    public function getOrganizationalWheelStatus() {
        $answers = $this->answerCountByType(Wheel::TYPE_ORGANIZATIONAL);
        $members = count($this->members);
        $questions = $members * $members * WheelQuestion::getQuestionCount(Wheel::TYPE_ORGANIZATIONAL);
        if ($questions == 0)
            $questions = 1;
        return round($answers / $questions * 100, 1) . '%';
    }

    public function getWheelToken(TeamMember $teamMember, $wheelType) {
        $wheel = Wheel::find()
            ->where([
                'observer_id' => $teamMember->person_id,
                'type' => $wheelType
            ])
            ->one();
        return $wheel->token;
    }

    public function getWheelsCompleted() {
        $answers = $this->answerCount();
        $members = count($this->members);
        $questions = $members * WheelQuestion::getQuestionCount(Wheel::TYPE_INDIVIDUAL);
        $questions += $members * $members * WheelQuestion::getQuestionCount(Wheel::TYPE_GROUP);
        $questions += $members * $members * WheelQuestion::getQuestionCount(Wheel::TYPE_ORGANIZATIONAL);
        if ($questions == 0)
            $questions = 1;
        return round($answers / $questions * 100, 1) . '%';
    }

    public function getMemberProgress($teamMember, $type) {
        $answers = (new Query)->select('count(wheel_answer.id) as count')
            ->from('wheel')
            ->leftJoin('wheel_answer', 'wheel_answer.wheel_id = wheel.id')
            ->where(['team_id' => $this->id, 'type' => $type, 'observer_id' => $teamMember->person_id])
            ->scalar();
        $members = count($this->members);
        $questions = $members * WheelQuestion::getQuestionCount(Wheel::TYPE_ORGANIZATIONAL);
        if ($questions == 0)
            $questions = 1;
        return round($answers / $questions * 100, 1) . '%';
    }

    public function answerCount() {
        return (new Query)->select('count(wheel_answer.id) as count')
            ->from('wheel')
            ->leftJoin('wheel_answer', 'wheel_answer.wheel_id = wheel.id')
            ->where(['team_id' => $this->id])
            ->scalar();
    }

    public function answerCountByType($type) {
        return (new Query)->select('count(wheel_answer.id) as count')
            ->from('wheel')
            ->leftJoin('wheel_answer', 'wheel_answer.wheel_id = wheel.id')
            ->where(['team_id' => $this->id, 'type' => $type])
            ->scalar();
    }

    public function notifyIcon($type, $observer_id) {
        $count = [
            'created' => 0,
            'sent' => 0,
            'received' => 0,
            'in_progress' => 0,
            'done' => 0,
        ];

        $wheels = Wheel::find()
            ->where(['team_id' => $this->id])
            ->andWhere(['type' => $type])
            ->andWhere(['observer_id' => $observer_id])
            ->all();

        foreach ($wheels as $wheel) {
            $count[$wheel->status] += 1;
        }

        if ($count['created'] > 0 && ($count ['sent'] + $count['received'] + $count['in_progress'] == 0) && $count['done'] > 0) {
            // retry
            return \app\components\Icons::EXCLAMATION;
        } else if ($count['done'] == count($wheels)) {
            return \app\components\Icons::OK . \app\components\Icons::OK;
        } else if ($count['in_progress'] > 0) {
            return \app\components\Icons::PLAY;
        } else if ($count['received'] > 0) {
            return \app\components\Icons::OK;
        } else if ($count['sent'] > 0) {
            return \app\components\Icons::SEND;
        }
        return '';
    }

    public function isUserAllowed() {
        if ($this->coach_id == Yii::$app->user->id) {
            return true;
        }

        foreach ($this->teamCoaches as $teamCoach) {
            if ($teamCoach->coach->id == Yii::$app->user->id) {
                return true;
            }
        }
        return false;
    }

    public function getRelationsStatistics() {
        $groupRelationsMatrix = Wheel::getRelationsMatrix($this->id, Wheel::TYPE_GROUP);
        $organizationalRelationsMatrix = Wheel::getRelationsMatrix($this->id, Wheel::TYPE_ORGANIZATIONAL);

        return [
            Wheel::TYPE_GROUP => $this->internalGetRelationsStatistics($groupRelationsMatrix),
            Wheel::TYPE_ORGANIZATIONAL => $this->internalGetRelationsStatistics($organizationalRelationsMatrix)
        ];
    }

    private function internalGetRelationsStatistics($data) {
        //observer_id, observed_id, value, observer_name, observer_surname, observed_name, observed_surname,'

        $sums = ['green' => 0, 'yellow' => 0, 'red' => 0,];
        $count = 0;
        $self_critic = [
            'min' => ['value' => 1000, 'name' => '',],
            'max' => ['value' => -1000, 'name' => '',]
        ];
        $critics = [
            'min' => ['value' => 1000, 'name' => '',],
            'max' => ['value' => -1000, 'name' => '',]
        ];
        $productions = [
            'min' => ['value' => 1000, 'name' => '',],
            'max' => ['value' => -1000, 'name' => '',]
        ];
        $critic_data = [];

        foreach ($data as $datum) {
            // Calculate general
            if ($datum['value'] >= Yii::$app->params['good_consciousness']) {
                $sums['green']++;
            } else if ($datum['value'] < Yii::$app->params['minimal_consciousness']) {
                $sums['red']++;
            } else {
                $sums['yellow']++;
            }
            $count++;

            if ($datum['observer_id'] != $datum['observed_id']) {
                if (!isset($critic_data[$datum['observer_id']])) {
                    $critic_data[$datum['observer_id']] = [
                        'observer_value' => 0,
                        'observer_count' => 0,
                        'observed_value' => 0,
                        'observed_count' => 0,
                        'name' => '',
                    ];
                }
                if (!isset($critic_data[$datum['observed_id']])) {
                    $critic_data[$datum['observed_id']] = [
                        'observer_value' => 0,
                        'observer_count' => 0,
                        'observed_value' => 0,
                        'observed_count' => 0,
                        'name' => '',
                    ];
                }
                $critic_data[$datum['observer_id']]['observer_value'] += $datum['value'];
                $critic_data[$datum['observer_id']]['observer_count']++;
                $critic_data[$datum['observer_id']]['name'] = $datum['observer_name'] . ' ' . $datum['observer_surname'];
                $critic_data[$datum['observed_id']]['observed_value'] += $datum['value'];
                $critic_data[$datum['observed_id']]['observed_count']++;
                $critic_data[$datum['observed_id']]['name'] = $datum['observed_name'] . ' ' . $datum['observed_surname'];
            } else {
                if ($datum['value'] < $self_critic['min']['value']) {
                    $self_critic['min']['value'] = $datum['value'];
                    $self_critic['min']['name'] = $datum['observer_name'] . ' ' . $datum['observer_surname'];
                }
                if ($datum['value'] > $self_critic['max']['value']) {
                    $self_critic['max']['value'] = $datum['value'];
                    $self_critic['max']['name'] = $datum['observer_name'] . ' ' . $datum['observer_surname'];
                }
            }
        }

        // Process critics
        foreach ($critic_data as $critic_datum) {
            $observed = $critic_datum['observed_value'] / $critic_datum['observed_count'];
            $observer = $critic_datum['observer_value'] / $critic_datum['observer_count'];

            if ($observer < $critics['min']['value']) {
                $critics['min']['value'] = $observer;
                $critics['min']['name'] = $critic_datum['name'];
            }
            if ($observer > $critics['max']['value']) {
                $critics['max']['value'] = $observer;
                $critics['max']['name'] = $critic_datum['name'];
            }
            if ($observed < $productions['min']['value']) {
                $productions['min']['value'] = $observed;
                $productions['min']['name'] = $critic_datum['name'];
            }
            if ($observed > $productions['max']['value']) {
                $productions['max']['value'] = $observed;
                $productions['max']['name'] = $critic_datum['name'];
            }
        }

        if ($count == 0) {
            $count = 1;
        }

        $result = [
            'green_percentage' => $sums['green'] / $count,
            'yellow_percentage' => $sums['yellow'] / $count,
            'red_percentage' => $sums['red'] / $count,
            //
            'most_selfcritic' => $self_critic['min']['name'],
            'most_selfcritic_percentage' => $self_critic['min']['value'] / 4,
            'less_selfcritic' => $self_critic['max']['name'],
            'less_selfcritic_percentage' => $self_critic['max']['value'] / 4,
            //
            'most_critic' => $critics['min']['name'],
            'most_critic_percentage' => $critics['min']['value'] / 4,
            'most_benevolent' => $critics['max']['name'],
            'most_benevolent_percentage' => $critics['max']['value'] / 4,
            //
            'most_productive' => $productions['max']['name'],
            'most_productive_percentage' => $productions['max']['value'] / 4,
            'less_productive' => $productions['min']['name'],
            'less_productive_percentage' => $productions['min']['value'] / 4,
        ];

        return $result;
    }
}
