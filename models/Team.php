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
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Name'),
            'coach_id' => Yii::t('team', 'Coach'),
            'sponsor_id' => Yii::t('team', 'Sponsor'),
            'company_id' => Yii::t('team', 'Company'),
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

        $assessments = $this->hasMany(Assessment::className(), ['team_id' => 'id']);
        $this->deletable = $assessments->count() == 0;

        parent::afterFind();
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->afterFind();
    }

    public static function browse()
    {
        return Team::find()->where(['coach_id' => Yii::$app->user->id])->orderBy('id desc');
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

    public function getAssessments()
    {
        return $this->hasMany(Assessment::className(), ['team_id' => 'id']);
    }

    static public function getDashboardList($companyId)
    {
        $teams = self ::find()
                ->leftJoin('assessment', 'assessment.team_id = team.id')
                ->leftJoin('assessment_coach', 'assessment_coach.assessment_id = assessment.id')
                ->where(['team.coach_id' => Yii::$app->user->id])
                ->orWhere(['assessment_coach.coach_id' => Yii::$app->user->id])
                ->andWhere(['team.company_id' => $companyId])
                ->with(['coach', 'company'])
                ->all();

        return ArrayHelper::map($teams, 'id', 'name');
    }

}
