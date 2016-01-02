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
            'token' => Yii::t('wheel', 'Wheel token'),
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
        $count = count($this->answers);
        $questionCount = WheelQuestion::getQuestionCount($this->type);
        return round($count * 100 / $questionCount, 1) . '%';
    }

    public function getObserver() {
        return $this->hasOne(Person::className(), ['id' => 'observer_id']);
    }

    public function getObserved() {
        return $this->hasOne(Person::className(), ['id' => 'observed_id']);
    }

    public function getCoach() {
        return User::findOne(['id' => $this->observer->coach_id]);
    }

    public function getAssessment() {
        return Assessment::findOne(['id' => $this->assessment_id]);
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

    public static function browse($personId) {
        return (new Query())->select('id, date')
                        ->from('wheel')
                        ->where(['person_id' => $personId])
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
        $answers = [];
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

    private static function getReflectedWheel($assessmentId, $memberId, $type) {
        $rawAnswers = (new Query())->select('wheel_answer.dimension, avg(wheel_answer.answer_value) as value')
                ->from('wheel_answer')
                ->innerJoin('wheel', 'wheel.id = wheel_answer.wheel_id')
                ->innerJoin('assessment', 'assessment.id = wheel.assessment_id')
                ->where("wheel.observer_id <> $memberId and wheel.observed_id = $memberId and assessment.id = $assessmentId and wheel.type = " . $type)
                ->groupBy('wheel_answer.dimension')
                ->all();
        $answers = [];
        foreach ($rawAnswers as $rawAnswer)
            $answers[] = $rawAnswer['value'];
        return $answers;
    }

    public static function getReflectedGroupWheel($assessmentId, $memberId) {
        return self::getReflectedWheel($assessmentId, $memberId, Wheel::TYPE_GROUP);
    }

    public static function getReflectedOrganizationalWheel($assessmentId, $memberId) {
        return self::getReflectedWheel($assessmentId, $memberId, Wheel::TYPE_ORGANIZATIONAL);
    }

    public static function getPerformanceMatrix($assessmentId, $type) {
        $reflectedValues = (new Query)->select('wheel.observed_id, avg(wheel_answer.answer_value) as value')
                ->from('wheel_answer')
                ->innerJoin('wheel', 'wheel.id = wheel_answer.wheel_id')
                ->innerJoin('assessment', 'assessment.id = wheel.assessment_id')
                ->where("assessment.id = $assessmentId and wheel.type = $type and wheel.observer_id <> wheel.observed_id")
                ->groupBy('wheel.observed_id')
                ->all();

        $projectedValues = (new Query)->select('wheel.observed_id, avg(wheel_answer.answer_value) as value, user.name, user.surname')
                ->from('wheel_answer')
                ->innerJoin('wheel', 'wheel.id = wheel_answer.wheel_id')
                ->innerJoin('assessment', 'assessment.id = wheel.assessment_id')
                ->innerJoin('user', 'user.id = wheel.observed_id')
                ->where("assessment.id = $assessmentId and wheel.type = $type and wheel.observer_id = wheel.observed_id")
                ->groupBy('wheel.observed_id')
                ->all();

        $result = [];
        foreach ($reflectedValues as $reflectedValue)
            foreach ($projectedValues as $projectedValue)
                if ($reflectedValue['observed_id'] == $projectedValue['observed_id']) {
                    $result[] = [
                        'id' => $reflectedValue['observed_id'],
                        'name' => $projectedValue['name'] . ' ' . $projectedValue['surname'],
                        'productivity' => $reflectedValue['value'] / 4 * 100,
                        'consciousness' => ($projectedValue['value'] - $reflectedValue['value']) / 4 * 100
                    ];
                }

        return $result;
    }

    public static function getGauges($assessmentId, $type) {
        $rawAnswers = (new Query())->select('wheel_answer.dimension, avg(wheel_answer.answer_value) as value')
                ->from('wheel_answer')
                ->innerJoin('wheel', 'wheel.id = wheel_answer.wheel_id')
                ->innerJoin('assessment', 'assessment.id = wheel.assessment_id')
                ->where("assessment.id = $assessmentId and wheel.type = " . $type)
                ->groupBy('wheel_answer.dimension')
                ->all();
        $answers = [];
        foreach ($rawAnswers as $rawAnswer)
            $answers[] = $rawAnswer['value'];
        return $answers;
    }

    public static function getMemberGauges($assessmentId, $memberId, $type) {
        $rawAnswers = (new Query())->select('wheel_answer.dimension, avg(wheel_answer.answer_value) as value')
                ->from('wheel_answer')
                ->innerJoin('wheel', 'wheel.id = wheel_answer.wheel_id')
                ->innerJoin('assessment', 'assessment.id = wheel.assessment_id')
                ->where("wheel.observed_id = $memberId and assessment.id = $assessmentId and wheel.type = " . $type)
                ->groupBy('wheel_answer.dimension')
                ->all();
        $answers = [];
        foreach ($rawAnswers as $rawAnswer)
            $answers[] = $rawAnswer['value'];
        return $answers;
    }

    public static function getEmergents($assessmentId, $type) {
        $rawEmergents = (new Query)->select('wheel_question.dimension, wheel_answer.answer_order, wheel_question.question , avg(wheel_answer.answer_value) as value')
                ->from('wheel_answer')
                ->innerJoin('wheel', 'wheel.id = wheel_answer.wheel_id')
                ->innerJoin('assessment', 'assessment.id = wheel.assessment_id')
                ->innerJoin('wheel_question', 'wheel_question.order = wheel_answer.answer_order and wheel_question.type = wheel.type')
                ->where("assessment.id = $assessmentId and wheel.type = $type")
                ->groupBy('wheel_answer.answer_order, wheel_question.question')
                ->orderBy('avg(wheel_answer.answer_value) desc')
                ->all();

        return $rawEmergents;
    }

    public static function getMemberEmergents($assessmentId, $memberId, $type) {
        $rawEmergents = (new Query)->select('wheel_question.dimension, wheel_answer.answer_order, wheel_question.question ,'
                . ' avg( case when wheel.observed_id <> wheel.observer_id then wheel_answer.answer_value else null end) as value,'
                . 'avg( case when wheel.observed_id = wheel.observer_id then wheel_answer.answer_value else null end) as mine_value')
                ->from('wheel_answer')
                ->innerJoin('wheel', 'wheel.id = wheel_answer.wheel_id')
                ->innerJoin('assessment', 'assessment.id = wheel.assessment_id')
                ->innerJoin('wheel_question', 'wheel_question.order = wheel_answer.answer_order and wheel_question.type = wheel.type')
                ->where("assessment.id = $assessmentId and wheel.observed_id = $memberId and wheel.type = $type")
                ->groupBy('wheel_answer.answer_order, wheel_question.question')
                ->orderBy('avg(wheel_answer.answer_value) desc')
                ->all();

        return $rawEmergents;
    }

    public static function getRelationsMatrix($assessmentId, $type) {
        $rawAnswers = (new Query())->select('wheel.observer_id, wheel.observed_id, avg(wheel_answer.answer_value) as value, user.name, user.surname')
                ->from('wheel_answer')
                ->innerJoin('wheel', 'wheel.id = wheel_answer.wheel_id')
                ->innerJoin('assessment', 'assessment.id = wheel.assessment_id')
                ->innerJoin('user', 'user.id = wheel.observer_id')
                ->where("assessment.id = $assessmentId and wheel.type = " . $type)
                ->groupBy('wheel.observer_id, wheel.observed_id, user.name, user.surname')
                ->all();

        return $rawAnswers;
    }

    public static function doesTokenExist($token) {
        return (new Query())->select('count(*)')
                        ->from('wheel')
                        ->where("token = $token")
                        ->scalar() > 0;
    }

}
