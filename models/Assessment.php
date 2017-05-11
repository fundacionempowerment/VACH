<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Query;
use \yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;

class Assessment extends ActiveRecord
{

    const STATUS_PENDING = 0;
    const STATUS_SENT = 1;
    const STATUS_FINISHED = 2;

    public $fullname;

    public function __construct()
    {
        $this->name = date("Y-m");
        $this->version = 2;
        $this->individual_status = 0;
        $this->group_status = 0;
        $this->organizational_status = 0;
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['name', 'team_id', 'version'], 'required'],
            [['name'], 'filter', 'filter' => 'trim'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Name'),
            'team_id' => Yii::t('team', 'Team'),
            'IndividualWheelStatus' => Yii::t('wheel', 'Individual Wheels'),
            'GroupWheelStatus' => Yii::t('wheel', 'Group Wheels'),
            'OrganizationalWheelStatus' => Yii::t('wheel', 'Organizational Wheels'),
            'coaches' => Yii::t('assessment', 'Allowed coaches'),
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

    public function afterFind()
    {
        $this->fullname = $this->team->company->name . ' ' . $this->team->name . ' ' . $this->name;
        parent::afterFind();
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->afterFind();
    }

    public function beforeDelete()
    {
        AssessmentCoach::deleteAll(['assessment_id' => $this->id]);
        Wheel::deleteAll(['assessment_id' => $this->id]);
        return parent::beforeDelete();
    }

    public static function browse()
    {
        return Assessment::find()
                        ->select('assessment.*')
                        ->leftJoin('assessment_coach', '`assessment_coach`.`assessment_id` = `assessment`.`id`')
                        ->leftJoin('team', '`team`.`id` = `assessment`.`team_id`')
                        ->where(['team.coach_id' => Yii::$app->user->id])
                        ->orWhere(['assessment_coach.coach_id' => Yii::$app->user->id])
                        ->orderBy('assessment.id desc');
    }

    public function getTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'team_id']);
    }

    public function getReport()
    {
        return $this->hasOne(Report::className(), ['assessment_id' => 'id']);
    }

    public function wheelStatus($type)
    {
        return (new Query)->select('count(wheel_answer.id) as count')
                        ->from('wheel')
                        ->leftJoin('wheel_answer', 'wheel_answer.wheel_id = wheel.id')
                        ->where(['assessment_id' => $this->id, 'type' => $type])
                        ->scalar();
        ;
    }

    public function getIndividualWheelStatus()
    {
        $answers = $this->wheelStatus(Wheel::TYPE_INDIVIDUAL);
        $members = count($this->team->members);
        $questions = $members * WheelQuestion::getQuestionCount(Wheel::TYPE_INDIVIDUAL);
        if ($questions == 0)
            $questions = 1;

        return round($answers / $questions * 100, 1) . '%';
    }

    public function getGroupWheelStatus()
    {
        $answers = $this->wheelStatus(Wheel::TYPE_GROUP);
        $members = count($this->team->members);
        $questions = $members * $members * WheelQuestion::getQuestionCount(Wheel::TYPE_GROUP);
        if ($questions == 0)
            $questions = 1;
        return round($answers / $questions * 100, 1) . '%';
    }

    public function getOrganizationalWheelStatus()
    {
        $answers = $this->wheelStatus(Wheel::TYPE_ORGANIZATIONAL);
        $members = count($this->team->members);
        $questions = $members * $members * WheelQuestion::getQuestionCount(Wheel::TYPE_ORGANIZATIONAL);
        if ($questions == 0)
            $questions = 1;
        return round($answers / $questions * 100, 1) . '%';
    }

    public function getWheels()
    {
        return $this->hasMany(Wheel::className(), ['assessment_id' => 'id']);
    }

    public function getIndividualWheels()
    {
        return $this->hasMany(Wheel::className(), ['assessment_id' => 'id'])->where(['type' => Wheel::TYPE_INDIVIDUAL])->with('answers');
    }

    public function getGroupWheels()
    {
        return $this->hasMany(Wheel::className(), ['assessment_id' => 'id'])->where(['type' => Wheel::TYPE_GROUP])->with('answers');
    }

    public function getOrganizationalWheels()
    {
        return $this->hasMany(Wheel::className(), ['assessment_id' => 'id'])->where(['type' => Wheel::TYPE_ORGANIZATIONAL])->with('answers');
    }

    public function getAssessmentCoaches()
    {
        return $this->hasMany(AssessmentCoach::className(), ['assessment_id' => 'id']);
    }

    public function getAccessGranted()
    {
        return self::find()
                        ->where([
                            'assessment_id' => $this->id,
                            'coach_id' => Yii::$app->user->identity->id,
                        ])->exists();
    }

    static public function getDashboardList($teamId)
    {
        $assessments = self ::find()
                ->innerJoin('team', 'team.id = assessment.team_id')
                ->leftJoin('assessment_coach', 'assessment_coach.assessment_id = assessment.id')
                ->where(['team.coach_id' => Yii::$app->user->id])
                ->orWhere(['assessment_coach.coach_id' => Yii::$app->user->id])
                ->andWhere(['team_id' => $teamId])
                ->with(['team', 'team.company'])
                ->all();
        return ArrayHelper::map($assessments, 'id', 'name');
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
                ->where(['assessment_id' => $this->id])
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
        if ($this->team->coach_id == Yii::$app->user->id) {
            return true;
        }

        foreach ($this->assessmentCoaches as $assessmentCoach) {
            if ($assessmentCoach->coach->id == Yii::$app->user->id) {
                return true;
            }
        }
        return false;
    }

}
