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

        return ($count * 100 / 80) . ' %';
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

}
