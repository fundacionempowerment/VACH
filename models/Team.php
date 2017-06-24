<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Query;
use \yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;

class Team extends ActiveRecord
{

    public $fullname;
    public $deletable;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['name', 'coach_id', 'sponsor_id', 'company_id'], 'required'],
            [['name'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Name'),
            'coach_id' => Yii::t('team', 'Coach'),
            'sponsor_id' => Yii::t('team', 'Sponsor'),
            'company_id' => Yii::t('team', 'Company'),
            'coaches' => Yii::t('team', 'Allowed coaches'),
            'IndividualWheelStatus' => Yii::t('wheel', 'Individual Wheels'),
            'GroupWheelStatus' => Yii::t('wheel', 'Group Wheels'),
            'OrganizationalWheelStatus' => Yii::t('wheel', 'Organizational Wheels'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function beforeValidate()
    {
        if (!isset($this->coach_id))
            $this->coach_id = Yii::$app->user->id;

        return parent::beforeValidate();
    }

    public function afterFind()
    {
        $this->fullname = $this->company->name . ' ' . $this->name;

        $this->deletable = count($this->wheels) == 0;

        parent::afterFind();
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->afterFind();
    }

    public static function browse()
    {
        return Team::find()
                        ->select('team.*')
                        ->leftJoin('team_coach', '`team_coach`.`team_id` = `team`.`id`')
                        ->where(['team.coach_id' => Yii::$app->user->id])
                        ->orWhere(['team_coach.coach_id' => Yii::$app->user->id])
                        ->orderBy('team.id desc');
    }

    public function getCoach()
    {
        return $this->hasOne(User::className(), ['id' => 'coach_id']);
    }

    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }

    public function getSponsor()
    {
        return $this->hasOne(Person::className(), ['id' => 'sponsor_id']);
    }

    public function getMembers()
    {
        return $this->hasMany(TeamMember::className(), ['team_id' => 'id']);
    }

    public function hasMember($personId)
    {
        return TeamMember::find(['person_id' => $personId, 'team_id' => $this->id])
                        ->exists();
    }

    public function getWheels()
    {
        return $this->hasMany(Wheel::className(), ['team_id' => 'id']);
    }

    public function getTeamCoaches()
    {
        return $this->hasMany(TeamCoach::className(), ['team_id' => 'id']);
    }

    public function getReport()
    {
        return $this->hasOne(Report::className(), ['team_id' => 'id']);
    }

    static public function getDashboardList($companyId)
    {
        $teams = self ::find()
                ->leftJoin('team_coach', 'team_coach.team_id = team.id')
                ->where(['team.coach_id' => Yii::$app->user->id])
                ->orWhere(['team_coach.coach_id' => Yii::$app->user->id])
                ->andWhere(['team.company_id' => $companyId])
                ->with(['coach', 'company'])
                ->all();

        return ArrayHelper::map($teams, 'id', 'name');
    }

    public function getIndividualWheels()
    {
        return $this->hasMany(Wheel::className(), ['team_id' => 'id'])->where(['type' => Wheel::TYPE_INDIVIDUAL])->with('answers');
    }

    public function getGroupWheels()
    {
        return $this->hasMany(Wheel::className(), ['team_id' => 'id'])->where(['type' => Wheel::TYPE_GROUP])->with('answers');
    }

    public function getOrganizationalWheels()
    {
        return $this->hasMany(Wheel::className(), ['team_id' => 'id'])->where(['type' => Wheel::TYPE_ORGANIZATIONAL])->with('answers');
    }

    public function getIndividualWheelStatus()
    {
        $answers = $this->wheelStatus(Wheel::TYPE_INDIVIDUAL);
        $members = count($this->members);
        $questions = $members * WheelQuestion::getQuestionCount(Wheel::TYPE_INDIVIDUAL);
        if ($questions == 0)
            $questions = 1;

        return round($answers / $questions * 100, 1) . '%';
    }

    public function getGroupWheelStatus()
    {
        $answers = $this->wheelStatus(Wheel::TYPE_GROUP);
        $members = count($this->members);
        $questions = $members * $members * WheelQuestion::getQuestionCount(Wheel::TYPE_GROUP);
        if ($questions == 0)
            $questions = 1;
        return round($answers / $questions * 100, 1) . '%';
    }

    public function getOrganizationalWheelStatus()
    {
        $answers = $this->wheelStatus(Wheel::TYPE_ORGANIZATIONAL);
        $members = count($this->members);
        $questions = $members * $members * WheelQuestion::getQuestionCount(Wheel::TYPE_ORGANIZATIONAL);
        if ($questions == 0)
            $questions = 1;
        return round($answers / $questions * 100, 1) . '%';
    }

    public function wheelStatus($type)
    {
        return (new Query)->select('count(wheel_answer.id) as count')
                        ->from('wheel')
                        ->leftJoin('wheel_answer', 'wheel_answer.wheel_id = wheel.id')
                        ->where(['team_id' => $this->id, 'type' => $type])
                        ->scalar();
        ;
    }

    public function notifyIcon($type, $observer_id)
    {
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

    public function isUserAllowed()
    {
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

}
