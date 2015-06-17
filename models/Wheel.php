<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Query;
use \yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * LoginForm is the model behind the login form.
 */
class Wheel extends ActiveRecord {

    const TYPE_INDIVIDUAL = 0;
    const TYPE_GROUP = 1;
    const TYPE_ORGANIZATIONAL = 2;

    public $dimensionAnswers = [0, 0, 0, 0, 0, 0, 0, 0];
    private $_answers_status = null;

    public function __construct() {
        $this->date = date("Y-m-d");
    }

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            [['observer_id', 'observed_id', 'date',], 'required'],
        ];
    }

    public function attributeLabels() {
        return [
            'date' => Yii::t('app', 'Date'),
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

    public function getAnswerStatus() {
        $count = WheelAnswer::findByCondition(['wheel_id' => $this->id])
                ->count();
        $questionCount = count(WheelQuestion::getQuestions($this->type));
        return ($count * 100 / $questionCount) . ' %';
    }

    public function getObserver() {
        return $this->hasOne(Coachee::className(), ['id' => 'observer_id']);
    }

    public function getObserved() {
        return $this->hasOne(Coachee::className(), ['id' => 'observed_id']);
    }

    public function getCoach() {
        return User::findOne(['id' => $this->observer->coach_id]);
    }

    public function getAnswers() {
        return $this->hasMany(WheelAnswer::className(), ['wheel_id' => 'id']);
    }

    public function afterFind() {
        parent::afterFind();

        foreach ($this->answers as $answer) {
            $this->dimensionAnswers[(int) ($answer['answer_order'] / 10)] += $answer['answer_value'];
        }
        for ($i = 0; $i < count($this->dimensionAnswers); $i++) {
            $this->dimensionAnswers[$i] = 1 + ($this->dimensionAnswers[$i] / 10);
        }
    }

    public function customSave($answers) {
        if (count($answers) < 80) {
            $this->addError('answers', Yii::t('wheel', 'Some answers left.'));
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        if (!$this->validate()) {
            $transaction->rollBack();
            return false;
        }
        if (!$this->save()) {
            $transaction->rollBack();
            return false;
        }

        foreach ($answers as $answer) {
            if (!$answer->validate()) {
                $transaction->rollBack();
                return false;
            }
            $this->link('answers', $answer, ['wheel_id', 'id']);
        }

        if (!$this->save()) {
            $transaction->rollBack();
            return false;
        }

        $transaction->commit();
        return true;
    }

    public static function browse($coacheeId) {
        return (new Query())->select('id, date')
                        ->from('wheel')
                        ->where(['coachee_id' => $coacheeId])
                        ->orderBy('id desc')
                        ->all();
    }

    public static function getWheelTypes() {
        return[
            self::TYPE_INDIVIDUAL => Yii::t('wheel', 'Individual Wheel'),
            self::TYPE_GROUP => Yii::t('wheel', 'Group Wheel'),
            self::TYPE_ORGANIZATIONAL => Yii::t('wheel', 'Organizational Wheel '),
        ];
    }

    private static function getProjectedWheel($assessmentId, $memberId, $type) {
        $rawAnswers = (new Query())->select('wheel_answer.dimension, avg(wheel_answer.answer_value) as value')
                ->from('wheel_answer')
                ->innerJoin('wheel', 'wheel.id = wheel_answer.wheel_id')
                ->innerJoin('assessment', 'assessment.id = wheel.assessment_id')
                ->where("wheel.observer_id = $memberId and wheel.observed_id = $memberId and assessment.id = $assessmentId and wheel.type = " . $type)
                ->groupBy('wheel_answer.dimension')
                ->all();

        foreach ($rawAnswers as $rawAnswer)
            $answers[] = $rawAnswer['value'];
        return $answers;
    }

    public static function getProjectedIndividualWheel($assessmentId, $memberId) {
        return self::getProjectedWheel($assessmentId, $memberId, Wheel::TYPE_INDIVIDUAL);
    }

    public static function getProjectedGroupWheel($assessmentId, $memberId) {
        return self::getProjectedWheel($assessmentId, $memberId, Wheel::TYPE_GROUP);
    }

    public static function getProjectedOrganizationalWheel($assessmentId, $memberId) {
        return self::getProjectedWheel($assessmentId, $memberId, Wheel::TYPE_ORGANIZATIONAL);
    }

}
