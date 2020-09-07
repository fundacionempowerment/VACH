<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class Company
 * @package app\models
 * @property integer id
 * @property string name
 * @property string email
 * @property string phone
 * @property integer coach_id
 * @property integer created_at
 * @property integer updated_at
 * @property string notes
 */

class Company extends ActiveRecord
{

    public $deletable;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'company';
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['name', 'email', 'coach_id'], 'required'],
            [['phone', 'notes'], 'safe'],
            [['notes'], 'string', 'max' => 1000],
            [['name', 'email', 'phone', 'notes'], 'filter', 'filter' => 'trim'],
            ['email', 'email'],
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Name'),
            'email' => Yii::t('app', 'Email'),
            'phone' => Yii::t('app', 'Phone'),
            'fullname' => Yii::t('app', 'Name'),
            'notes' => Yii::t('app', 'Notes'),
        ];
    }

    public function afterFind()
    {
        $teams = $this->hasMany(Team::class, ['company_id' => 'id']);
        $this->deletable = $teams->count() == 0;

        parent::afterFind();
    }

    public function beforeValidate()
    {
        if (!isset($this->coach_id)) {
            $this->coach_id = Yii::$app->user->id;
        }

        return parent::beforeValidate();
    }

    public static function browse()
    {
        return Company::find()
                        ->where(['company.coach_id' => Yii::$app->user->id])
                        ->orderBy('name');
    }

    public function getCoach()
    {
        return $this->hasOne(User::class, ['id' => 'coach_id']);
    }

    static public function getDashboardList()
    {
        $companies = Company::find()
                ->leftJoin('team', 'team.company_id = company.id')
                ->leftJoin('team_coach', 'team_coach.team_id = team.id')
                ->where(['company.coach_id' => Yii::$app->user->id])
                ->orWhere(['team_coach.coach_id' => Yii::$app->user->id])
                ->asArray()
                ->all();

        return ArrayHelper::map($companies, 'id', 'name');
    }
    
    public function userAllowed(){
        return $this->coach_id == Yii::$app->user->identity->id;
    }

}
